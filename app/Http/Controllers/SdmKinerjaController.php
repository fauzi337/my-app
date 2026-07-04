<?php

namespace App\Http\Controllers;

use App\Models\SdmEvaluasiKinerja;
use App\Models\Pegawai;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SdmKinerjaController extends Controller
{
    public function index(Request $request)
    {
        $bulan = (int) $request->input('bulan', Carbon::now()->month);
        $tahun = (int) $request->input('tahun', Carbon::now()->year);

        $evaluasis = SdmEvaluasiKinerja::with('pegawai')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        // Cari daftar pegawai yang bertindak sebagai PIC Developer di database untuk dropdown/dashboard
        $pegawais = Pegawai::where('statusenabled', true)
            ->whereIn('jenispegawai', ['Programmer', 'Analis', 'pic_koordinator', 'Operator'])
            ->get();

        return view('sdm.index', compact('evaluasis', 'bulan', 'tahun', 'pegawais'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2020',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Ambil semua task yang selesai pada bulan dan tahun tersebut
        $tasks = Jadwal::where('statusenabled', true)
            ->whereNotNull('picdeveloper_id')
            ->whereNotNull('tgl_selesai')
            ->whereYear('tgl_selesai', $tahun)
            ->whereMonth('tgl_selesai', $bulan)
            ->get()
            ->groupBy('picdeveloper_id');

        if ($tasks->isEmpty()) {
            return redirect()->back()->withErrors(['error' => 'Tidak ada Timeline Request yang diselesaikan pada bulan dan tahun terpilih!']);
        }

        DB::beginTransaction();
        try {
            foreach ($tasks as $picId => $picTasks) {
                // Skip jika pegawai tidak ditemukan
                $pegawai = Pegawai::find($picId);
                if (!$pegawai) continue;

                $totalTask = $picTasks->count();
                $tepatWaktu = $picTasks->where('performa_score', '>=', 100)->count();
                $terlambat = $totalTask - $tepatWaktu;
                $rataRataSkor = $picTasks->avg('performa_score') ?? 100.00;

                // Tentukan persentase potongan berdasarkan rata-rata skor performa
                $persentasePotongan = 0.00;
                if ($rataRataSkor < 50) {
                    $persentasePotongan = 15.00; // Buruk -> potong 15% dari tunjangan kinerja
                } elseif ($rataRataSkor < 80) {
                    $persentasePotongan = 5.00;  // Kurang -> potong 5% dari tunjangan kinerja
                }

                // Simpan atau update record evaluasi
                SdmEvaluasiKinerja::updateOrCreate(
                    [
                        'pegawai_id' => $picId,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                    ],
                    [
                        'total_task' => $totalTask,
                        'task_tepat_waktu' => $tepatWaktu,
                        'task_terlambat' => $terlambat,
                        'rata_rata_skor' => $rataRataSkor,
                        'persentase_potongan' => $persentasePotongan,
                        'status_evaluasi' => 'Draft', // Default draft sebelum diapprove SDM
                    ]
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal men-generate evaluasi: ' . $e->getMessage()]);
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Generate Evaluasi Kinerja Bulanan',
            'details' => 'Generate evaluasi kinerja untuk bulan: ' . $bulan . ' tahun: ' . $tahun
        ]);

        return redirect()->back()->with('success', 'Evaluasi kinerja berhasil digenerate / diperbarui!');
    }

    public function approve($id)
    {
        $evaluasi = SdmEvaluasiKinerja::findOrFail($id);
        $evaluasi->update([
            'status_evaluasi' => 'Approved'
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Approve Evaluasi Kinerja',
            'details' => 'Menyetujui evaluasi kinerja ID: ' . $id . ' untuk Pegawai: ' . $evaluasi->pegawai_id
        ]);

        return redirect()->back()->with('success', 'Kinerja pegawai berhasil disetujui (Approved)!');
    }
}
