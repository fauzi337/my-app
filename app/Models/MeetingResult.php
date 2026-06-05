<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingResult extends Model
{
    use HasFactory;

    protected $table = 'meeting_result';
    protected $guarded = ['id'];

    public function agendaMeeting()
    {
        return $this->belongsTo(AgendaMeeting::class, 'agenda_meeting_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function notesHistory()
    {
        return $this->hasMany(MeetingNotes::class, 'meeting_result_id');
    }

    public function actionItems()
    {
        return $this->hasMany(ActionItem::class, 'meeting_result_id');
    }
}
