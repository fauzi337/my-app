<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;
    
    protected $fillable = ['nama', 'kategori', 'nomor_antrian'];

        public function getFormattedNomorAntrianAttribute()
        {
            $prefix = $this->kategori === 'orang_tua' ? 'OT' : 'AM';
            return $prefix . '-' . str_pad($this->nomor_antrian, 3, '0', STR_PAD_LEFT);
        }
        


}
