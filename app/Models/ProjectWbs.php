<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProjectWbs extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'project_wbs';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'due_date' => 'date',
            'finish_date' => 'date',
            'duration' => 'integer',
            'order_num' => 'integer',
        ];
    }

    public function won()
    {
        return $this->belongsTo(Won::class, 'won_id');
    }

    public function pic()
    {
        return $this->belongsTo(Pegawai::class, 'jmt_pic_id');
    }

    public function predecessor()
    {
        return $this->belongsTo(ProjectWbs::class, 'predecessor_id');
    }

    public function successors()
    {
        return $this->hasMany(ProjectWbs::class, 'predecessor_id');
    }
}
