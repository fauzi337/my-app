<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class LeadActivity extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tanggal_aktivitas' => 'datetime',
            'tanggal_followup_berikutnya' => 'date',
        ];
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function picInternal()
    {
        return $this->belongsTo(User::class, 'pic_internal');
    }

    public function meeting()
    {
        return $this->belongsTo(MeetingResult::class, 'meeting_id');
    }
}
