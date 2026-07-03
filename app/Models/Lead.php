<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'modul_diminati' => 'array',
            'tanggal_masuk' => 'date',
            'target_closing' => 'date',
            'tanggal_followup_berikutnya' => 'date',
            'estimasi_nilai' => 'integer',
        ];
    }

    public function activities()
    {
        return $this->hasMany(LeadActivity::class, 'lead_id')->orderBy('tanggal_aktivitas', 'desc');
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'lead_id')->orderBy('versi', 'desc');
    }

    public function picInternal()
    {
        return $this->belongsTo(User::class, 'pic_internal');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'lead_id');
    }
}
