<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MasterWbsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard.jadwal');
})->name('antrian.index');

Route::middleware('auth')->group(function () {
    // 1. Meeting & Agenda view-only routes (accessible by everyone, including marketing)
    Route::get('/dashboard-agenda', [AntrianController::class, 'getDataAgenda'])->name('dashboard.agenda');
    Route::get('/meeting-detail/{meeting_result_id}', [AntrianController::class, 'getMeetingDetail'])->name('meeting.detail');
    Route::get('/agenda-timeline/{id}', [AntrianController::class, 'getAgendaTimeline'])->name('agenda.timeline');

    // 2. Existing operational routes (restricted from marketing)
    Route::middleware('role:admin,manager,developer,pic_request')->group(function () {
        Route::get('/antrian/jumlah', [AntrianController::class, 'jumlah'])->name('antrian.jumlah');
        Route::get('/delete-antrian/{id}', [AntrianController::class, 'destroy']);
        Route::get('/dashboardjadwal/jadwal', [AntrianController::class, 'jadwal'])->name('dashboard.jadwal');
        Route::get('/dashboard-developer', [AntrianController::class, 'dashboardDev'])->name('dashboard.dev');
        Route::get('/dashboard-picrequest', [AntrianController::class, 'dashboardPicReq'])->name('dashboard.picreq');
        Route::get('/dashboar-requestserver', [AntrianController::class, 'getDataReqServer'])->name('dashboard.reqserver');
        Route::get('/dashboard-daily', [AntrianController::class, 'getDataDaily2'])->name('dashboard.daily');
        Route::get('/dashboard-weekly', [AntrianController::class, 'getDataWeekly'])->name('dashboard.weekly');

        Route::post('/update-statusdev/{id}', [AntrianController::class, 'updateStatus'])->name('update.statusdev');
        Route::post('/dashboardjadwal/jadwal', [AntrianController::class, 'postJadwal'])->name('dashboard.jadwalpost');
        Route::post('/antrian', [AntrianController::class, 'store']);
        Route::post('/update-statuspicreq/{id}', [AntrianController::class, 'updateStatusPicReq'])->name('update.statuspicreq');
        Route::post('/update-statusserver/{id}', [AntrianController::class, 'updateStatusServer'])->name('update.statusserv');
        Route::post('/upload-pdf/{id}', [AntrianController::class, 'uploadPdf'])->name('upload.pdf');
        Route::post('/save-dashboard-agenda', [AntrianController::class, 'postAgenda'])->name('dashboard.agendas');
        Route::post('/update-statusagenda/{id}', [AntrianController::class, 'updateAgenda'])->name('update.agenda');
        Route::delete('/delete-agenda/{id}', [AntrianController::class, 'deleteAgenda'])->name('agenda.delete');
        Route::post('/save-dailyreport', [AntrianController::class, 'saveDaily'])->name('save.daily');
        Route::post('/save-weeklyreport', [AntrianController::class, 'saveWeekly'])->name('save.weekly');

        // Meeting Management Write Routes
        Route::post('/save-meeting-notes/{meeting_result_id}', [AntrianController::class, 'saveMeetingNotes'])->name('meeting.notes.save');
        Route::post('/meeting-result/{meeting_result_id}/upload-audio', [AntrianController::class, 'uploadAudio'])->name('meeting.upload-audio');
        Route::post('/meeting-result/{meeting_result_id}/extract-existing-audio', [AntrianController::class, 'extractExistingAudio'])->name('meeting.extract-existing-audio');
        Route::get('/project-tracker', [AntrianController::class, 'getProjectTracker'])->name('project.tracker');
        Route::post('/save-project', [AntrianController::class, 'saveProject'])->name('project.save');
        Route::post('/update-action-item/{action_item_id}', [AntrianController::class, 'updateActionItem'])->name('action.item.update');
        Route::post('/project-tracker/delegate/{detailId}', [AntrianController::class, 'delegateWonModule'])->name('project.tracker.delegate');
        Route::post('/project-tracker/assign-implementator/{wonId}', [AntrianController::class, 'assignImplementator'])->name('project.tracker.assign_implementator');
        Route::post('/project-tracker/won-detail-checklists/toggle', [AntrianController::class, 'toggleWonDetailChecklist'])->name('project.tracker.checklist.toggle');
        Route::post('/project-tracker/progress/{detailId}', [AntrianController::class, 'updateWonModuleProgress'])->name('project.tracker.progress');

        // Project Activity Routes
        Route::get('/project-activity', [AntrianController::class, 'getProjectActivity'])->name('project.activity');
        Route::post('/save-project-activity', [AntrianController::class, 'saveProjectActivity'])->name('project.activity.save');
        Route::post('/update-project-activity/{id}', [AntrianController::class, 'updateProjectActivity'])->name('project.activity.update');
        Route::get('/delete-project-activity/{id}', [AntrianController::class, 'deleteProjectActivity'])->name('project.activity.delete');
        Route::post('/project-activity/wbs/{wbsId}', [AntrianController::class, 'updateProjectWbs'])->name('project.activity.wbs.update');
        Route::post('/project-activity/wbs-bulk-save/{wonId}', [AntrianController::class, 'bulkSaveProjectWbs'])->name('project.activity.wbs.bulk_save');

        // Jadwal (Timeline Request) Routes
        Route::post('/update-jadwal/{id}', [AntrianController::class, 'updateJadwal'])->name('jadwal.update');
        Route::get('/delete-jadwal/{id}', [AntrianController::class, 'deleteJadwal'])->name('jadwal.delete');

        // Follow-up Meeting Route
        Route::get('/create-followup-meeting/{parent_meeting_result_id}', [AntrianController::class, 'createFollowupMeeting'])->name('meeting.followup');

        // Log Aktivitas Route
        Route::get('/log-aktifitas', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity.log');
    });

    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/wbs-master', [MasterWbsController::class, 'index'])->name('wbs.master');
        Route::post('/wbs-master', [MasterWbsController::class, 'store'])->name('wbs.master.store');
        Route::put('/wbs-master/{id}', [MasterWbsController::class, 'update'])->name('wbs.master.update');
        Route::delete('/wbs-master/{id}', [MasterWbsController::class, 'destroy'])->name('wbs.master.destroy');

        // Master Modul templates routes
        Route::get('/master-modul', [App\Http\Controllers\MasterModulController::class, 'index'])->name('modul.master');
        Route::post('/master-modul/store', [App\Http\Controllers\MasterModulController::class, 'storeModul'])->name('modul.master.store');
        Route::post('/master-modul/update/{id}', [App\Http\Controllers\MasterModulController::class, 'updateModul'])->name('modul.master.update');
        Route::get('/master-modul/delete/{id}', [App\Http\Controllers\MasterModulController::class, 'deleteModul'])->name('modul.master.delete');
        Route::post('/master-modul/detail/store/{modulId}', [App\Http\Controllers\MasterModulController::class, 'storeDetail'])->name('modul.master.detail.store');
        Route::post('/master-modul/detail/update/{id}', [App\Http\Controllers\MasterModulController::class, 'updateDetail'])->name('modul.master.detail.update');
        Route::get('/master-modul/detail/delete/{id}', [App\Http\Controllers\MasterModulController::class, 'deleteDetail'])->name('modul.master.detail.delete');

        // Master SLA Routes
        Route::get('/master-sla', [App\Http\Controllers\SlaController::class, 'index'])->name('sla.index');
        Route::post('/master-sla', [App\Http\Controllers\SlaController::class, 'store'])->name('sla.store');
        Route::put('/master-sla/{id}', [App\Http\Controllers\SlaController::class, 'update'])->name('sla.update');
        Route::delete('/master-sla/{id}', [App\Http\Controllers\SlaController::class, 'destroy'])->name('sla.destroy');

        // SDM Evaluasi Kinerja Routes
        Route::get('/sdm-kinerja', [App\Http\Controllers\SdmKinerjaController::class, 'index'])->name('sdm.index');
        Route::post('/sdm-kinerja/generate', [App\Http\Controllers\SdmKinerjaController::class, 'generate'])->name('sdm.generate');
        Route::post('/sdm-kinerja/approve/{id}', [App\Http\Controllers\SdmKinerjaController::class, 'approve'])->name('sdm.approve');

        // Payroll Routes
        Route::get('/payroll', [App\Http\Controllers\PayrollController::class, 'index'])->name('payroll.index');
        Route::post('/payroll/generate', [App\Http\Controllers\PayrollController::class, 'generate'])->name('payroll.generate');
        Route::post('/payroll/pay/{id}', [App\Http\Controllers\PayrollController::class, 'pay'])->name('payroll.pay');

        // Pegawai Routes
        Route::get('/pegawai', [App\Http\Controllers\PegawaiController::class, 'index'])->name('pegawai.index');
        Route::post('/pegawai', [App\Http\Controllers\PegawaiController::class, 'store'])->name('pegawai.store');
        Route::put('/pegawai/{id}', [App\Http\Controllers\PegawaiController::class, 'update'])->name('pegawai.update');
        Route::delete('/pegawai/{id}', [App\Http\Controllers\PegawaiController::class, 'destroy'])->name('pegawai.destroy');
    });

    // 3. Marketing Pipeline Routes (accessible by admin, manager, marketing)
    Route::middleware('role:admin,manager,marketing')->group(function () {
        Route::get('/marketing', [App\Http\Controllers\MarketingController::class, 'dashboard'])->name('marketing.dashboard');
        Route::get('/marketing/leads/create', [App\Http\Controllers\MarketingController::class, 'createLead'])->name('marketing.leads.create');
        Route::post('/marketing/leads', [App\Http\Controllers\MarketingController::class, 'storeLead'])->name('marketing.leads.store');
        Route::get('/marketing/leads/{id}', [App\Http\Controllers\MarketingController::class, 'showLead'])->name('marketing.leads.show');
        Route::get('/marketing/leads/{id}/edit', [App\Http\Controllers\MarketingController::class, 'editLead'])->name('marketing.leads.edit');
        Route::post('/marketing/leads/{id}', [App\Http\Controllers\MarketingController::class, 'updateLead'])->name('marketing.leads.update');
        Route::delete('/marketing/leads/{id}', [App\Http\Controllers\MarketingController::class, 'destroyLead'])->name('marketing.leads.destroy');

        Route::post('/marketing/leads/{leadId}/activity', [App\Http\Controllers\MarketingController::class, 'storeActivity'])->name('marketing.activities.store');
        Route::post('/marketing/leads/{leadId}/proposal', [App\Http\Controllers\MarketingController::class, 'storeProposal'])->name('marketing.proposals.store');
        Route::post('/marketing/proposals/{proposalId}/status', [App\Http\Controllers\MarketingController::class, 'updateProposalStatus'])->name('marketing.proposals.status');

        Route::get('/marketing/leads/{leadId}/handoff', [App\Http\Controllers\MarketingController::class, 'showHandoffForm'])->name('marketing.leads.handoff');
        Route::post('/marketing/leads/{leadId}/handoff', [App\Http\Controllers\MarketingController::class, 'processWonHandoff'])->name('marketing.leads.process_handoff');
    });
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'marketing') {
        return redirect()->route('marketing.dashboard');
    }
    if (in_array(auth()->user()->role, ['admin', 'manager', 'developer', 'pic_request'])) {
        return redirect()->route('dashboard.jadwal');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// -- real time --
// Route::get('/jumlah-reqserver', function () {
//     $jumlah = \App\Models\Jadwal::from('jadwal_t as jt')
//         ->join('status_m as st2', 'st2.id', '=', 'jt.status_server_id')
//         ->whereNotIn('st2.id', [6,10])
//         ->count();

//     return response()->json(['jumlah' => $jumlah]);
// });

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');


require __DIR__.'/auth.php';
