<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;
    protected $table = 'site_m';
    // protected $fillable = ['id ', 'namapegawai', 'jenispegawai', 'kdjenispegawai', 'produk', 'site_id'];
}

