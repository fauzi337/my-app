<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterModul;
use App\Models\MasterModulDetail;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class MasterModulController extends Controller
{
    public function index()
    {
        $moduls = MasterModul::with('details')->orderBy('nama_modul')->get();
        return view('modul.master', compact('moduls'));
    }

    public function storeModul(Request $request)
    {
        $request->validate([
            'nama_modul' => 'required|string|max:255|unique:master_moduls,nama_modul',
        ]);

        $modul = MasterModul::create([
            'nama_modul' => $request->nama_modul,
            'statusenabled' => true,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Create Master Modul',
            'details' => "Membuat Master Modul: {$modul->nama_modul}",
        ]);

        return redirect()->back()->with('success', 'Master Modul berhasil ditambahkan!');
    }

    public function updateModul(Request $request, $id)
    {
        $modul = MasterModul::findOrFail($id);
        $request->validate([
            'nama_modul' => 'required|string|max:255|unique:master_moduls,nama_modul,' . $id,
        ]);

        $modul->update([
            'nama_modul' => $request->nama_modul,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Update Master Modul',
            'details' => "Mengubah Master Modul ID: {$id} menjadi {$modul->nama_modul}",
        ]);

        return redirect()->back()->with('success', 'Master Modul berhasil diubah!');
    }

    public function deleteModul($id)
    {
        $modul = MasterModul::findOrFail($id);
        $modul->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Delete Master Modul',
            'details' => "Menghapus Master Modul: {$modul->nama_modul}",
        ]);

        return redirect()->back()->with('success', 'Master Modul berhasil dihapus!');
    }

    public function storeDetail(Request $request, $modulId)
    {
        $request->validate([
            'nama_detail' => 'required|string|max:255',
        ]);

        $detail = MasterModulDetail::create([
            'master_modul_id' => $modulId,
            'nama_detail' => $request->nama_detail,
            'statusenabled' => true,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Create Master Modul Detail',
            'details' => "Menambahkan checklist item: {$detail->nama_detail} ke Modul ID: {$modulId}",
        ]);

        return redirect()->back()->with('success', 'Checklist item berhasil ditambahkan!');
    }

    public function updateDetail(Request $request, $id)
    {
        $detail = MasterModulDetail::findOrFail($id);
        $request->validate([
            'nama_detail' => 'required|string|max:255',
        ]);

        $detail->update([
            'nama_detail' => $request->nama_detail,
        ]);

        return redirect()->back()->with('success', 'Checklist item berhasil diubah!');
    }

    public function deleteDetail($id)
    {
        $detail = MasterModulDetail::findOrFail($id);
        $detail->delete();

        return redirect()->back()->with('success', 'Checklist item berhasil dihapus!');
    }
}
