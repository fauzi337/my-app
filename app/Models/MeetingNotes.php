<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingNotes extends Model
{
    use HasFactory;

    protected $table = 'meeting_notes';
    protected $guarded = ['id'];

    public function meetingResult()
    {
        return $this->belongsTo(MeetingResult::class, 'meeting_result_id');
    }
}
