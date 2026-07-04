<?php

namespace App\Http\Controllers;

use App\Models\MasterSla;
use App\Models\Prioritas;
use App\Models\Jenistask;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class SlaController extends Controller
{
    public function index()
    {
        $slas = MasterSla::with(['prioritas', 'jenistask'])->get();
        $prioritas = Prioritas::where('statusenabled', true)->get();
        $jenistasks = Jenistask::where('statusenabled', true)->get();

        return view('sla.index', compact('slas', 'prioritas', 'jenistasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'prioritas_id' => 'nullable|exists:prioritas_m,id',
            'jenistask_id' => 'nullable|exists:jenistask_m,id',
            'sla_hours' => 'required|integer|min:1',
        ]);

        // Cek duplikasi
        $exists = MasterSla::where('prioritas_id', $request->prioritas_id)
            ->where('jenistask_id', $request->jenistask_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['error' => 'Aturan SLA untuk kombinasi Prioritas & Jenis Task ini sudah ada!']);
        }

        MasterSla::create([
            'prioritas_id' => $request->prioritas_id,
            'jenistask_id' => $request->jenistask_id,
            'sla_hours' => $request->sla_hours,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Membuat Master SLA baru',
            'details' => 'Membuat SLA ' . $request->sla_hours . ' jam untuk prioritas_id: ' . $request->prioritas_id . ', jenistask_id: ' . $request->jenistask_id
        ]);

        return redirect()->back()->with('success', 'Master SLA berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'prioritas_id' => 'nullable|exists:prioritas_m,id',
            'jenistask_id' => 'nullable|exists:jenistask_m,id',
            'sla_hours' => 'required|integer|min:1',
        ]);

        $sla = MasterSla::findOrFail($id);

        // Cek duplikasi kecuali id ini
        $exists = MasterSla::where('prioritas_id', $request->prioritas_id)
            ->where('jenistask_id', $request->jenistask_id)
            ->where('id', '<>', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['error' => 'Aturan SLA untuk kombinasi Prioritas & Jenis Task ini sudah ada!']);
        }

        $sla->update([
            'prioritas_id' => $request->prioritas_id,
            'jenistask_id' => $request->jenistask_id,
            'sla_hours' => $request->sla_hours,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Memperbarui Master SLA',
            'details' => 'Memperbarui SLA ID ' . $id . ' menjadi ' . $request->sla_hours . ' jam'
        ]);

        return redirect()->back()->with('success', 'Master SLA berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $sla = MasterSla::findOrFail($id);
        $sla->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Menghapus Master SLA',
            'details' => 'Menghapus SLA ID ' . $id
        ]);

        return redirect()->back()->with('success', 'Master SLA berhasil dihapus!');
    }
}
