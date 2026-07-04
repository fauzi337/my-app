<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollGaji extends Model
{
    use HasFactory;

    protected $table = 'payroll_gaji_t';

    protected $fillable = [
        'pegawai_id',
        'bulan',
        'tahun',
        'gaji_pokok',
        'tunjangan_kinerja',
        'potongan_performa',
        'gaji_diterima',
        'status_pembayaran',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
