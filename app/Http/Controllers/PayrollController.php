<?php

namespace App\Http\Controllers;

use App\Models\PayrollGaji;
use App\Models\SdmEvaluasiKinerja;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $bulan = (int) $request->input('bulan', Carbon::now()->month);
        $tahun = (int) $request->input('tahun', Carbon::now()->year);

        $payrolls = PayrollGaji::with('pegawai')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        return view('payroll.index', compact('payrolls', 'bulan', 'tahun'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2020',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Ambil semua evaluasi kinerja yang sudah di-approve oleh SDM pada bulan/tahun terpilih
        $evaluasis = SdmEvaluasiKinerja::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('status_evaluasi', 'Approved')
            ->get();

        if ($evaluasis->isEmpty()) {
            return redirect()->back()->withErrors(['error' => 'Tidak ada Evaluasi Kinerja (Approved) dari SDM pada bulan dan tahun terpilih! Silakan selesaikan evaluasi kinerja di menu SDM terlebih dahulu.']);
        }

        DB::beginTransaction();
        try {
            foreach ($evaluasis as $eval) {
                $pegawai = Pegawai::find($eval->pegawai_id);
                if (!$pegawai) continue;

                // Tentukan standar gaji pokok & tunjangan berdasarkan jenis pegawai
                $gajiPokok = 6000000;
                $tunjanganKinerja = 2000000;

                if (in_array(trim($pegawai->jenispegawai), ['Programmer', 'pic_koordinator'])) {
                    $gajiPokok = 8000000;
                    $tunjanganKinerja = 4000000;
                } elseif (trim($pegawai->jenispegawai) === 'Analis') {
                    $gajiPokok = 7000000;
                    $tunjanganKinerja = 3000000;
                }

                // Kalkulasi potongan berdasarkan nilai evaluasi SDM
                $potongan = $tunjanganKinerja * ($eval->persentase_potongan / 100);
                $gajiDiterima = $gajiPokok + $tunjanganKinerja - $potongan;

                // Simpan atau update slip gaji bulanan
                PayrollGaji::updateOrCreate(
                    [
                        'pegawai_id' => $eval->pegawai_id,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                    ],
                    [
                        'gaji_pokok' => $gajiPokok,
                        'tunjangan_kinerja' => $tunjanganKinerja,
                        'potongan_performa' => $potongan,
                        'gaji_diterima' => $gajiDiterima,
                        'status_pembayaran' => 'Draft',
                    ]
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal men-generate penggajian: ' . $e->getMessage()]);
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Generate Payroll Karyawan',
            'details' => 'Generate payroll karyawan untuk bulan: ' . $bulan . ' tahun: ' . $tahun
        ]);

        return redirect()->back()->with('success', 'Payroll berhasil digenerate / diperbarui berdasarkan evaluasi SDM!');
    }

    public function pay($id)
    {
        $payroll = PayrollGaji::findOrFail($id);
        $payroll->update([
            'status_pembayaran' => 'Paid'
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Pembayaran Payroll Karyawan',
            'details' => 'Set status pembayaran Paid untuk Payroll ID: ' . $id
        ]);

        return redirect()->back()->with('success', 'Status pembayaran payroll diset menjadi dibayar (Paid)!');
    }
}
