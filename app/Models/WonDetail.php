<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class WonDetail extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'won_details';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'progress' => 'integer',
        ];
    }

    public function won()
    {
        return $this->belongsTo(Won::class, 'won_id');
    }

    public function picDeveloper()
    {
        return $this->belongsTo(Pegawai::class, 'pic_developer_id');
    }

    public function checklists()
    {
        return $this->hasMany(WonDetailChecklist::class, 'won_detail_id');
    }
}
