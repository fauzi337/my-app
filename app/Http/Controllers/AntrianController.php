<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Antrian;
use App\Models\Jadwal;
use App\Models\Pegawai;
use App\Models\Prioritas;
use App\Models\Jenistask;
use App\Models\Site;
use App\Models\Timeline;
use App\Models\Status;
use App\Models\Agenda;
use App\Models\Jam;
use App\Models\Parties;
use App\Models\Unit;
use App\Models\DailyReport;
use App\Models\Activity;
use App\Models\WeeklyReport;
use App\Models\MasterActionCategory;
use App\Models\MasterActionStatus;
use App\Models\MasterPriority;
use App\Models\Project;
use App\Models\AgendaMeeting;
use App\Models\MeetingResult;
use App\Models\MeetingNotes;
use App\Models\ActionItem;
use App\Models\ActionItemProgress;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\ProjectActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class AntrianController extends Controller
{
     public function index()
    {
        $antrians = Antrian::orderBy('created_at')->get();
        return view('antrian.index', compact('antrians'));
        // $orangTua = Pelanggan::where('kategori', 'orang_tua')->get();
        // $anakMuda = Pelanggan::where('kategori', 'anak_muda')->get();
        
        // return view('antrian.index', compact('orangTua', 'anakMuda'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kategori' => 'required|in:orang_tua,anak_muda',
        ]);

        // Hitung nomor antrian untuk kategori tersebut
        $count = Antrian::where('kategori', $request->kategori)->count() + 1;
        $kode = $request->kategori === 'orang_tua' ? 'OT' : 'AM';

        Antrian::create([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'nomor_antrian' => $count,
        ]);

        return redirect('/');
    }

    public function destroy($id)
    {
       
        // Hitung nomor antrian untuk kategori tersebut
         Antrian::where('id', $id)->delete();
       


        return redirect('/');
    }

    public function jumlah()
    {
        $jumlahAntrian = Antrian::count(); // Mengambil jumlah antrian
        return view('jumlah-antrian', ['jumlahAntrian'=>$jumlahAntrian]);
    }

      public function jadwal()
    {
        $todayMonth = Carbon::now()->startOfMonth(); // Awal bulan ini
        // dd($todayMonth);
        $daftarJadwal = Jadwal::from('jadwal_t as jt')
                            ->join('prioritas_m as pr','pr.id','=','jt.prioritas_id')
                            ->join('jenistask_m as js','js.id','=','jt.jenistask_id')
                            ->join('timeline_m as tm','tm.id','=','jt.timeline_id')
                            ->join('site_m as si','si.id','=','jt.site_id')
                            ->join('status_m as st','st.id','=','jt.developer_status_id')
                            ->leftJoin('pegawai_m as pg_dev','pg_dev.id','=','jt.picdeveloper_id')
                            ->where('jt.developer_status_id',1)
                            ->where('jt.statusenabled',true)
                            ->select('jt.id', 'jt.prioritas_id', 'jt.jenistask_id', 'jt.site_id', 'jt.timeline_id', 'jt.picrequest_id', 'jt.picdeveloper_id', 'pr.namaprioritas','js.jenistask','si.namasite','tm.gabung','jt.tgl_masuk','jt.task','jt.tgl_deadline','st.status as devstatus','pg_dev.namapegawai as pic_developer',
                            DB::raw("CONCAT(jt.kd_list, '-', jt.nourut) as kd_list"))
                            ->orderBy('si.namasite')
                            ->orderBy('jt.created_at', 'desc')
                            ->get();

        $pegawai = Pegawai::where('statusenabled', true)
                            ->where('jenispegawai','=','Programmer')
                            ->get(); 
                            
        $prioritas = Prioritas::where('statusenabled', true)->get();
        $jenisTask = Jenistask::where('statusenabled', true)->get();
        $site = Site::where('statusenabled', true)->get();
        $timeline = Timeline::where('statusenabled', true)
                            ->whereMonth('tgl_deadline','>=',$todayMonth)
                            ->orderBy('id')
                            ->get();

        $picReq = Pegawai::where('statusenabled', true)
                            ->where('jenispegawai','<>','Programmer')
                            ->where('kdjenispegawai','<>','RS')
                            ->get(); 

        $statusDev = Status::where('statusenabled', true)
                            ->where('jenisstatus','=','Developer')
                            ->get(); 
        $statusServer = Status::where('statusenabled', true)
                            ->where('jenisstatus','=','Server')
                            ->get(); 
        $statusPicReq = Status::where('statusenabled', true)
                            ->where('jenisstatus','=','Pic Request')
                            ->get(); 
        $statusFinal = Status::where('statusenabled', true)
                            ->where('jenisstatus','=','Final')
                            ->get();
        $user = Auth::user()->pegawai_id;

        $cekLogin = Pegawai::from('pegawai_m as pg')
                            ->join('users as us','us.pegawai_id','=','pg.id')
                            ->where('pg.id',$user)
                            ->select('pg.id','pg.namalengkap')
                            ->get();

        return view('dashboard-jadwal', compact('daftarJadwal','pegawai','prioritas','jenisTask','site','timeline','picReq','statusDev','statusServer','statusPicReq','statusFinal','cekLogin'));
      
    }

    // public function getPegawai()
    // {
    //     $pegawai = Pegawai::where('statusenabled', true)->get(); // Ambil semua data prioritas
    //     return view('dashboard-jadwal', compact('pegawai'));
    // }

    // public function getPrioritas()
    // {
    //     $prioritas = Prioritas::where('statusenabled', true)->get(); // Ambil semua data prioritas
    //     return view('dashboard-jadwal', compact('prioritas'));
    // }

    public function postJadwal(Request $request)
    {
        $today = carbon::now();
        $newid = Jadwal::max('id');
        $newids = Activity::max('id');

        $validated = $request->validate([
            'task_masuk' => 'required|date',
            'task_deadline' => 'required|date|after_or_equal:task_masuk',
            'task' => 'required|string'
        ],[
            'task_masuk.required' => 'Silahkan isi Task Masuk terlebih dahulu !',
            'task.required' => 'Silahkan isi Task terlebih dahulu !',
            'task_deadline.required' => 'Task Deadline tidak boleh lebih besar dari Task Masuk !'
        ]);

        $kdSite = Site::where('statusenabled', true)
                        ->where('id', $request->site)
                        ->value('kdsite');
                        // ->get();
        $count = Jadwal::where('kd_list', $kdSite)->count() + 1;
        // $kode = $kdSite + $count;

        $data = Jadwal::insert([
            'id' => $newid +1,
            'prioritas_id' => $request->prioritas,
            'jenistask_id' => $request->jenistask,
            'site_id' => $request->site,
            'timeline_id' => $request->timeline,
            // 'tgl_masuk' => $request->task_masuk,
            'tgl_masuk' => $validated['task_masuk'],
            'tgl_deadline' => $request->task_deadline,
            'task' => $validated['task'],
            'picrequest_id' => $request->picrequest,
            'picdeveloper_id' => $request->pegawai,
            'tgl_selesai' => $request->tgl_selesai ?? null,
            'developer_status_id' => $request->devst,
            'server_status_id' => $request->servetst,
            'picrequest_status_id' => $request->picreqst,
            'final_status_id' => $request->finalst,
            'statusenabled' => true,
            // 'kd_list' => $kdSite . '-' . $newid+1 ,
            'kd_list' => trim($kdSite),
            'nourut' => $count,
            // 'kd_revisi' => $request->finalst = 20 ? $rev : null
            'created_at' => $today,
            'updated_at' => null
        ]);

        $postActivity = Activity::insert([
            'id' => $newids +1,
            'statusenabled' => true,
            'aktifitas' => $validated['task'],
            'status' => 'Not Yet',
            'kd_list' => trim($kdSite) . '-' . $count,
            'created_at' => $today,
            'jadwal_id' => $newid +1,
        ]);
        

        return redirect()->route('dashboard.jadwal')->with('success', 'Simpan Berhasil !');
    }

     public function dashboardDev()
    {
        $listDev = Jadwal::from('jadwal_t as jt')
                            ->join('prioritas_m as pr','pr.id','=','jt.prioritas_id')
                            ->join('jenistask_m as js','js.id','=','jt.jenistask_id')
                            ->join('timeline_m as tm','tm.id','=','jt.timeline_id')
                            ->join('site_m as si','si.id','=','jt.site_id')
                            ->join('pegawai_m as pg','pg.id','=','jt.picrequest_id')
                            ->join('pegawai_m as pg2','pg2.id','=','jt.picdeveloper_id')
                            ->join('status_m as st','st.id','=','jt.developer_status_id')
                            ->join('status_m as st2','st2.id','=','jt.server_status_id')
                            ->join('status_m as st3','st3.id','=','jt.picrequest_status_id')
                            ->whereNotIn('st.id',[3,5])
                            ->orWhere('st3.id',14)
                            ->select('pr.namaprioritas','js.jenistask','si.namasite','tm.gabung','jt.tgl_masuk','jt.task','jt.tgl_deadline','pg.namapegawai','st.id as devstid','jt.id',
                            DB::raw("CONCAT(pg2.kdjenispegawai, ' - ', pg2.namapegawai) as dev,CONCAT(jt.kd_list, '-', jt.nourut) as kd_list"),
                                    'st.status as devstatus','st2.status as servstatus','st2.id as servstid','st3.id as picreqstid')
                            ->orderBy('si.namasite')
                            ->orderBy('jt.created_at', 'desc')
                            ->get();

        $statusDev = Status::where('statusenabled', true)
                            ->where('jenisstatus','=','Developer')
                            ->where('status','<>','Selesai - Revisi')
                            ->get(); 
        $statusServer = Status::where('statusenabled', true)
                            ->where('jenisstatus','=','Server')
                            ->get(); 
        $statusServer2 = Status::where('statusenabled', true)
                            ->where('jenisstatus','=','Server')
                            ->whereIn('id',[6,8])
                            ->get(); 

        $jumlahReqServer = Jadwal::from('jadwal_t as jt')
                            ->join('status_m as st4','st4.id','=','jt.final_status_id')
                            ->join('status_m as st2','st2.id','=','jt.server_status_id')
                            ->where('jt.statusenabled', true)
                            ->where(function($query) {
                                $query->where('st4.id', 21)
                                      ->orWhere('st2.id', 7);
                            })
                            ->count(); 

        // return response()->json([
        //     'list' => $ja$listDevdwal,
        //     'serverStatuses' => $serverStatuses,
        //     'developerStatuses' => $developerStatuses,
        // ]);


        return view('dashboard-developer', compact('listDev','statusDev','statusServer','jumlahReqServer','statusServer2'));
        // return $this->setStatusCode($result['status'])->respond($result, $transMessage);
    }

    public function dashboardPicReq()
    {
        $listPicReq = Jadwal::from('jadwal_t as jt')
                            ->join('prioritas_m as pr','pr.id','=','jt.prioritas_id')
                            ->join('jenistask_m as js','js.id','=','jt.jenistask_id')
                            ->join('timeline_m as tm','tm.id','=','jt.timeline_id')
                            ->join('site_m as si','si.id','=','jt.site_id')
                            ->join('pegawai_m as pg','pg.id','=','jt.picrequest_id')
                            ->join('pegawai_m as pg2','pg2.id','=','jt.picdeveloper_id')
                            ->join('status_m as st','st.id','=','jt.developer_status_id')
                            ->join('status_m as st2','st2.id','=','jt.server_status_id')
                            ->join('status_m as st3','st3.id','=','jt.picrequest_status_id')
                            ->join('status_m as st4','st4.id','=','jt.final_status_id')
                            ->whereIn('st.id',[3,5])
                            ->whereNotIn('st4.id',[21])
                            ->where('jt.statusenabled',true)
                            ->select('pr.namaprioritas','js.jenistask','si.namasite','tm.gabung','jt.tgl_masuk','jt.task','jt.tgl_deadline','pg.namapegawai','jt.id',
                            DB::raw("CONCAT(pg2.kdjenispegawai, ' - ', pg2.namapegawai) as dev,CONCAT(jt.kd_list, '-', jt.nourut) as kd_list"),
                                    'st.status as devstatus','st2.status as servstatus','st3.status as picreqst','st3.id as picreqstid','st4.id as finalstid','st4.status as finalst','jt.path',
                                    'st.id as devstid')
                            ->orderBy('jt.created_at','desc')
                            ->get();
                            // dd($listPicReq);

        $statusPicReq = Status::where('statusenabled', true)
                            ->where('jenisstatus','=','Pic Request')
                            ->where('status','<>','Pending')
                            ->get(); 
        $statusFinal = Status::where('statusenabled', true)
                            ->where('jenisstatus','=','Final')
                            ->whereNotIn('status',['Pending','Selesai'])
                            ->get(); 

        return view('dashboard-picrequest', compact('listPicReq','statusPicReq','statusFinal'));
    }

   public function updateStatus(Request $request, $id)
    {
        $today = carbon::now();
        $id = intval($request->id); // Ubah ke integer
        $newids = Activity::max('id');

        $updateJadwal = Jadwal::find($id);
        $updateJadwal->developer_status_id = $request->devstid;
        $updateJadwal->server_status_id = $request->servstid;
        $updateJadwal->picrequest_status_id = $request->devstid == 5 ? 13 : 11;
        $updateJadwal->tgl_selesai = $request->devstid == 3 ? $request->tgl_selesai : null;
        $updateJadwal->final_status_id = $request->devstid == 5 ? 18 : 17;
        $updateJadwal->updated_at = $today;
        $updateJadwal->save();

        $getDataDev = Jadwal::from('jadwal_t as jt')
                            ->join('status_m as st', 'st.id', '=', 'jt.developer_status_id')
                            ->join('status_m as st2', 'st2.id', '=', 'jt.server_status_id')
                            ->select('jt.task', 'st.status as devstatus', 'st2.status as servstatus',
                            DB::raw("CONCAT(REPLACE(TRIM(jt.kd_list), ' ', ''), '-', jt.nourut) AS kd_list"))
                            ->where('jt.id', $id)
                            ->first(); // Ambil satu data saja

                        // Pastikan $getDataDev tidak null
                        if ($getDataDev) {
                            $newId = Activity::max('id') ?? 0;

                            $statusText = $getDataDev->task . 
                                        ' Dengan Status = ' . $getDataDev->devstatus . 
                                        ' di Server - ' . $getDataDev->servstatus . 
                                        ' pada tgl ' . 
                                        ($request->devstid == 3 ? $request->tgl_selesai : 'Belum Selesai');

                            Activity::insert([
                                'id' => $newId + 1,
                                'statusenabled' => true,
                                'aktifitas' => $statusText,
                                'kd_list' => $getDataDev->kd_list,
                                'status' => $getDataDev->devstatus,
                                'created_at' => now(),
                                'jadwal_id' => $id,
                            ]);
                        }

        return redirect()->back()->with('success', 'Status berhasil diupdate.');
    }

     public function updateStatusPicReq(Request $request, $id)
    {
        $today = carbon::now();
        $todayDate = date('Y-m-d');
        $id = intval($request->id); // Pastikan ID berupa angka

        $updatePicReqSt = Jadwal::find($id);
        $updatePicReqSt->picrequest_status_id = $request->picreqstid;
        if ($request->picreqstid == 14) {
            $updatePicReqSt->developer_status_id = 2;
        }
        $updatePicReqSt->final_status_id = $request->finalstid;
        $updatePicReqSt->updated_at = $today;
        $updatePicReqSt->save();

        $getDataDev = Jadwal::from('jadwal_t as jt')
                            ->join('status_m as st', 'st.id', '=', 'jt.picrequest_status_id')
                            ->join('status_m as st2', 'st2.id', '=', 'jt.final_status_id')
                            ->select('jt.task', 'st.status as picreqst', 'st2.status as finalst',
                             DB::raw("CONCAT(REPLACE(TRIM(jt.kd_list), ' ', ''), '-', jt.nourut) AS kd_list"))
                            ->where('jt.id', $id)
                            ->first(); // Ambil satu data saja

                        // Pastikan $getDataDev tidak null
                        if ($getDataDev) {
                            $newId = Activity::max('id') ?? 0;

                            $statusText = $getDataDev->task . 
                                        ' Dengan Status = ' . $getDataDev->picreqst . 
                                        ' dan Status Akhir - ' . $getDataDev->finalst . 
                                        ' pada tgl ' . $todayDate;

                            Activity::insert([
                                'id' => $newId + 1,
                                'statusenabled' => true,
                                'aktifitas' => $statusText,
                                'kd_list' => $getDataDev->kd_list,
                                'status' => $request->picreqstid == 15 ? $getDataDev->finalst : $getDataDev->picreqst,
                                'created_at' => $today,
                                'jadwal_id' => $id,
                            ]);
                        }

        return redirect()->back()->with('success', 'Status berhasil diupdate.');
    }

     public function getDataReqServer()
    {
        $listReqServ = Jadwal::from('jadwal_t as jt')
                            ->join('prioritas_m as pr','pr.id','=','jt.prioritas_id')
                            ->join('jenistask_m as js','js.id','=','jt.jenistask_id')
                            ->join('timeline_m as tm','tm.id','=','jt.timeline_id')
                            ->join('site_m as si','si.id','=','jt.site_id')
                            ->join('pegawai_m as pg','pg.id','=','jt.picrequest_id')
                            ->join('pegawai_m as pg2','pg2.id','=','jt.picdeveloper_id')
                            ->join('status_m as st4','st4.id','=','jt.final_status_id')
                            ->join('status_m as st2','st2.id','=','jt.server_status_id')
                            ->where('jt.statusenabled', true)
                            ->where(function($query) {
                                $query->where('st4.id', 21)
                                      ->orWhere('st2.id', 7);
                            })
                            ->select('pr.namaprioritas','js.jenistask','si.namasite','tm.gabung','jt.tgl_masuk','jt.task','jt.tgl_deadline','pg.namapegawai','st4.id as finalstid','st4.status as finalst','jt.id',
                                    'st2.id as servstid','st2.status as servstatus',
                            DB::raw("CONCAT(pg2.kdjenispegawai, ' - ', pg2.namapegawai) as dev,CONCAT(jt.kd_list, '-', jt.nourut) as kd_list"))
                            ->orderBy('jt.prioritas_id','desc')
                            ->get();
                            
        $statusServer = Status::where('statusenabled', true)
                            ->where('jenisstatus','=','Server')
                            ->get(); 

        // return response()->json([
        //     'list' => $ja$listDevdwal,
        //     'serverStatuses' => $serverStatuses,
        //     'developerStatuses' => $developerStatuses,
        // ]);


        return view('dashboard-requestserver', compact('listReqServ','statusServer'));
    }

     public function updateStatusServer(Request $request, $id)
    {
        $today = carbon::now();
        $id = intval($request->id); // Pastikan ID berupa angka
        $todayDate = date('Y-m-d');

        $updateServer = Jadwal::find($id);
        $updateServer->server_status_id = $request->servstid;
        $updateServer->final_status_id = $request->servstid == 10 ? 22 : 21;
        $updateServer->updated_at = $today;
        $updateServer->save();

        $getDataDev = Jadwal::from('jadwal_t as jt')
                            ->join('status_m as st', 'st.id', '=', 'jt.server_status_id')
                            ->join('status_m as st2', 'st2.id', '=', 'jt.final_status_id')
                            ->select('jt.task', 'st.status as servst','st2.status as finalst',
                            DB::raw("CONCAT(REPLACE(TRIM(jt.kd_list), ' ', ''), '-', jt.nourut) AS kd_list"))
                            ->where('jt.id', $id)
                            ->first(); // Ambil satu data saja

                        // Pastikan $getDataDev tidak null
                        if ($getDataDev) {
                            $newId = Activity::max('id') ?? 0;

                            $statusText = $getDataDev->task . 
                                        ' Dengan Status Server = ' . $getDataDev->servst . 
                                        ' pada tgl ' . $todayDate;

                            Activity::insert([
                                'id' => $newId + 1,
                                'statusenabled' => true,
                                'aktifitas' => $statusText,
                                'kd_list' => $getDataDev->kd_list,
                                'status' => $getDataDev->finalst,
                                'created_at' => $today,
                                'jadwal_id' => $id,
                            ]);
                        }

        return redirect()->back()->with('success', 'Status berhasil diupdate.');
    }

    public function uploadPdf(Request $request, $id)
    {
        $today = carbon::now();
        $todayDate = date('Y-m-d');
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('pdf', $filename); // Simpan file ke storage/app/public/pdf

            $uploadFile = Jadwal::find($id);

            if (!$uploadFile) {
                return back()->with('error', 'Data jadwal tidak ditemukan.');
            }

            $uploadFile->uat = $filename;
            $uploadFile->path = 'pdf/' . $filename;
            $uploadFile->final_status_id = 19;
            $uploadFile->updated_at = $today;
            $uploadFile->save();

            $getDataDev = Jadwal::from('jadwal_t as jt')
                            ->join('status_m as st', 'st.id', '=', 'jt.final_status_id')
                            ->select('jt.task', 'st.status as finalst','jt.uat',
                            DB::raw("CONCAT(REPLACE(TRIM(jt.kd_list), ' ', ''), '-', jt.nourut) AS kd_list"))
                            ->where('jt.id', $id)
                            ->first(); // Ambil satu data saja

                        // Pastikan $getDataDev tidak null
                        if ($getDataDev) {
                            $newId = Activity::max('id') ?? 0;

                            $statusText = $getDataDev->task . 
                                        ' Dengan Status Akhir = ' . $getDataDev->finalst . 
                                        ' pada File = ' . $getDataDev->uat . 
                                        ' pada tgl ' . $todayDate;

                            Activity::insert([
                                'id' => $newId + 1,
                                'statusenabled' => true,
                                'aktifitas' => $statusText,
                                'kd_list' => $getDataDev->kd_list,
                                'status' => $getDataDev->finalst,
                                'created_at' => $today,
                                'jadwal_id' => $id,
                            ]);
                        }

            return back()->with('success', 'PDF berhasil diupload');
        }

        return back()->with('error', 'Tidak ada file yang dipilih');
    }

     public function getDataAgenda()
     {
         $listAgenda = AgendaMeeting::from('agenda_meeting as ag')
                             ->join('site_m as si','si.id','=','ag.site_id')
                             ->join('pegawai_m as pg','pg.id','=','ag.picintern_id')
                             ->join('pegawai_m as pg2','pg2.id','=','ag.picextern_id')
                             ->join('status_m as st','st.id','=','ag.status_id')
                             ->join('parties_m as pt','pt.id','=','ag.parties_id')
                             ->join('jam_m as jm','jm.id','=','ag.jam_id')
                             ->join('unit_m as un','un.id','=','ag.unit_id')
                             ->where('ag.statusenabled', true)
                             ->select('ag.id','si.namasite','ag.tgl_jadwal','ag.kegiatan','pg.namapegawai as picintern','pg2.namapegawai as picextern','st.status','pt.parties',
                             'jm.jam','un.unit','st.id as stid',
                             DB::raw("CONCAT(ag.kd_list, '-', ag.nourut) as kd_list"))
                             ->orderBy('ag.id','desc')
                             ->get();

          $listMeetingResults = MeetingResult::from('meeting_result as mr')
                              ->join('agenda_meeting as ag', 'ag.id', '=', 'mr.agenda_meeting_id')
                              ->join('site_m as si', 'si.id', '=', 'ag.site_id')
                              ->join('parties_m as pt', 'pt.id', '=', 'ag.parties_id')
                              ->leftJoin('project as pr', 'pr.id', '=', 'mr.project_id')
                              ->where('mr.statusenabled', true)
                              ->select('mr.id', 'mr.meeting_code', 'mr.agenda_meeting_id', 'si.namasite', 'ag.tgl_jadwal', 'ag.kegiatan', 'pt.parties', 'mr.status', 'mr.notes', 'pr.project_name', 'mr.project_id', 'ag.unit_id')
                              ->orderBy('mr.id', 'desc')
                              ->get();

          // Determine the latest meeting result and its status for each agenda
          $latestMeetingResults = MeetingResult::where('statusenabled', true)
              ->select('agenda_meeting_id', DB::raw('MAX(id) as max_id'))
              ->groupBy('agenda_meeting_id')
              ->pluck('max_id', 'agenda_meeting_id')
              ->toArray();

          $latestStatuses = MeetingResult::whereIn('id', array_values($latestMeetingResults))
              ->pluck('status', 'id')
              ->toArray();

          foreach ($listMeetingResults as $mr) {
              $maxId = $latestMeetingResults[$mr->agenda_meeting_id] ?? null;
              $isLatest = ($mr->id === $maxId);
              $latestStatus = trim($latestStatuses[$maxId] ?? '');

              // Show plus button only if this is the latest meeting result for this agenda,
              // and its status is 'On Going'
              $mr->show_plus_button = ($isLatest && $latestStatus === 'On Going');
          }
                             
         $statusAgenda = Status::where('statusenabled', true)
                             ->where('jenisstatus','=','Agenda')
                             ->where('id','<>',23)
                             ->orderBy('id','desc')
                             ->get(); 

         $site = Site::where('statusenabled', true)
                             ->get(); 

         $jam = Jam::where('statusenabled', true)
                             ->get(); 
                             
         $parties = Parties::where('statusenabled', true)
                             ->get();      

         $unit = Unit::where('statusenabled', true)
                             ->get(); 

         $picInternal = Pegawai::where('statusenabled', true)
                             ->where('parties_id',6)
                             ->whereIn('jenispegawai',['Operator','Implementator'])
                             ->get(); 

         $picExternal = Pegawai::where('statusenabled', true)
                             ->where('parties_id',3)
                             ->get(); 

         // Master data for Action Items modal
         $actionCategories = MasterActionCategory::where('statusenabled', true)->get();
         $actionStatuses = MasterActionStatus::where('statusenabled', true)->get();
         $priorities = MasterPriority::where('statusenabled', true)->get();
         $units = Unit::where('statusenabled', true)->get();
         $users = User::from('users as us')
                      ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'us.pegawai_id')
                      ->select(
                          'us.id',
                          DB::raw("CASE WHEN pg.namapegawai IS NOT NULL THEN TRIM(pg.namapegawai) || COALESCE(' (' || TRIM(pg.jenispegawai) || ')', '') ELSE us.name END as name")
                      )
                      ->where('us.statusenabled', true)
                      ->get();
         $projects = Project::where('statusenabled', true)->get();

         return view('dashboard-agenda', compact(
             'listAgenda',
             'listMeetingResults',
             'site',
             'jam',
             'parties',
             'unit',
             'picInternal',
             'picExternal',
             'statusAgenda',
             'actionCategories',
             'actionStatuses',
             'priorities',
             'units',
             'users',
             'projects'
         ));
     }

     public function postAgenda(Request $request)
     {
         $today = Carbon::now();
         $newid = AgendaMeeting::max('id') ?? 0;

         $validated = $request->validate([
             'tgl_jadwal' => 'required|date',
             'kegiatan' => 'required|string',
             'site' => 'required|exists:site_m,id',
             'jam' => 'required|exists:jam_m,id',
             'parties' => 'required|exists:parties_m,id',
             'unit' => 'required|exists:unit_m,id',
             'pic_internal' => 'required|exists:pegawai_m,id',
             'pic_external' => 'required|exists:pegawai_m,id',
         ],[
             'tgl_jadwal.required' => 'Penjadwalan Wajib di Isi !',
             'kegiatan.required' => 'Kegiatan Wajib di Isi !',
             'site.required' => 'Site Wajib di Isi !',
             'jam.required' => 'Jam Wajib di Isi !',
             'parties.required' => 'Pihak Wajib di Isi !',
             'unit.required' => 'Unit Wajib di Isi !',
             'pic_internal.required' => 'PIC Internal Wajib di Isi !',
             'pic_external.required' => 'PIC Eksternal Wajib di Isi !',
         ]);

         $kdSite = Site::where('statusenabled', true)
                         ->where('id', $request->site)
                         ->value('kdsite');

         $count = AgendaMeeting::where('kd_list', $kdSite)->count() + 1;

         $saveAgenda = new AgendaMeeting();
         $saveAgenda->id = $newid + 1;
         $saveAgenda->kegiatan = $validated['kegiatan'];
         $saveAgenda->tgl_jadwal = $validated['tgl_jadwal'];
         $saveAgenda->site_id = $validated['site'];
         $saveAgenda->jam_id = $validated['jam'];
         $saveAgenda->status_id = 23; // Scheduled status id
         $saveAgenda->parties_id = $validated['parties'];
         $saveAgenda->unit_id = $validated['unit'];
         $saveAgenda->picintern_id = $validated['pic_internal'];
         $saveAgenda->picextern_id = $validated['pic_external'];
         $saveAgenda->statusenabled = true;
         $saveAgenda->kd_list = $kdSite;
         $saveAgenda->nourut = $count;
         $saveAgenda->created_at = $today;
         $saveAgenda->updated_at = null;
         $saveAgenda->save();

         // Automatically reset pgsql sequence just in case
         if (DB::getDriverName() === 'pgsql') {
             DB::statement("SELECT setval('agenda_meeting_id_seq', (SELECT MAX(id) FROM agenda_meeting))");
         }

         // Automatically create a record in the meeting_result table
         $meetCode = 'MEET-' . trim($kdSite) . '-' . $count;
         $meetResult = new MeetingResult();
         $meetResult->meeting_code = $meetCode;
         $meetResult->agenda_meeting_id = $saveAgenda->id;
         $meetResult->status = 'Pending';
         $meetResult->statusenabled = true;
         $meetResult->save();

         // Audit logging
         AuditLog::create([
             'user_id' => Auth::id(),
             'activity' => 'Meeting dibuat',
             'details' => 'Membuat agenda meeting: ' . $saveAgenda->kegiatan . ' (Kode: ' . $meetCode . ')'
         ]);

         // Trigger activity log for the legacy system
         $getDataAgenda = AgendaMeeting::from('agenda_meeting as ag')
                             ->join('status_m as st', 'st.id', '=', 'ag.status_id')
                             ->join('unit_m as ut', 'ut.id', '=', 'ag.unit_id')
                             ->join('pegawai_m as pg', 'pg.id', '=', 'ag.picextern_id')
                             ->select('ag.kegiatan', 'st.status','ut.unit','pg.namapegawai','st.id as stid')
                             ->where('ag.id', $saveAgenda->id)
                             ->first();

         if ($getDataAgenda) {
             $newIds = Activity::max('id') ?? 0;
             $statusText = $getDataAgenda->kegiatan . 
                         ' Dengan Status = ' . $getDataAgenda->status . 
                         ' di unit - ' . $getDataAgenda->unit . 
                         ' dengan PIC ' . $getDataAgenda->namapegawai . 
                         ' pada tgl ' . $validated['tgl_jadwal'];

             Activity::insert([
                 'id' => $newIds + 1,
                 'statusenabled' => true,
                 'aktifitas' => $statusText,
                 'kd_list' => $kdSite . '-' . $count,
                 'status' => $getDataAgenda->status,
                 'created_at' => $today,
                 'agenda_id' => $saveAgenda->id,
             ]);
         }

         return redirect()->route('dashboard.agenda')->with('success', 'Simpan Berhasil !');
     }

     public function updateAgenda(Request $request, $id)
     {
         $today = Carbon::now();
         $id = intval($request->id);

         $updateJadwal = AgendaMeeting::find($id);
         $updateJadwal->status_id = $request->statusid;
         $updateJadwal->tgl_realisasi = $request->tgl_selesai;
         $updateJadwal->updated_at = $today;
         $updateJadwal->save();

         // Update meeting result if it exists
         $meetResult = MeetingResult::where('agenda_meeting_id', $id)->first();
         if ($meetResult) {
             $statusMap = 'Pending';
             $statusIdVal = intval($request->statusid);
             if ($statusIdVal == 24) {
                 $statusMap = 'Cancel';
             } elseif ($statusIdVal == 25) {
                 $statusMap = 'Done';
             }
             $meetResult->status = $statusMap;
             $meetResult->tgl_realisasi = $request->tgl_selesai;
             $meetResult->save();
         }

         // Audit logging
         AuditLog::create([
             'user_id' => Auth::id(),
             'activity' => 'Meeting diubah',
             'details' => 'Memperbarui status agenda meeting ID: ' . $id
         ]);

         $getDataAgenda = AgendaMeeting::from('agenda_meeting as ag')
                             ->join('status_m as st', 'st.id', '=', 'ag.status_id')
                             ->join('unit_m as ut', 'ut.id', '=', 'ag.unit_id')
                             ->join('pegawai_m as pg', 'pg.id', '=', 'ag.picextern_id')
                             ->select('ag.kegiatan', 'st.status','ut.unit','pg.namapegawai','st.id as stid',
                             DB::raw("CONCAT(REPLACE(TRIM(ag.kd_list), ' ', ''), '-', ag.nourut) AS kd_list"))
                             ->where('ag.id', $id)
                             ->first();

         if ($getDataAgenda) {
             $newIds = Activity::max('id') ?? 0;
             $statusText = $getDataAgenda->kegiatan . 
                         ' Dengan Status = ' . $getDataAgenda->status . 
                         ' di unit - ' . $getDataAgenda->unit . 
                         ' dengan PIC ' . $getDataAgenda->namapegawai . 
                         ' pada tgl ' . $request->tgl_selesai;

             Activity::insert([
                 'id' => $newIds + 1,
                 'statusenabled' => true,
                 'aktifitas' => $statusText,
                 'kd_list' => $getDataAgenda->kd_list,
                 'status' => $getDataAgenda->status,
                 'created_at' => $today,
                 'agenda_id' => $id,
             ]);
         }

         return redirect()->back()->with('success', 'Status berhasil diupdate.');
     }

     public function deleteAgenda($id)
     {
         if (Auth::user()->pegawai_id === null || (int) Auth::user()->pegawai_id !== 0) {
             abort(403, 'Unauthorized action.');
         }

         $agenda = AgendaMeeting::findOrFail($id);
         $agenda->delete();

         AuditLog::create([
             'user_id' => Auth::id(),
             'activity' => 'Agenda Meeting dihapus',
             'details' => 'Menghapus agenda meeting ID: ' . $id
         ]);

         return redirect()->back()->with('success', 'Agenda Meeting berhasil dihapus!');
     }

     public function saveMeetingNotes(Request $request, $meeting_result_id)
     {
         $today = Carbon::now();
         $meetingResult = MeetingResult::findOrFail($meeting_result_id);

         $request->validate([
             'tgl_realisasi' => 'required|date',
             'notulen' => 'required|string',
             'project_id' => 'nullable|exists:project,id',
             'action_items' => 'nullable|array',
             'action_items.*.description' => 'required|string',
             'action_items.*.category_id' => 'required|exists:master_action_category,id',
             'action_items.*.unit_id' => 'required|exists:unit_m,id',
             'action_items.*.pic_person_id' => 'required|exists:users,id',
             'action_items.*.priority_id' => 'required|exists:master_priority,id',
             'action_items.*.target_date' => 'required|date',
         ]);

         // 1. Update meeting result status
         $hasActionItems = $request->action_items && count($request->action_items) > 0;
         $meetingResult->status = $hasActionItems ? 'On Going' : 'Done';
         $meetingResult->notes = $request->notulen;
         $meetingResult->tgl_realisasi = $request->tgl_realisasi;
         $meetingResult->project_id = $request->project_id;
         $meetingResult->save();

         // Update parent agenda status to Done (25)
         AgendaMeeting::where('id', $meetingResult->agenda_meeting_id)->update(['status_id' => 25]);

         // 2. Insert into meeting_notes
         MeetingNotes::create([
             'meeting_result_id' => $meeting_result_id,
             'notulen' => $request->notulen,
             'tgl_realisasi' => $request->tgl_realisasi,
             'statusenabled' => true
         ]);

         // 3. Save Action Items
         $openStatusId = MasterActionStatus::where('name', 'Open')->value('id');
         if ($request->action_items) {
             foreach ($request->action_items as $item) {
                 $actionItem = ActionItem::create([
                     'meeting_result_id' => $meeting_result_id,
                     'project_id' => $request->project_id,
                     'description' => $item['description'],
                     'category_id' => $item['category_id'],
                     'unit_id' => $item['unit_id'],
                     'pic_person_id' => $item['pic_person_id'],
                     'priority_id' => $item['priority_id'],
                     'target_date' => $item['target_date'],
                     'status_id' => $openStatusId,
                     'statusenabled' => true
                 ]);

                 // Auto-dispatch based on category:
                 // Non Develop -> project_activity
                 // Develop -> timeline request (jadwal_t & activity_t)
                 $category = MasterActionCategory::find($item['category_id']);
                 $categoryName = $category ? trim(strtolower($category->name)) : '';

                 if ($categoryName === 'non develop') {
                     // Get priority name from master_priority
                     $priorityObj = MasterPriority::find($item['priority_id']);
                     $priorityName = $priorityObj ? $priorityObj->name : 'Low';
                     $prioritasMId = Prioritas::where('namaprioritas', 'ilike', '%' . trim($priorityName) . '%')->value('id') 
                                     ?? Prioritas::where('statusenabled', true)->value('id');

                     // Resolve Pegawai ID for PIC
                     $userObj = User::find($item['pic_person_id']);
                     $picId = ($userObj && $userObj->pegawai_id) ? $userObj->pegawai_id : Pegawai::where('statusenabled', true)->value('id');

                     $paMaxId = ProjectActivity::max('id') ?? 0;
                     ProjectActivity::create([
                         'id' => $paMaxId + 1,
                         'prioritas_id' => $prioritasMId,
                         'site_id' => $meetingResult->agendaMeeting->site_id,
                         'pic_id' => $picId,
                         'tgl_masuk' => $request->tgl_realisasi ?? date('Y-m-d'),
                         'tgl_deadline' => $item['target_date'],
                         'task' => $item['description'],
                         'statusenabled' => true
                     ]);

                     if (DB::getDriverName() === 'pgsql') {
                         DB::statement("SELECT setval('project_activity_id_seq', (SELECT MAX(id) FROM project_activity))");
                     }
                 } elseif ($categoryName === 'develop') {
                     // Get priority
                     $priorityObj = MasterPriority::find($item['priority_id']);
                     $priorityName = $priorityObj ? $priorityObj->name : 'Low';
                     $prioritasMId = Prioritas::where('namaprioritas', 'ilike', '%' . trim($priorityName) . '%')->value('id') 
                                     ?? Prioritas::where('statusenabled', true)->value('id');

                     // Resolve Pegawai IDs
                     $userObj = User::find($item['pic_person_id']);
                     $picDevId = ($userObj && $userObj->pegawai_id) ? $userObj->pegawai_id : Pegawai::where('statusenabled', true)->value('id');
                     $picReqId = $meetingResult->agendaMeeting->picintern_id ?? Pegawai::where('statusenabled', true)->value('id');

                     // Resolve Site code and increment sequence for nourut
                     $kdSite = Site::where('id', $meetingResult->agendaMeeting->site_id)->value('kdsite');
                     $kdSiteClean = trim($kdSite);
                     $count = Jadwal::where('kd_list', $kdSiteClean)->count() + 1;

                     // Resolve timeline_id based on target date
                     $timelineId = Timeline::where('tgl_deadline', '>=', $item['target_date'])
                         ->where('statusenabled', true)
                         ->orderBy('tgl_deadline', 'asc')
                         ->value('id');

                     // Default timeline_id if none matched
                     if (!$timelineId) {
                         $timelineId = Timeline::where('statusenabled', true)->orderBy('tgl_deadline', 'desc')->value('id');
                     }

                     $newJadwalId = Jadwal::max('id') ?? 0;
                     Jadwal::insert([
                         'id' => $newJadwalId + 1,
                         'prioritas_id' => $prioritasMId,
                         'jenistask_id' => 7, // Request type
                         'site_id' => $meetingResult->agendaMeeting->site_id,
                         'timeline_id' => $timelineId,
                         'tgl_masuk' => $request->tgl_realisasi ?? date('Y-m-d'),
                         'tgl_deadline' => $item['target_date'],
                         'task' => $item['description'],
                         'picrequest_id' => $picReqId,
                         'picdeveloper_id' => null,
                         'developer_status_id' => 1, // Not Yet
                         'server_status_id' => 6, // Not Yet
                         'picrequest_status_id' => 11, // Not Yet
                         'final_status_id' => 17, // Not Yet
                         'statusenabled' => true,
                         'kd_list' => $kdSiteClean,
                         'nourut' => $count,
                         'created_at' => now(),
                         'updated_at' => null
                     ]);

                     // Insert into Activity
                     $newActivityId = Activity::max('id') ?? 0;
                     Activity::insert([
                         'id' => $newActivityId + 1,
                         'statusenabled' => true,
                         'aktifitas' => $item['description'],
                         'status' => 'Not Yet',
                         'kd_list' => $kdSiteClean . '-' . $count,
                         'jadwal_id' => $newJadwalId + 1,
                         'created_at' => now(),
                         'updated_at' => null
                     ]);
                 }

                 // Audit log for action item
                 AuditLog::create([
                     'user_id' => Auth::id(),
                     'activity' => 'Action Item dibuat',
                     'details' => 'Membuat action item: "' . substr($actionItem->description, 0, 50) . '..." untuk meeting ' . $meetingResult->meeting_code
                 ]);
             }
         }

         // 4. Update project progress
         if ($request->project_id) {
             $projectId = $request->project_id;
             $totalItems = ActionItem::where('project_id', $projectId)->where('statusenabled', true)->count();
             $doneStatusId = MasterActionStatus::where('name', 'Done')->value('id');
             $doneItems = ActionItem::where('project_id', $projectId)->where('status_id', $doneStatusId)->where('statusenabled', true)->count();
             $progress = $totalItems > 0 ? round(($doneItems / $totalItems) * 100, 2) : 0;
             Project::where('id', $projectId)->update(['progress' => $progress]);
         }

         // Audit log for meeting
         AuditLog::create([
             'user_id' => Auth::id(),
             'activity' => 'Meeting selesai',
             'details' => 'Mengisi notulen dan action items untuk meeting ' . $meetingResult->meeting_code
         ]);

         return redirect()->route('dashboard.agenda')->with('success', 'Hasil meeting berhasil disimpan!');
     }

      public function getMeetingDetail($meeting_result_id)
      {
          $meeting = MeetingResult::with([
              'agendaMeeting.site',
              'agendaMeeting.jam',
              'agendaMeeting.parties',
              'agendaMeeting.unit',
              'agendaMeeting.picInternal',
              'agendaMeeting.picExternal',
              'project',
              'actionItems.category',
              'actionItems.unit',
              'actionItems.picPerson',
              'actionItems.priority',
              'actionItems.status',
              'actionItems.progressUpdates.creator'
          ])->findOrFail($meeting_result_id);

          // Resolve live status directly from source tables
          foreach ($meeting->actionItems as $item) {
              $liveStatus = 'Open'; // Default fallback
              $categoryName = $item->category ? trim(strtolower($item->category->name)) : '';

              if ($categoryName === 'non develop') {
                  $userObj = User::find($item->pic_person_id);
                  $picId = ($userObj && $userObj->pegawai_id) ? $userObj->pegawai_id : null;

                  $paQuery = ProjectActivity::where('site_id', $meeting->agendaMeeting->site_id)
                      ->where('task', $item->description)
                      ->where('tgl_deadline', $item->target_date)
                      ->where('statusenabled', true);
                  if ($picId) {
                      $paQuery->where('pic_id', $picId);
                  }
                  $pa = $paQuery->first();
                  if ($pa) {
                      $liveStatus = $pa->status; // 'Open', 'Done', etc.
                  }
              } elseif ($categoryName === 'develop') {
                  $userObj = User::find($item->pic_person_id);
                  $picDevId = ($userObj && $userObj->pegawai_id) ? $userObj->pegawai_id : null;

                  $jadwalQuery = Jadwal::where('site_id', $meeting->agendaMeeting->site_id)
                      ->where('task', $item->description)
                      ->where('tgl_deadline', $item->target_date)
                      ->where('statusenabled', true);
                  if ($picDevId) {
                      $jadwalQuery->where(function($q) use ($picDevId) {
                          $q->where('picdeveloper_id', $picDevId)
                            ->orWhereNull('picdeveloper_id');
                      });
                  }
                  $jadwal = $jadwalQuery->first();
                  if ($jadwal) {
                      $finalStatusName = DB::table('status_m')->where('id', $jadwal->final_status_id)->value('status');
                      if ($finalStatusName) {
                          $liveStatus = trim($finalStatusName);
                      }
                  }
              }
              $item->live_status = $liveStatus;
          }

          return view('detail-meeting', compact('meeting'));
      }

      public function getAgendaTimeline($id)
      {
          $meetingResults = MeetingResult::where('agenda_meeting_id', $id)
              ->where('statusenabled', true)
              ->orderBy('id', 'asc')
              ->get();

          $formatted = $meetingResults->map(function ($mr) {
              return [
                  'meeting_code' => $mr->meeting_code,
                  'status' => trim($mr->status),
                  'tgl_realisasi' => $mr->tgl_realisasi ? \Carbon\Carbon::parse($mr->tgl_realisasi)->format('Y-m-d') : 'Belum Realisasi',
                  'notes' => $mr->notes ?? 'Belum ada notulen rapat.'
              ];
          });

          return response()->json([
              'success' => true,
              'timeline' => $formatted
          ]);
      }

    public function getProjectTracker(Request $request)
    {
        // Fetch all active sites for the filter dropdown
        $sites = Site::where('statusenabled', true)->orderBy('namasite')->get();

        // Get selected site ID (default to first active site if null)
        $selectedSiteId = $request->input('site_id');
        if (!$selectedSiteId && $sites->isNotEmpty()) {
            $selectedSiteId = $sites->first()->id;
        }

        // 1. Query Project Activities
        $projectActivities = ProjectActivity::with(['prioritas', 'pic'])
            ->where('site_id', $selectedSiteId)
            ->where('statusenabled', true)
            ->get();

        // 2. Query Timeline Requests (Jadwal)
        $timelineRequests = Jadwal::from('jadwal_t as jt')
            ->leftJoin('prioritas_m as pr', 'pr.id', '=', 'jt.prioritas_id')
            ->leftJoin('jenistask_m as js', 'js.id', '=', 'jt.jenistask_id')
            ->leftJoin('timeline_m as tm', 'tm.id', '=', 'jt.timeline_id')
            ->leftJoin('pegawai_m as pg_req', 'pg_req.id', '=', 'jt.picrequest_id')
            ->leftJoin('pegawai_m as pg_dev', 'pg_dev.id', '=', 'jt.picdeveloper_id')
            ->leftJoin('status_m as st', 'st.id', '=', 'jt.final_status_id')
            ->where('jt.site_id', $selectedSiteId)
            ->where('jt.statusenabled', true)
            ->select(
                'jt.id',
                'jt.tgl_masuk',
                'jt.tgl_deadline',
                'jt.task',
                'jt.kd_list',
                'jt.nourut',
                'jt.final_status_id',
                'pr.namaprioritas',
                'js.jenistask',
                'tm.gabung as timeline_name',
                'pg_req.namapegawai as pic_requestor',
                'pg_dev.namapegawai as pic_developer',
                'st.status as final_status'
            )
            ->get();

        // Calculations
        $totalPA = $projectActivities->count();
        $donePA = $projectActivities->where('status', 'Done')->count();
        $undonePA = $totalPA - $donePA;

        $totalTR = $timelineRequests->count();
        $doneTR = $timelineRequests->where('final_status_id', 19)->count();
        $undoneTR = $totalTR - $doneTR;

        $combinedTotal = $totalPA + $totalTR;
        $combinedDone = $donePA + $doneTR;
        $combinedUndone = $undonePA + $undoneTR;

        $donePercentage = $combinedTotal > 0 ? round(($combinedDone / $combinedTotal) * 100, 2) : 0;
        $undonePercentage = $combinedTotal > 0 ? round(($combinedUndone / $combinedTotal) * 100, 2) : 0;

        // Filter out completed tasks for the lists
        $unfinishedPA = $projectActivities->where('status', '<>', 'Done');
        $unfinishedTR = $timelineRequests->where('final_status_id', '<>', 19);

        return view('project-tracker', compact(
            'sites',
            'selectedSiteId',
            'combinedTotal',
            'combinedDone',
            'combinedUndone',
            'donePercentage',
            'undonePercentage',
            'unfinishedPA',
            'unfinishedTR'
        ));
    }

     public function saveProject(Request $request)
     {
         $request->validate([
             'project_code' => 'required|string|unique:project,project_code',
             'project_name' => 'required|string',
             'site_id' => 'required|exists:site_m,id',
             'start_date' => 'nullable|date',
             'target_date' => 'nullable|date|after_or_equal:start_date',
             'status' => 'required|string'
         ]);

         $project = Project::create([
             'project_code' => $request->project_code,
             'project_name' => $request->project_name,
             'site_id' => $request->site_id,
             'description' => $request->description,
             'start_date' => $request->start_date,
             'target_date' => $request->target_date,
             'status' => $request->status,
             'progress' => 0,
             'statusenabled' => true
         ]);

         AuditLog::create([
             'user_id' => Auth::id(),
             'activity' => 'Project dibuat',
             'details' => 'Membuat project: ' . $project->project_name . ' (Kode: ' . $project->project_code . ')'
         ]);

         return redirect()->back()->with('success', 'Project berhasil disimpan!');
     }

     public function updateActionItem(Request $request, $action_item_id)
     {
         $actionItem = ActionItem::findOrFail($action_item_id);

         $request->validate([
             'status_id' => 'required|exists:master_action_status,id',
             'priority_id' => 'required|exists:master_priority,id',
             'pic_person_id' => 'required|exists:users,id',
             'target_date' => 'required|date',
             'progress_notes' => 'nullable|string',
             'attachment' => 'nullable|file|mimes:pdf,jpg,png,jpeg,doc,docx|max:5120'
         ]);

         // Update fields
         $actionItem->status_id = $request->status_id;
         $actionItem->priority_id = $request->priority_id;
         $actionItem->pic_person_id = $request->pic_person_id;
         $actionItem->target_date = $request->target_date;
         $actionItem->save();

         // Save progress updates if notes are supplied
         if ($request->progress_notes) {
             $attachmentPath = null;
             if ($request->hasFile('attachment')) {
                 $file = $request->file('attachment');
                 $filename = time() . '_' . $file->getClientOriginalName();
                 $file->move('attachments', $filename);
                 $attachmentPath = 'attachments/' . $filename;
             }

             ActionItemProgress::create([
                 'action_item_id' => $action_item_id,
                 'progress_date' => now()->format('Y-m-d'),
                 'notes' => $request->progress_notes,
                 'attachment' => $attachmentPath,
                 'created_by' => Auth::id()
             ]);

             AuditLog::create([
                 'user_id' => Auth::id(),
                 'activity' => 'Progress ditambahkan',
                 'details' => 'Menambahkan log progres for Action Item ID: ' . $action_item_id
             ]);
         }

         // Audit logging for action item modification
         AuditLog::create([
             'user_id' => Auth::id(),
             'activity' => 'Action Item diubah',
             'details' => 'Memperbarui status/prioritas Action Item ID: ' . $action_item_id
         ]);

         // Recalculate project progress
         if ($actionItem->project_id) {
             $projectId = $actionItem->project_id;
             $totalItems = ActionItem::where('project_id', $projectId)->where('statusenabled', true)->count();
             $doneStatusId = MasterActionStatus::where('name', 'Done')->value('id');
             $doneItems = ActionItem::where('project_id', $projectId)->where('status_id', $doneStatusId)->where('statusenabled', true)->count();
             $progress = $totalItems > 0 ? round(($doneItems / $totalItems) * 100, 2) : 0;
             Project::where('id', $projectId)->update(['progress' => $progress]);
         }

         return redirect()->back()->with('success', 'Action Item berhasil diperbarui!');
     }

    public function getDataDaily()
    {
        $today = date('Y-m-d');

        $dailyReport = [];
        $resultDailyReport = [];

        $cekExist = DailyReport::whereDate('created_at',$today)
                                ->where('statusenabled', true)
                                ->exists();

        if (!$cekExist) {
           $dailyReport = DB::select("select x.kd_list,initcap(x.kegiatan) as kegiatan, x.status from (
                            select 
                            jt.kd_list||'-'||jt.nourut as kd_list, jt.task||' dengan status server = '|| st2.status as kegiatan, st.status
                            from jadwal_t as jt 
                            join status_m as st on st.id = jt.developer_status_id
                            join status_m as st2 on st2.id = jt.server_status_id
                            where jt.statusenabled = true and DATE(jt.created_at) = '$today'

                            union all 

                            select 
                            ag.kd_list||'-'||ag.nourut as kd_list, ag.kegiatan||' Pada Tanggal '||to_char(ag.tgl_jadwal,'YYYY-MM-DD')||' Pukul '||jm.jam||' Dengan Pihak '||pt.parties||' - '||pg.namalengkap as kegiatan, st.status
                            from agenda_t as ag 
                            join parties_m as pt on pt.id = ag.parties_id
                            join pegawai_m as pg on pg.id = ag.picextern_id
                            join status_m as st on st.id = ag.status_id
                            join jam_m as jm on jm.id = ag.jam_id
                            where ag.statusenabled = true and DATE(ag.created_at) = '$today'
                            ) as x 
                            order by
                            x.kd_list");
        } else {
            $resultDailyReport = DailyReport::whereDate('created_at',$today)
                                            ->where('statusenabled',true)
                                            ->get();
                                           
        }

        return view('dashboard-dailyreport', compact('dailyReport','resultDailyReport'));
    }

     public function getDataDaily2()
    {
        $today = date('Y-m-d');

        $dailyReport = [];
        $resultDailyReport = [];

        $cekExist = DailyReport::whereDate('created_at',$today)
                                ->where('statusenabled', true)
                                ->exists();

        if (!$cekExist) {
           $dailyReport = DB::select("
                            select * from (
                                select row_number() over (partition by ac.jadwal_id order by ac.created_at desc) as cek,ac.aktifitas,jt.kd_list||'-'||jt.nourut||'-jadwal' as kd_list,ac.status  from activity_t as ac
                                join jadwal_t as jt on jt.id = ac.jadwal_id
                                -- join status_m as st on st.id = jt.developer_status_id
                                where ac.statusenabled = true and ac.jadwal_id is not null and date(ac.created_at) = '$today' ) as x
                            where x.cek = 1

                                union all 

                            select * from (
                                select row_number() over (partition by ac.agenda_id order by ac.created_at desc) as cek,ac.aktifitas, ag.kd_list||'-'||ag.nourut||'-agenda' as kd_list,ac.status from activity_t as ac
                                join agenda_t as ag on ag.id = ac.agenda_id
                                -- join status_m as st on st.id = ag.status_id
                                where ac.statusenabled = true and ac.agenda_id is not null and date(ac.created_at) = '$today' ) as x 
                            where x.cek = 1 
           ");
        } else {
            $resultDailyReport = DailyReport::whereDate('created_at',$today)
                                            ->where('statusenabled',true)
                                            ->orderBy('status','desc')
                                            ->get();                             
        }

        return view('dashboard-dailyreport', compact('dailyReport','resultDailyReport'));
    }

     public function saveDaily(Request $request)
    {
        $today = Carbon::now();
        $data = $request->input('data');
        if (!$data || !is_array($data)) {
            return back()->with('error', 'Tidak ada data yang dikirim.');
        }
    
        
        foreach ($data as $row) {
            // dd($row['kd_list']);
            $newId = DailyReport::max('id')+1;
            DailyReport::insert([
                'id' => $newId,
                'kd_list' => $row['kd_list'],
                'dailyreport' => $row['aktifitas'],
                'status' => $row['status'],
                'statusenabled' => true,
                'created_at' => $today,
            ]);
        }

        return redirect()->back()->with('success', 'Daily Report berhasil disimpan.');
    }

     public function getDataWeekly()
    {
        $today = date('Y-m-d');
        // $today = Carbon::today(); // Carbon object
        // $limitDate = $today->copy()->addDays(4); // 4 hari ke depan
        
          $getTimeline = Timeline::whereRaw("tgl_deadline > CURRENT_DATE")
                                 ->select('tgl_deadline','id',
                                 DB::raw("tgl_deadline - INTERVAL '4 days' AS tglawal"))
                                 ->first();
        //   dd($getTimeline);
                               
        $tglDeadline = $getTimeline['tgl_deadline'];
        $idTimeline = $getTimeline['id'];
        $tglAwal = Carbon::parse($getTimeline['tglawal'])->format('Y-m-d');
        $weeklyReport = [];
        $resultWeeklyReport = [];

        $cekExist = WeeklyReport::whereDate('created_at',$today)
                                ->where('statusenabled', true)
                                ->exists();
        //    dd($tglAwal);

        if (!$cekExist) {
           $weeklyReport = DB::select("
                           select * from (
                            select row_number() over (partition by dr.kd_list order by dr.created_at desc) as cek,dr.dailyreport,
                            CONCAT(REPLACE(TRIM(dr.status), ' ', '')) as status,
                            CONCAT(REPLACE(TRIM(dr.kd_list), ' ', '')) as kd_list,
                            CONCAT(REPLACE(TRIM(tl.week), ' ', '')) as week,
                            CONCAT(REPLACE(TRIM(tl.month), ' ', '')) as month
                            from daily_report_t as dr
                            join timeline_m as tl on tl.id = $idTimeline
                            where date(dr.created_at) between '$tglAwal' and '$tglDeadline' and dr.statusenabled = true ) as x 
                            where x.cek = 1
           ");
        //    dd($weeklyReport);
        } else {
            $resultWeeklyReport = WeeklyReport::whereDate('created_at',$today)
                                            ->where('statusenabled',true)
                                            ->orderBy('status','desc')
                                            ->get();       
                                            //   dd($resutlWeeklyReport);                      
        }

        return view('dashboard-weeklyreport', compact('weeklyReport','resultWeeklyReport'));
    }

      public function saveWeekly(Request $request)
    {
        $today = Carbon::now();
        $data = $request->input('data');
        if (!$data || !is_array($data)) {
            return back()->with('error', 'Tidak ada data yang dikirim.');
        }
    
        
        foreach ($data as $row) {
            // dd($row['kd_list']);
            $newId = WeeklyReport::max('id')+1;
            WeeklyReport::insert([
                'id' => $newId,
                'kd_list' => $row['kd_list'],
                'weeklyreport' => $row['dailyreport'],
                'status' => $row['status'],
                'statusenabled' => true,
                'week' => $row['week'],
                'month' => $row['month'],
                'created_at' => $today,
            ]);
        }

        return redirect()->back()->with('success', 'Weekly Report berhasil disimpan.');
    }

    public function getProjectActivity()
    {
        $projectActivities = ProjectActivity::with(['prioritas', 'site', 'pic'])
            ->where('statusenabled', true)
            ->orderBy('tgl_deadline', 'asc')
            ->paginate(3);

        $priorities = Prioritas::where('statusenabled', true)->get();
        $sites = Site::where('statusenabled', true)->get();
        $pics = Pegawai::where('statusenabled', true)->get();

        return view('project-activity', compact('projectActivities', 'priorities', 'sites', 'pics'));
    }

    public function saveProjectActivity(Request $request)
    {
        $request->validate([
            'prioritas_id' => 'required|exists:prioritas_m,id',
            'site_id' => 'required|exists:site_m,id',
            'pic_id' => 'required|exists:pegawai_m,id',
            'tgl_masuk' => 'required|date',
            'tgl_deadline' => 'required|date|after_or_equal:tgl_masuk',
            'task' => 'required|string',
            'status' => 'required|string',
        ]);

        $newId = ProjectActivity::max('id') ?? 0;

        ProjectActivity::create([
            'id' => $newId + 1,
            'prioritas_id' => $request->prioritas_id,
            'site_id' => $request->site_id,
            'pic_id' => $request->pic_id,
            'tgl_masuk' => $request->tgl_masuk,
            'tgl_deadline' => $request->tgl_deadline,
            'task' => $request->task,
            'status' => $request->status,
            'statusenabled' => true,
        ]);

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("SELECT setval('project_activity_id_seq', (SELECT MAX(id) FROM project_activity))");
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Project Activity dibuat',
            'details' => 'Membuat project activity: ' . substr($request->task, 0, 50) . '...'
        ]);

        return redirect()->back()->with('success', 'Project Activity berhasil disimpan!');
    }

    public function updateProjectActivity(Request $request, $id)
    {
        $activity = ProjectActivity::findOrFail($id);

        $request->validate([
            'prioritas_id' => 'required|exists:prioritas_m,id',
            'site_id' => 'required|exists:site_m,id',
            'pic_id' => 'required|exists:pegawai_m,id',
            'tgl_masuk' => 'required|date',
            'tgl_deadline' => 'required|date|after_or_equal:tgl_masuk',
            'task' => 'required|string',
            'status' => 'required|string',
        ]);

        $activity->update([
            'prioritas_id' => $request->prioritas_id,
            'site_id' => $request->site_id,
            'pic_id' => $request->pic_id,
            'tgl_masuk' => $request->tgl_masuk,
            'tgl_deadline' => $request->tgl_deadline,
            'task' => $request->task,
            'status' => $request->status,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Project Activity diubah',
            'details' => 'Memperbarui project activity ID: ' . $id
        ]);

        return redirect()->back()->with('success', 'Project Activity berhasil diperbarui!');
    }

    public function deleteProjectActivity($id)
    {
        $activity = ProjectActivity::findOrFail($id);
        $activity->update(['statusenabled' => false]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Project Activity dihapus',
            'details' => 'Menghapus project activity ID: ' . $id
        ]);

        return redirect()->back()->with('success', 'Project Activity berhasil dihapus!');
    }

    public function createFollowupMeeting($parent_meeting_result_id)
    {
        $parent = MeetingResult::findOrFail($parent_meeting_result_id);

        if (trim($parent->status) !== 'On Going') {
            return redirect()->back()->with('error', 'Hanya rapat berstatus On Going yang dapat dibuat tindak lanjutnya.');
        }

        $agenda = $parent->agendaMeeting;

        // Calculate new code
        $kdSite = Site::where('id', $agenda->site_id)->value('kdsite');
        $kdSiteClean = trim($kdSite);
        $existingCount = MeetingResult::where('agenda_meeting_id', $agenda->id)->count();
        $newCode = 'MEET-' . $kdSiteClean . '-' . $agenda->nourut . '-F' . $existingCount;

        $newMeeting = MeetingResult::create([
            'meeting_code' => $newCode,
            'agenda_meeting_id' => $agenda->id,
            'project_id' => $parent->project_id,
            'status' => 'Pending',
            'statusenabled' => true
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Tindak lanjut rapat dibuat',
            'details' => 'Membuat tindak lanjut rapat ' . $newCode . ' dari rapat induk ' . $parent->meeting_code
        ]);

        return redirect()->back()->with('success', 'Rapat tindak lanjut ' . $newCode . ' berhasil dibuat!');
    }

    public function updateJadwal(Request $request, $id)
    {
        $validated = $request->validate([
            'prioritas_id' => 'required',
            'jenistask_id' => 'required',
            'site_id' => 'required',
            'timeline_id' => 'required',
            'tgl_masuk' => 'required|date',
            'tgl_deadline' => 'required|date|after_or_equal:tgl_masuk',
            'task' => 'required|string',
            'picrequest_id' => 'required',
            'picdeveloper_id' => 'required',
        ]);

        $jadwal = Jadwal::findOrFail($id);
        
        // Update kd_list and nourut if site has changed
        if ($jadwal->site_id != $request->site_id) {
            $kdSite = Site::where('statusenabled', true)
                            ->where('id', $request->site_id)
                            ->value('kdsite');
            $count = Jadwal::where('kd_list', $kdSite)->count() + 1;
            $jadwal->kd_list = trim($kdSite);
            $jadwal->nourut = $count;
        }

        $jadwal->update([
            'prioritas_id' => $request->prioritas_id,
            'jenistask_id' => $request->jenistask_id,
            'site_id' => $request->site_id,
            'timeline_id' => $request->timeline_id,
            'tgl_masuk' => $validated['tgl_masuk'],
            'tgl_deadline' => $request->tgl_deadline,
            'task' => $validated['task'],
            'picrequest_id' => $request->picrequest_id,
            'picdeveloper_id' => $request->picdeveloper_id,
            'updated_at' => now(),
        ]);

        // Also update legacy activities if they exist
        Activity::where('jadwal_id', $id)->update([
            'aktifitas' => $validated['task'],
        ]);

        return redirect()->back()->with('success', 'Timeline Request berhasil diperbarui!');
    }

    public function deleteJadwal($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update(['statusenabled' => false]);

        return redirect()->back()->with('success', 'Timeline Request berhasil dihapus!');
    }

    public function uploadAudio(Request $request, $meeting_result_id)
    {
        $request->validate([
            'audio_file' => 'required|file',
            'extract' => 'nullable|string', // '1' or 'true'
            'mime_type' => 'nullable|string',
        ]);

        $meetingResult = MeetingResult::findOrFail($meeting_result_id);

        if ($request->hasFile('audio_file')) {
            $file = $request->file('audio_file');
            
            // Get mime type before moving the file to prevent temporary file not found exception
            $mimeType = $request->input('mime_type') ?: ($file->getClientMimeType() ?: ($file->getMimeType() ?: 'audio/webm'));
            
            $filename = 'audio_' . $meetingResult->id . '_' . time() . '.webm';
            $file->move('meeting_audios', $filename);

            if ($meetingResult->audio_path) {
                $oldPath = public_path($meetingResult->audio_path);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $meetingResult->audio_path = 'meeting_audios/' . $filename;
            $meetingResult->save();

            $transcription = '';
            $extractedActionItems = [];

            if ($request->input('extract') === '1' || $request->input('extract') === 'true') {
                try {
                    $filePath = public_path($meetingResult->audio_path);
                    
                    $geminiData = \App\Services\GeminiService::extractFromAudio($filePath, $mimeType);
                    
                    $transcription = $geminiData['transcription'] ?? '';
                    $rawActionItems = $geminiData['action_items'] ?? [];

                    // Fetch all categories and priorities to perform database-agnostic matching in PHP
                    $allPriorities = \App\Models\MasterPriority::where('statusenabled', true)->get();
                    $allCategories = \App\Models\MasterActionCategory::where('statusenabled', true)->get();

                    foreach ($rawActionItems as $item) {
                        $desc = $item['description'] ?? '';
                        if (empty($desc)) continue;

                        $priorityName = $item['priority'] ?? 'Low';
                        $priorityObj = $allPriorities->first(function ($p) use ($priorityName) {
                            return str_contains(strtolower($p->name), strtolower(trim($priorityName))) || 
                                   str_contains(strtolower(trim($priorityName)), strtolower($p->name));
                        }) ?? $allPriorities->first();

                        $categoryName = $item['category'] ?? 'Non Develop';
                        $categoryObj = $allCategories->first(function ($c) use ($categoryName) {
                            return str_contains(strtolower($c->name), strtolower(trim($categoryName))) || 
                                   str_contains(strtolower(trim($categoryName)), strtolower($c->name));
                        }) ?? $allCategories->first();

                        $targetDate = $item['target_date'] ?? null;
                        // Validate format (YYYY-MM-DD) or default to today's date
                        if (!$targetDate || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $targetDate)) {
                            $targetDate = date('Y-m-d');
                        }

                        $extractedActionItems[] = [
                            'description' => $desc,
                            'priority_id' => $priorityObj ? $priorityObj->id : '',
                            'category_id' => $categoryObj ? $categoryObj->id : '',
                            'target_date' => $targetDate,
                        ];
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Gemini extraction error: ' . $e->getMessage());
                    // We still return success: true for saving the audio file, but with an extraction error message
                    return response()->json([
                        'success' => true,
                        'audio_path' => $meetingResult->audio_path,
                        'extraction_error' => 'Gagal mengekstrak audio: ' . $e->getMessage()
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'audio_path' => $meetingResult->audio_path,
                'transcription' => $transcription,
                'action_items' => $extractedActionItems
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengupload file audio.'
        ], 400);
    }

    public function extractExistingAudio($meeting_result_id)
    {
        $meetingResult = MeetingResult::findOrFail($meeting_result_id);
        
        if (!$meetingResult->audio_path) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada file rekaman audio yang tersimpan untuk rapat ini.'
            ], 400);
        }

        $filePath = public_path($meetingResult->audio_path);
        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File rekaman audio tidak ditemukan di server.'
            ], 400);
        }

        // Attempt to determine the mime type from the saved file extension
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeType = 'audio/webm'; // Default fallback
        if ($extension === 'ogg') {
            $mimeType = 'audio/ogg';
        } elseif ($extension === 'mp3') {
            $mimeType = 'audio/mp3';
        } elseif ($extension === 'wav') {
            $mimeType = 'audio/wav';
        }

        try {
            $geminiData = \App\Services\GeminiService::extractFromAudio($filePath, $mimeType);
            
            $transcription = $geminiData['transcription'] ?? '';
            $rawActionItems = $geminiData['action_items'] ?? [];

            $allPriorities = \App\Models\MasterPriority::where('statusenabled', true)->get();
            $allCategories = \App\Models\MasterActionCategory::where('statusenabled', true)->get();
            $extractedActionItems = [];

            foreach ($rawActionItems as $item) {
                $desc = $item['description'] ?? '';
                if (empty($desc)) continue;

                $priorityName = $item['priority'] ?? 'Low';
                $priorityObj = $allPriorities->first(function ($p) use ($priorityName) {
                    return str_contains(strtolower($p->name), strtolower(trim($priorityName))) || 
                           str_contains(strtolower(trim($priorityName)), strtolower($p->name));
                }) ?? $allPriorities->first();

                $categoryName = $item['category'] ?? 'Non Develop';
                $categoryObj = $allCategories->first(function ($c) use ($categoryName) {
                    return str_contains(strtolower($c->name), strtolower(trim($categoryName))) || 
                           str_contains(strtolower(trim($categoryName)), strtolower($c->name));
                }) ?? $allCategories->first();

                $targetDate = $item['target_date'] ?? null;
                if (!$targetDate || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $targetDate)) {
                    $targetDate = date('Y-m-d');
                }

                $extractedActionItems[] = [
                    'description' => $desc,
                    'priority_id' => $priorityObj ? $priorityObj->id : '',
                    'category_id' => $categoryObj ? $categoryObj->id : '',
                    'target_date' => $targetDate,
                ];
            }

            return response()->json([
                'success' => true,
                'transcription' => $transcription,
                'action_items' => $extractedActionItems
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gemini extraction error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengekstrak audio: ' . $e->getMessage()
            ], 500);
        }
    }

}


