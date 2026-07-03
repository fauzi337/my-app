<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterModulDetail extends Model
{
    use HasFactory;

    protected $table = 'master_modul_details';
    protected $guarded = [];

    public function modul()
    {
        return $this->belongsTo(MasterModul::class, 'master_modul_id');
    }
}
