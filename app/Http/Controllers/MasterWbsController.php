<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterWbs;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class MasterWbsController extends Controller
{
    public function index()
    {
        $masterWbs = MasterWbs::orderBy('order_num', 'asc')->get();
        return view('wbs.master', compact('masterWbs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_struktur' => 'required|in:Kick-Off Meeting,Master Data,Assesment,Instalasi Sistem,Training,Bridging,Development & Quick Customize,Go - Live',
            'wbs_code' => 'required|string|max:50',
            'detail_task' => 'required|string|max:255',
            'task_to' => 'required|in:Vendor,Client,Both',
            'order_num' => 'required|integer',
        ]);

        MasterWbs::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Create Master WBS Task',
            'details' => "Membuat master WBS task: {$validated['wbs_code']} - {$validated['detail_task']}",
        ]);

        return redirect()->route('wbs.master')->with('success', 'Master WBS berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jenis_struktur' => 'required|in:Kick-Off Meeting,Master Data,Assesment,Instalasi Sistem,Training,Bridging,Development & Quick Customize,Go - Live',
            'wbs_code' => 'required|string|max:50',
            'detail_task' => 'required|string|max:255',
            'task_to' => 'required|in:Vendor,Client,Both',
            'order_num' => 'required|integer',
        ]);

        $item = MasterWbs::findOrFail($id);
        $item->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Update Master WBS Task',
            'details' => "Mengubah master WBS task ID {$id} menjadi: {$validated['wbs_code']} - {$validated['detail_task']}",
        ]);

        return redirect()->route('wbs.master')->with('success', 'Master WBS berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $item = MasterWbs::findOrFail($id);
        $item->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Delete Master WBS Task',
            'details' => "Menghapus master WBS task ID {$id} - {$item->detail_task}",
        ]);

        return redirect()->route('wbs.master')->with('success', 'Master WBS berhasil dihapus!');
    }
}
