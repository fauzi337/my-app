<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Site;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $selectedSiteId = $request->input('filter_site_id');

        $query = Pegawai::where('statusenabled', true);

        if ($selectedSiteId) {
            $query->where('site_id', $selectedSiteId);
        }

        $pegawais = $query->paginate(7);
        $sites = Site::where('statusenabled', true)->get();
        $parties = DB::table('parties_m')->where('statusenabled', true)->get();

        return view('pegawai.index', compact('pegawais', 'sites', 'parties', 'selectedSiteId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'namapegawai' => 'required|string|max:255',
            'namalengkap' => 'required|string|max:255',
            'jenispegawai' => 'required|string|max:100',
            'kdjenispegawai' => 'required|string|max:10',
            'produk' => 'nullable|string|max:100',
            'site_id' => 'nullable|exists:site_m,id',
            'parties_id' => 'required|exists:parties_m,id',
        ]);

        $newId = Pegawai::max('id') + 1;

        Pegawai::create([
            'id' => $newId,
            'namapegawai' => $request->namapegawai,
            'namalengkap' => $request->namalengkap,
            'jenispegawai' => $request->jenispegawai,
            'kdjenispegawai' => $request->kdjenispegawai,
            'produk' => $request->produk ?? 'SIMRS',
            'site_id' => $request->site_id,
            'parties_id' => $request->parties_id,
            'statusenabled' => true,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Tambah Pegawai Baru',
            'details' => 'Menambahkan pegawai ' . $request->namapegawai . ' dengan site_id: ' . $request->site_id . ', parties_id: ' . $request->parties_id,
        ]);

        return redirect()->back()->with('success', 'Pegawai baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'namapegawai' => 'required|string|max:255',
            'namalengkap' => 'required|string|max:255',
            'jenispegawai' => 'required|string|max:100',
            'kdjenispegawai' => 'required|string|max:10',
            'produk' => 'nullable|string|max:100',
            'site_id' => 'nullable|exists:site_m,id',
            'parties_id' => 'required|exists:parties_m,id',
        ]);

        $pegawai = Pegawai::findOrFail($id);
        $pegawai->update([
            'namapegawai' => $request->namapegawai,
            'namalengkap' => $request->namalengkap,
            'jenispegawai' => $request->jenispegawai,
            'kdjenispegawai' => $request->kdjenispegawai,
            'produk' => $request->produk ?? 'SIMRS',
            'site_id' => $request->site_id,
            'parties_id' => $request->parties_id,
            'statusenabled' => true, // Pertahankan true agar tetap aktif
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Perbarui Data Pegawai',
            'details' => 'Memperbarui data pegawai ID: ' . $id . ' - ' . $request->namapegawai,
        ]);

        return redirect()->back()->with('success', 'Data pegawai berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        // Soft delete dengan menonaktifkan statusenabled
        $pegawai->update(['statusenabled' => false]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Hapus Pegawai',
            'details' => 'Menonaktifkan pegawai ID: ' . $id,
        ]);

        return redirect()->back()->with('success', 'Pegawai berhasil dinonaktifkan (deleted)!');
    }
}
