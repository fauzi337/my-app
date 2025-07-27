<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [AntrianController::class, 'index'])->name('antrian.index');
Route::get('/antrian/jumlah', [AntrianController::class, 'jumlah'])->name('antrian.jumlah');
Route::get('/delete-antrian/{id}', [AntrianController::class, 'destroy']);
Route::get('/dashboardjadwal/jadwal', [AntrianController::class, 'jadwal'])->name('dashboard.jadwal');
Route::get('/dashboard-developer', [AntrianController::class, 'dashboardDev'])->name('dashboard.dev');
Route::get('/dashboard-picrequest', [AntrianController::class, 'dashboardPicReq'])->name('dashboard.picreq');
Route::get('/dashboar-requestserver', [AntrianController::class, 'getDataReqServer'])->name('dashboard.reqserver');
Route::get('/dashboard-agenda', [AntrianController::class, 'getDataAgenda'])->name('dashboard.agenda');
// Route::get('/dashboard-daily', [AntrianController::class, 'getDataDaily'])->name('dashboard.daily');
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
Route::post('/save-dailyreport', [AntrianController::class, 'saveDaily'])->name('save.daily');
Route::post('/save-weeklyreport', [AntrianController::class, 'saveWeekly'])->name('save.weekly');

Route::get('/dashboard', function () {
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
