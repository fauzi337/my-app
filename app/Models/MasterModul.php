<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterModul extends Model
{
    use HasFactory;

    protected $table = 'master_moduls';
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(MasterModulDetail::class, 'master_modul_id');
    }
}
