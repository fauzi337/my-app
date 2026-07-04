<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SdmEvaluasiKinerja extends Model
{
    use HasFactory;

    protected $table = 'sdm_evaluasi_kinerja_t';

    protected $fillable = [
        'pegawai_id',
        'bulan',
        'tahun',
        'total_task',
        'task_tepat_waktu',
        'task_terlambat',
        'rata_rata_skor',
        'persentase_potongan',
        'status_evaluasi',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
