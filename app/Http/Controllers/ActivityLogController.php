<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activities = DB::table('log_sla_t as log')
            ->leftJoin('jadwal_t as jt', 'jt.id', '=', 'log.jadwal_id')
            ->leftJoin('site_m as si', 'si.id', '=', 'jt.site_id')
            ->leftJoin('pegawai_m as pg_dev', 'pg_dev.id', '=', 'jt.picdeveloper_id')
            ->leftJoin('pegawai_m as pg_req', 'pg_req.id', '=', 'jt.picrequest_id')
            ->leftJoin('users as us', 'us.id', '=', 'log.user_id')
            ->select(
                'log.id',
                'log.tipe_aktifitas',
                'log.status_sebelumnya',
                'log.status_sesudahnya',
                'log.aktifitas',
                'log.created_at',
                'jt.task',
                'jt.tgl_masuk',
                'jt.tgl_deadline',
                'jt.tgl_selesai',
                'jt.sla_hours',
                'si.namasite',
                'pg_dev.namapegawai as pic_developer',
                'pg_req.namapegawai as pic_request',
                'us.name as nama_user'
            )
            ->orderBy('log.created_at', 'desc')
            ->get();

        $devActivities = collect();
        $picActivities = collect();

        foreach ($activities as $act) {
            // Tab 1: Created, Developer Update, Detail Edit
            // Tab 2: PIC Request Update
            if ($act->tipe_aktifitas === 'PIC Request Update') {
                $picActivities->push($act);
            } else {
                $devActivities->push($act);
            }
        }

        return view('antrian.log', compact('devActivities', 'picActivities'));
    }
}
