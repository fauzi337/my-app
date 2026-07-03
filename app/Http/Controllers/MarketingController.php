<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\Proposal;
use App\Models\Project;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Timeline;
use App\Models\Site;
use App\Models\Jadwal;
use App\Models\Activity;
use App\Models\AuditLog;
use App\Models\MeetingResult;
use App\Models\Won;
use App\Models\WonDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MarketingController extends Controller
{
    /**
     * Display Marketing Dashboard & Stats.
     */
    public function dashboard(Request $request)
    {
        $today = Carbon::today();

        // 1. Funnel Pipeline
        $funnel = Lead::select('pipeline_status', DB::raw('count(*) as total'))
            ->groupBy('pipeline_status')
            ->pluck('total', 'pipeline_status')
            ->toArray();

        $allStatuses = ['New', 'Qualified', 'Demo', 'Proposal', 'Negotiation', 'Won', 'Lost', 'Nurture'];
        $funnelStats = [];
        foreach ($allStatuses as $status) {
            $funnelStats[$status] = $funnel[$status] ?? 0;
        }

        // 2. Revenue Pipeline (Total estimasi_nilai of active leads: not Won or Lost)
        $revenuePipeline = Lead::whereNotIn('pipeline_status', ['Won', 'Lost'])->sum('estimasi_nilai');

        // 3. Lead Aging (Leads with no activity for > 14 days)
        $agingLeadsCount = Lead::where(function ($query) {
            $query->whereRaw('(SELECT MAX(tanggal_aktivitas) FROM lead_activities WHERE lead_id = leads.id) < ?', [Carbon::now()->subDays(14)])
                  ->orWhere(function ($q) {
                      $q->whereNotExists(function ($sub) {
                          $sub->select(DB::raw(1))
                              ->from('lead_activities')
                              ->whereColumn('lead_id', 'leads.id');
                      })
                      ->where('created_at', '<', Carbon::now()->subDays(14));
                  });
        })
        ->whereNotIn('pipeline_status', ['Won', 'Lost'])
        ->count();

        // 4. Follow-up Today
        $followupsToday = Lead::whereDate('tanggal_followup_berikutnya', $today)
            ->whereNotIn('pipeline_status', ['Won', 'Lost'])
            ->get();

        // 5. Conversion Rate (Qualified -> Won)
        $totalQualified = Lead::whereIn('pipeline_status', ['Qualified', 'Demo', 'Proposal', 'Negotiation', 'Won', 'Lost', 'Nurture'])->count();
        $totalWon = Lead::where('pipeline_status', 'Won')->count();
        $conversionRate = $totalQualified > 0 ? round(($totalWon / $totalQualified) * 100, 1) : 0;

        // 6. Active Leads Table & Filters
        $query = Lead::with(['picInternal', 'activities', 'proposals']);

        if ($request->filled('status')) {
            $query->where('pipeline_status', $request->status);
        }
        if ($request->filled('pic')) {
            $query->where('pic_internal', $request->pic);
        }
        if ($request->filled('provinsi')) {
            $query->where('provinsi', 'like', '%' . $request->provinsi . '%');
        }
        if ($request->filled('kota')) {
            $query->where('kota', 'like', '%' . $request->kota . '%');
        }

        $activeLeads = $query->orderBy('created_at', 'desc')->get();

        $pics = User::where('statusenabled', true)->get();
        $provinsis = Lead::select('provinsi')->distinct()->pluck('provinsi')->filter()->toArray();

        return view('marketing.dashboard', compact(
            'funnelStats',
            'revenuePipeline',
            'agingLeadsCount',
            'followupsToday',
            'conversionRate',
            'activeLeads',
            'pics',
            'provinsis',
            'allStatuses'
        ));
    }

    /**
     * Show Lead Form.
     */
    public function createLead()
    {
        $pics = User::all();
        return view('marketing.leads.create', compact('pics'));
    }

    /**
     * Store Lead.
     */
    public function storeLead(Request $request)
    {
        $validated = $request->validate([
            'nama_institusi' => 'required|string|max:255',
            'jenis_institusi' => 'required|in:RS_Umum,RS_Khusus,Klinik,Puskesmas,Lainnya',
            'kota' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'ukuran' => 'required|in:Kecil,Menengah,Besar',
            'sumber_lead' => 'required|in:Referral,Website,Event,Cold_Outreach,Lainnya',
            'pic_klien' => 'required|string|max:255',
            'jabatan_pic' => 'required|string|max:255',
            'no_hp_pic' => 'required|string|max:255',
            'email_pic' => 'required|email|max:255',
            'estimasi_nilai' => 'required|integer|min:0',
            'modul_diminati' => 'required|array',
            'modul_diminati.*' => 'string',
            'pic_internal' => 'required|exists:users,id',
            'tanggal_masuk' => 'required|date',
            'target_closing' => 'nullable|date|after_or_equal:tanggal_masuk',
            'catatan' => 'nullable|string',
        ]);

        $lead = Lead::create([
            'nama_institusi' => $validated['nama_institusi'],
            'jenis_institusi' => $validated['jenis_institusi'],
            'kota' => $validated['kota'],
            'provinsi' => $validated['provinsi'],
            'ukuran' => $validated['ukuran'],
            'sumber_lead' => $validated['sumber_lead'],
            'pic_klien' => $validated['pic_klien'],
            'jabatan_pic' => $validated['jabatan_pic'],
            'no_hp_pic' => $validated['no_hp_pic'],
            'email_pic' => $validated['email_pic'],
            'pipeline_status' => 'New',
            'estimasi_nilai' => $validated['estimasi_nilai'],
            'modul_diminati' => $validated['modul_diminati'],
            'pic_internal' => $validated['pic_internal'],
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'target_closing' => $validated['target_closing'],
            'catatan' => $validated['catatan'],
        ]);

        // Create initial activity log
        LeadActivity::create([
            'lead_id' => $lead->id,
            'tipe_aktivitas' => 'Follow_Up',
            'tanggal_aktivitas' => now(),
            'pic_internal' => Auth::id() ?? $lead->pic_internal,
            'deskripsi' => 'Lead baru dibuat dengan status: New',
            'hasil' => 'Netral',
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Lead dibuat',
            'details' => 'Membuat lead untuk institusi: ' . $lead->nama_institusi,
        ]);

        return redirect()->route('marketing.dashboard')->with('success', 'Lead berhasil dibuat!');
    }

    /**
     * Show Lead Detail.
     */
    public function showLead($id)
    {
        $lead = Lead::with(['picInternal', 'activities.meeting', 'proposals.creator', 'projects'])->findOrFail($id);
        $meetings = MeetingResult::where('statusenabled', true)->orderBy('tgl_realisasi', 'desc')->get();

        return view('marketing.leads.show', compact('lead', 'meetings'));
    }

    /**
     * Edit Lead Form.
     */
    public function editLead($id)
    {
        $lead = Lead::findOrFail($id);
        $pics = User::all();
        return view('marketing.leads.edit', compact('lead', 'pics'));
    }

    /**
     * Update Lead.
     */
    public function updateLead(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);

        $validated = $request->validate([
            'nama_institusi' => 'required|string|max:255',
            'jenis_institusi' => 'required|in:RS_Umum,RS_Khusus,Klinik,Puskesmas,Lainnya',
            'kota' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'ukuran' => 'required|in:Kecil,Menengah,Besar',
            'sumber_lead' => 'required|in:Referral,Website,Event,Cold_Outreach,Lainnya',
            'pic_klien' => 'required|string|max:255',
            'jabatan_pic' => 'required|string|max:255',
            'no_hp_pic' => 'required|string|max:255',
            'email_pic' => 'required|email|max:255',
            'pipeline_status' => 'required|in:New,Qualified,Demo,Proposal,Negotiation,Won,Lost,Nurture',
            'alasan_lost' => 'required_if:pipeline_status,Lost|nullable|string',
            'estimasi_nilai' => 'required|integer|min:0',
            'modul_diminati' => 'required|array',
            'modul_diminati.*' => 'string',
            'pic_internal' => 'required|exists:users,id',
            'tanggal_masuk' => 'required|date',
            'target_closing' => 'nullable|date|after_or_equal:tanggal_masuk',
            'catatan' => 'nullable|string',
        ]);

        $oldStatus = $lead->pipeline_status;
        $newStatus = $validated['pipeline_status'];

        // Enforce Business Rules:
        // Status can only move forward or move backward to Nurture
        if ($oldStatus !== $newStatus) {
            $allowed = false;
            if ($newStatus === 'Nurture') {
                $allowed = true;
            } else {
                $stageIndices = [
                    'New' => 0,
                    'Qualified' => 1,
                    'Demo' => 2,
                    'Proposal' => 3,
                    'Negotiation' => 4,
                    'Won' => 5,
                    'Lost' => 5,
                    'Nurture' => -1
                ];

                $currentIndex = $stageIndices[$oldStatus] ?? 0;
                $newIndex = $stageIndices[$newStatus] ?? 0;

                if ($oldStatus === 'Nurture') {
                    $allowed = true; // From Nurture, can move to any stage
                } elseif ($newIndex > $currentIndex) {
                    $allowed = true; // Moving forward is allowed
                }
            }

            if (!$allowed) {
                return redirect()->back()->withErrors(['pipeline_status' => 'Status Lead hanya bisa maju ke tahap berikutnya atau mundur ke Nurture.']);
            }
        }

        // If changed to Won, they must fill the contract handoff form
        if ($newStatus === 'Won' && $oldStatus !== 'Won') {
            return redirect()->route('marketing.leads.handoff', $lead->id);
        }

        $lead->update($validated);

        if ($oldStatus !== $newStatus) {
            LeadActivity::create([
                'lead_id' => $lead->id,
                'tipe_aktivitas' => 'Follow_Up',
                'tanggal_aktivitas' => now(),
                'pic_internal' => Auth::id() ?? $lead->pic_internal,
                'deskripsi' => "Status diubah dari $oldStatus menjadi $newStatus. " . ($newStatus === 'Lost' ? "Alasan: " . $request->alasan_lost : ''),
                'hasil' => $newStatus === 'Lost' ? 'Negatif' : 'Netral',
            ]);
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Lead diperbarui',
            'details' => 'Memperbarui lead ID: ' . $lead->id . ' untuk institusi: ' . $lead->nama_institusi,
        ]);

        return redirect()->route('marketing.leads.show', $lead->id)->with('success', 'Lead berhasil diperbarui!');
    }

    /**
     * Soft Delete Lead.
     */
    public function destroyLead($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Lead dihapus',
            'details' => 'Menghapus lead: ' . $lead->nama_institusi,
        ]);

        return redirect()->route('marketing.dashboard')->with('success', 'Lead berhasil dihapus!');
    }

    /**
     * Store Lead Activity.
     */
    public function storeActivity(Request $request, $leadId)
    {
        $lead = Lead::findOrFail($leadId);

        $validated = $request->validate([
            'tipe_aktivitas' => 'required|in:Telepon,Email,Kunjungan,Demo,Presentasi,Follow_Up,Kickoff,Lainnya',
            'tanggal_aktivitas' => 'required|date_format:Y-m-d\TH:i',
            'deskripsi' => 'required|string',
            'hasil' => 'required|in:Positif,Netral,Negatif',
            'tindak_lanjut' => 'nullable|string',
            'tanggal_followup_berikutnya' => 'nullable|date|after_or_equal:tanggal_aktivitas',
            'meeting_id' => 'nullable|exists:meeting_result,id',
            'next_pipeline_status' => 'nullable|in:New,Qualified,Demo,Proposal,Negotiation,Won,Lost,Nurture',
        ]);

        $meetingId = $validated['meeting_id'];

        // Business Rule: Create Meeting redirect logic handled in UI/Controller
        // Check next_pipeline_status
        $oldStatus = $lead->pipeline_status;
        $newStatus = $request->next_pipeline_status;

        if ($newStatus && $newStatus !== $oldStatus) {
            if ($newStatus === 'Won') {
                // If Won, save activity but redirect to handoff for project creation
                session()->put('pending_activity_' . $lead->id, $validated);
                return redirect()->route('marketing.leads.handoff', $lead->id);
            }

            $lead->update([
                'pipeline_status' => $newStatus,
                'alasan_lost' => $newStatus === 'Lost' ? $request->alasan_lost : null,
            ]);

            // Add auto-generated activity log for status change
            LeadActivity::create([
                'lead_id' => $lead->id,
                'tipe_aktivitas' => 'Follow_Up',
                'tanggal_aktivitas' => now(),
                'pic_internal' => Auth::id(),
                'deskripsi' => "Status diubah otomatis dari $oldStatus menjadi $newStatus.",
                'hasil' => $newStatus === 'Lost' ? 'Negatif' : 'Netral',
            ]);
        }

        if ($request->filled('tanggal_followup_berikutnya')) {
            $lead->update([
                'tanggal_followup_berikutnya' => $validated['tanggal_followup_berikutnya']
            ]);
        }

        $activity = LeadActivity::create([
            'lead_id' => $lead->id,
            'tipe_aktivitas' => $validated['tipe_aktivitas'],
            'tanggal_aktivitas' => Carbon::parse($validated['tanggal_aktivitas']),
            'pic_internal' => Auth::id(),
            'deskripsi' => $validated['deskripsi'],
            'hasil' => $validated['hasil'],
            'tindak_lanjut' => $validated['tindak_lanjut'],
            'tanggal_followup_berikutnya' => $validated['tanggal_followup_berikutnya'],
            'meeting_id' => $meetingId,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Aktivitas Lead dibuat',
            'details' => 'Membuat aktivitas tipe: ' . $activity->tipe_aktivitas . ' untuk lead: ' . $lead->nama_institusi,
        ]);

        return redirect()->route('marketing.leads.show', $lead->id)->with('success', 'Aktivitas berhasil ditambahkan!');
    }

    /**
     * Store Proposal.
     */
    public function storeProposal(Request $request, $leadId)
    {
        $lead = Lead::findOrFail($leadId);

        $validated = $request->validate([
            'tanggal_proposal' => 'required|date',
            'nilai_penawaran' => 'required|integer|min:0',
            'masa_implementasi_bulan' => 'required|integer|min:1',
            'catatan_scope' => 'required|string',
            'file_proposal' => 'required|file|mimes:pdf|max:10240', // PDF max 10MB
            'modul_ditawarkan' => 'required|array',
            'modul_ditawarkan.*' => 'string',
        ]);

        // Upload file
        $path = $request->file('file_proposal')->store('proposals', 'public');
        $fileUrl = Storage::url($path);

        // Auto-generate proposal number: PROP-YYYY-MM-XXX
        $year = Carbon::parse($validated['tanggal_proposal'])->format('Y');
        $month = Carbon::parse($validated['tanggal_proposal'])->format('m');
        
        $countThisMonth = Proposal::whereYear('tanggal_proposal', $year)
            ->whereMonth('tanggal_proposal', $month)
            ->count() + 1;
        
        $nomorProposal = 'PROP-' . $year . '-' . $month . '-' . str_pad($countThisMonth, 3, '0', STR_PAD_LEFT);

        // Version control
        $latestProposal = Proposal::where('lead_id', $lead->id)->orderBy('versi', 'desc')->first();
        $versi = $latestProposal ? $latestProposal->versi + 1 : 1;

        $proposal = Proposal::create([
            'lead_id' => $lead->id,
            'nomor_proposal' => $nomorProposal,
            'tanggal_proposal' => $validated['tanggal_proposal'],
            'versi' => $versi,
            'modul_ditawarkan' => $validated['modul_ditawarkan'],
            'nilai_penawaran' => $validated['nilai_penawaran'],
            'masa_implementasi_bulan' => $validated['masa_implementasi_bulan'],
            'catatan_scope' => $validated['catatan_scope'],
            'file_proposal' => $fileUrl,
            'status_proposal' => 'Draft',
            'created_by' => Auth::id(),
        ]);

        LeadActivity::create([
            'lead_id' => $lead->id,
            'tipe_aktivitas' => 'Follow_Up',
            'tanggal_aktivitas' => now(),
            'pic_internal' => Auth::id(),
            'deskripsi' => "Proposal Baru dibuat: $nomorProposal (Versi: $versi) senilai Rp " . number_format($validated['nilai_penawaran'], 0, ',', '.'),
            'hasil' => 'Positif',
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Proposal dibuat',
            'details' => 'Membuat proposal: ' . $nomorProposal . ' versi: ' . $versi,
        ]);

        return redirect()->route('marketing.leads.show', $lead->id)->with('success', 'Proposal berhasil disimpan!');
    }

    /**
     * Update Proposal Status.
     */
    public function updateProposalStatus(Request $request, $proposalId)
    {
        $proposal = Proposal::findOrFail($proposalId);
        $lead = Lead::findOrFail($proposal->lead_id);

        $request->validate([
            'status_proposal' => 'required|in:Draft,Terkirim,Revisi,Disetujui,Ditolak',
            'catatan_revisi' => 'nullable|string',
        ]);

        $oldStatus = $proposal->status_proposal;
        $newStatus = $request->status_proposal;

        $proposal->update([
            'status_proposal' => $newStatus,
            'catatan_revisi' => $newStatus === 'Revisi' ? $request->catatan_revisi : $proposal->catatan_revisi,
            'tanggal_respon_klien' => in_array($newStatus, ['Disetujui', 'Ditolak', 'Revisi']) ? now()->format('Y-m-d') : $proposal->tanggal_respon_klien,
            'tanggal_kirim' => $newStatus === 'Terkirim' ? now()->format('Y-m-d') : $proposal->tanggal_kirim,
        ]);

        LeadActivity::create([
            'lead_id' => $lead->id,
            'tipe_aktivitas' => 'Follow_Up',
            'tanggal_aktivitas' => now(),
            'pic_internal' => Auth::id(),
            'deskripsi' => "Status Proposal {$proposal->nomor_proposal} (Versi: {$proposal->versi}) diubah dari $oldStatus menjadi $newStatus.",
            'hasil' => $newStatus === 'Disetujui' ? 'Positif' : ($newStatus === 'Ditolak' ? 'Negatif' : 'Netral'),
        ]);

        // Auto-change Lead status to Won if proposal is Disetujui
        if ($newStatus === 'Disetujui') {
            return redirect()->route('marketing.leads.handoff', $lead->id)->with('success', 'Proposal disetujui! Silakan isi formulir serah terima proyek.');
        }

        return redirect()->route('marketing.leads.show', $lead->id)->with('success', 'Status proposal berhasil diupdate!');
    }

    /**
     * Show Handoff form for Won Lead.
     */
    public function showHandoffForm($leadId)
    {
        $lead = Lead::with('proposals')->findOrFail($leadId);
        $latestProposal = Proposal::where('lead_id', $lead->id)->orderBy('versi', 'desc')->first();
        
        $picKoordinators = Pegawai::where('statusenabled', true)
            ->where('jenispegawai', 'pic_koordinator')
            ->where('parties_id', 6)
            ->get();

        return view('marketing.leads.handoff', compact('lead', 'latestProposal', 'picKoordinators'));
    }

    /**
     * Process Won Lead Handoff -> Project + Timeline Request
     */
    public function processWonHandoff(Request $request, $leadId)
    {
        $lead = Lead::findOrFail($leadId);
        $latestProposal = Proposal::where('lead_id', $lead->id)->orderBy('versi', 'desc')->first();

        $request->validate([
            'project_name' => 'required|string|max:255',
            'nilai_kontrak' => 'required|integer|min:0',
            'tanggal_kontrak' => 'required|date',
            'file_kontrak' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'pic_koordinator_id' => 'required|exists:pegawai_m,id',
            'pic_request' => 'required|string|max:255',
            'target_go_live' => 'required|date|after_or_equal:tanggal_kontrak',
        ]);

        // Upload contract
        $path = $request->file('file_kontrak')->store('contracts', 'public');
        $contractUrl = Storage::url($path);

        DB::beginTransaction();
        try {
            // Find or create pegawai for PIC Request
            $picRequestName = trim($request->pic_request);
            $pegawaiRequest = DB::table('pegawai_m')
                ->where('namapegawai', 'like', '%' . $picRequestName . '%')
                ->first();
            
            if (!$pegawaiRequest) {
                $pegawaiRequestId = DB::table('pegawai_m')->max('id') + 1;
                DB::table('pegawai_m')->insert([
                    'id' => $pegawaiRequestId,
                    'namapegawai' => $picRequestName,
                    'namalengkap' => $picRequestName,
                    'jenispegawai' => 'Operator', // default type for client
                    'kdjenispegawai' => 'OS',
                    'statusenabled' => true,
                    'created_at' => now(),
                ]);
            } else {
                $pegawaiRequestId = $pegawaiRequest->id;
            }

            // 1. Create or Find Site for the new institution
            $site = Site::where('namasite', 'like', '%' . $lead->nama_institusi . '%')->first();
            if (!$site) {
                // Generate 4-letter uppercase code
                $kdSite = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $lead->nama_institusi), 0, 4));
                while (DB::table('site_m')->where('kdsite', $kdSite)->exists()) {
                    $kdSite = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $lead->nama_institusi), 0, 3) . rand(0, 9));
                }

                $siteId = DB::table('site_m')->max('id') + 1;
                DB::table('site_m')->insert([
                    'id' => $siteId,
                    'namasite' => $lead->nama_institusi,
                    'kdsite' => $kdSite,
                    'statusenabled' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $siteId = $site->id;
                $kdSite = $site->kdsite;
            }

            // 2. Create Project
            $projectCode = 'PRJ-' . trim($kdSite) . '-' . strtoupper(substr(uniqid(), -5));
            $project = Project::create([
                'project_code' => $projectCode,
                'project_name' => $request->project_name,
                'site_id' => $siteId,
                'description' => "Proyek hasil konversi marketing untuk lead: " . $lead->nama_institusi,
                'start_date' => $request->tanggal_kontrak,
                'target_date' => $request->target_go_live,
                'status' => 'Open',
                'progress' => 0,
                'statusenabled' => true,
                'lead_id' => $lead->id,
                'proposal_id' => $latestProposal ? $latestProposal->id : null,
                'nilai_kontrak' => $request->nilai_kontrak,
                'tanggal_kontrak' => $request->tanggal_kontrak,
                'file_kontrak' => $contractUrl,
            ]);

            // 3. Create Won Record (Project Handoff)
            $won = Won::create([
                'lead_id' => $lead->id,
                'proposal_id' => $latestProposal ? $latestProposal->id : null,
                'project_name' => $request->project_name,
                'site_id' => $siteId,
                'nilai_kontrak' => $request->nilai_kontrak,
                'tanggal_kontrak' => $request->tanggal_kontrak,
                'file_kontrak' => $contractUrl,
                'pic_koordinator_id' => $request->pic_koordinator_id,
                'pic_request' => $request->pic_request,
                'target_go_live' => $request->target_go_live,
                'status' => 'Initiated',
            ]);

            // 4. Create Won Details (contracted modules)
            $modules = $latestProposal ? $latestProposal->modul_ditawarkan : $lead->modul_diminati;
            foreach ($modules as $module) {
                $wonDetail = WonDetail::create([
                    'won_id' => $won->id,
                    'modul_name' => $module,
                    'pic_developer_id' => null, // Unassigned/Not Delegated
                    'progress' => 0,
                    'status' => 'To Do',
                ]);

                // Look up Master Modul
                $masterModul = \App\Models\MasterModul::where('nama_modul', 'like', trim($module))->first();
                if ($masterModul) {
                    $details = $masterModul->details()->where('statusenabled', true)->get();
                } else {
                    $details = collect();
                }

                if ($details->isNotEmpty()) {
                    foreach ($details as $det) {
                        \App\Models\WonDetailChecklist::create([
                            'won_detail_id' => $wonDetail->id,
                            'nama_detail' => $det->nama_detail,
                            'is_checked' => false,
                            'checked_at' => null,
                        ]);
                    }
                } else {
                    // Create default checklists if no master module template exists or has no items
                    $defaultChecklists = [
                        'Asesmen Kebutuhan & Integrasi',
                        'Setup & Konfigurasi Parameter',
                        'Uji Coba Fungsionalitas Modul',
                        'Training User / Operator'
                    ];
                    foreach ($defaultChecklists as $dCheck) {
                        \App\Models\WonDetailChecklist::create([
                            'won_detail_id' => $wonDetail->id,
                            'nama_detail' => $dCheck,
                            'is_checked' => false,
                            'checked_at' => null,
                        ]);
                    }
                }
            }

            // 4.1 Copy Master WBS to Project WBS
            $masterWbsList = \App\Models\MasterWbs::where('statusenabled', true)->orderBy('order_num', 'asc')->get();
            foreach ($masterWbsList as $mwbs) {
                \App\Models\ProjectWbs::create([
                    'won_id' => $won->id,
                    'jenis_struktur' => $mwbs->jenis_struktur,
                    'wbs_code' => $mwbs->wbs_code,
                    'detail_task' => $mwbs->detail_task,
                    'task_to' => $mwbs->task_to,
                    'order_num' => $mwbs->order_num,
                    'status' => 'NOT STARTED',
                ]);
            }

            // 5. Update Lead to Won
            $lead->update([
                'pipeline_status' => 'Won',
                'target_closing' => $request->tanggal_kontrak,
            ]);

            // 6. Create Activity Log
            LeadActivity::create([
                'lead_id' => $lead->id,
                'tipe_aktivitas' => 'Lainnya',
                'tanggal_aktivitas' => now(),
                'pic_internal' => Auth::id(),
                'deskripsi' => "Kontrak ditandatangani — Proyek $projectCode berhasil dibuat untuk institusi " . $lead->nama_institusi,
                'hasil' => 'Positif',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Serah terima gagal: ' . $e->getMessage()]);
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Handoff Project Won',
            'details' => 'Membuat project: ' . $request->project_name . ' untuk lead ID: ' . $lead->id,
        ]);

        return redirect()->route('marketing.leads.show', $lead->id)->with('success', 'Handoff berhasil! Proyek dan tiket implementasi modul telah dibuat.');
    }
}
