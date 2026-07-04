<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSla extends Model
{
    use HasFactory;

    protected $table = 'master_sla_m';

    protected $fillable = [
        'prioritas_id',
        'jenistask_id',
        'sla_hours',
    ];

    public function prioritas()
    {
        return $this->belongsTo(Prioritas::class, 'prioritas_id');
    }

    public function jenistask()
    {
        return $this->belongsTo(Jenistask::class, 'jenistask_id');
    }
}
