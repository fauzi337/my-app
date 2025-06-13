<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    protected $table = 'jadwal_t';
    // protected $fillable = ['prioritas_id', 'jenistask_id', 'site_id','timeline_id','tgl_masuk','', 'task', 'picrequest_id','','','','','','','','','',''];
    protected $guarded = ['id'];

        // public function getFormattedNomorAntrianAttribute()
        // {
        //     $prefix = $this->kategori === 'orang_tua' ? 'OT' : 'AM';
        //     return $prefix . '-' . str_pad($this->nomor_antrian, 3, '0', STR_PAD_LEFT);
        // }
        


}

