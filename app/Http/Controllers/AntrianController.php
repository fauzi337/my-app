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
use Illuminate\Support\Facades\DB;



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
                            ->where('jt.developer_status_id',1)
                            ->select('jt.kd_list','pr.namaprioritas','js.jenistask','si.namasite','tm.gabung','jt.tgl_masuk','jt.task','jt.tgl_deadline',
                            DB::raw("CONCAT(jt.kd_list, '-', jt.nourut) as kd_list"))
                            ->orderBy('jt.created_at')
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

        return view('dashboard-jadwal', compact('daftarJadwal','pegawai','prioritas','jenisTask','site','timeline','picReq','statusDev','statusServer','statusPicReq','statusFinal'));
      
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
            'kd_list' => $kdSite,
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
            'kd_list' => $kdSite . '-' . $count,
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
                            ->select('pr.namaprioritas','js.jenistask','si.namasite','tm.gabung','jt.tgl_masuk','jt.task','jt.tgl_deadline','pg.namapegawai','st.id as devstid','jt.id',
                            DB::raw("CONCAT(pg2.kdjenispegawai, ' - ', pg2.namapegawai) as dev,CONCAT(jt.kd_list, '-', jt.nourut) as kd_list"),
                                    'st.status as devstatus','st2.status as servstatus','st2.id as servstid','st3.id as picreqstid')
                            ->orderBy('jt.prioritas_id','desc')
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
                            ->where('st4.id',21)
                            ->orWhere('st2.id',7)
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
                            ->select('pr.namaprioritas','js.jenistask','si.namasite','tm.gabung','jt.tgl_masuk','jt.task','jt.tgl_deadline','pg.namapegawai','jt.id',
                            DB::raw("CONCAT(pg2.kdjenispegawai, ' - ', pg2.namapegawai) as dev,CONCAT(jt.kd_list, '-', jt.nourut) as kd_list"),
                                    'st.status as devstatus','st2.status as servstatus','st3.status as picreqst','st3.id as picreqstid','st4.id as finalstid','st4.status as finalst','jt.path',
                                    'st.id as devstid')
                            ->orderBy('jt.prioritas_id','desc')
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
                            ->whereIn('st4.id',[21])
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
            $uploadFile->path = 'public/storage/pdf/' . $filename;
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
        $listAgenda = Agenda::from('agenda_t as ag')
                            ->join('site_m as si','si.id','=','ag.site_id')
                            ->join('pegawai_m as pg','pg.id','=','ag.picintern_id')
                            ->join('pegawai_m as pg2','pg2.id','=','ag.picextern_id')
                            ->join('status_m as st','st.id','=','ag.status_id')
                            ->join('parties_m as pt','pt.id','=','ag.parties_id')
                            ->join('jam_m as jm','jm.id','=','ag.jam_id')
                            ->join('unit_m as un','un.id','=','ag.unit_id')
                            // ->whereIn('st4.id',[21])
                            ->select('ag.id','si.namasite','ag.tgl_jadwal','ag.tgl_realisasi','ag.kegiatan','pg.namapegawai as picintern','pg2.namapegawai as picextern','st.status','pt.parties',
                            'jm.jam','un.unit','st.id as stid',
                            // )
                            DB::raw("CONCAT(ag.kd_list, '-', ag.nourut) as kd_list"))
                            ->orderBy('ag.id','desc')
                            ->get();
                            
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

        return view('dashboard-agenda', compact('listAgenda','site','jam','parties','unit','picInternal','picExternal','statusAgenda'));
    }

    public function postAgenda(Request $request)
    {
        $today = Carbon::now();
        $saveAgenda = new Agenda();
        $newid = Agenda::max('id');

        $validated = $request->validate([
            'tgl_jadwal' => 'required|date',
            'kegiatan' => 'required|string',
            'site' => 'required|exists:site_m,id',
            'jam' => 'required|exists:jam_m,id',
            // 'status' => 'required|exists:status_m,id',
            'parties' => 'required|exists:parties_m,id',
            'unit' => 'required|exists:unit_m,id',
            'pic_internal' => 'required|exists:pegawai_m,id',
            'pic_external' => 'required|exists:pegawai_m,id',
        ],[
            'tgl_jadwal.required' => 'Penjadwalan Wajib di Isi !',
            'kegiatan.required' => 'Kegiatan Wajib di Isi !',
            'site.required' => 'Site Wajib di Isi !',
            'jam.required' => 'Jam Wajib di Isi !',
            // 'status.required' => 'Status Wajib di Isi !',
            'parties.required' => 'Pihak Wajib di Isi !',
            'unit.required' => 'Unit Wajib di Isi !',
            'pic_internal.required' => 'PIC Internal Wajib di Isi !',
            'pic_external.required' => 'PIC Eksternal Wajib di Isi !',
        ]);

        $kdSite = Site::where('statusenabled', true)
                        ->where('id', $request->site)
                        ->value('kdsite');

        $count = Agenda::where('kd_list', $kdSite)->count() + 1;

        $saveAgenda->id = $newid +1;
        $saveAgenda->kegiatan = $validated['kegiatan'];
        $saveAgenda->tgl_jadwal = $validated['tgl_jadwal'];
        $saveAgenda->site_id = $validated['site'];
        $saveAgenda->jam_id = $validated['jam'];
        $saveAgenda->status_id = 23;
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

        $getDataAgenda = Agenda::from('agenda_t as ag')
                            ->join('status_m as st', 'st.id', '=', 'ag.status_id')
                            ->join('unit_m as ut', 'ut.id', '=', 'ag.unit_id')
                            ->join('pegawai_m as pg', 'pg.id', '=', 'ag.picextern_id')
                            ->select('ag.kegiatan', 'st.status','ut.unit','pg.namapegawai','st.id as stid')
                            ->where('ag.id', $saveAgenda->id)
                            ->first(); // Ambil satu data saja

                        // Pastikan $getDataDev tidak null
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
                                'agenda_id' => $newid +1,
                            ]);
                        }

        return redirect()->route('dashboard.agenda')->with('success', 'Simpan Berhasil !');
    }

     public function updateAgenda(Request $request, $id)
    {
        $today = carbon::now();
        $id = intval($request->id); // Ubah ke integer

        $updateJadwal = Agenda::find($id);
        $updateJadwal->status_id = $request->statusid;
        $updateJadwal->tgl_realisasi = $request->tgl_selesai;
        $updateJadwal->updated_at = $today;
        $updateJadwal->save();

        $getDataAgenda = Agenda::from('agenda_t as ag')
                            ->join('status_m as st', 'st.id', '=', 'ag.status_id')
                            ->join('unit_m as ut', 'ut.id', '=', 'ag.unit_id')
                            ->join('pegawai_m as pg', 'pg.id', '=', 'ag.picextern_id')
                            ->select('ag.kegiatan', 'st.status','ut.unit','pg.namapegawai','st.id as stid',
                            DB::raw("CONCAT(REPLACE(TRIM(ag.kd_list), ' ', ''), '-', ag.nourut) AS kd_list"))
                            ->where('ag.id', $id)
                            ->first(); // Ambil satu data saja
                        // Pastikan $getDataDev tidak null
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

}
