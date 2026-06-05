<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionItem extends Model
{
    use HasFactory;

    protected $table = 'action_item';
    protected $guarded = ['id'];

    public function meetingResult()
    {
        return $this->belongsTo(MeetingResult::class, 'meeting_result_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function category()
    {
        return $this->belongsTo(MasterActionCategory::class, 'category_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function picPerson()
    {
        return $this->belongsTo(User::class, 'pic_person_id');
    }

    public function priority()
    {
        return $this->belongsTo(MasterPriority::class, 'priority_id');
    }

    public function status()
    {
        return $this->belongsTo(MasterActionStatus::class, 'status_id');
    }

    public function progressUpdates()
    {
        return $this->hasMany(ActionItemProgress::class, 'action_item_id')->orderBy('created_at', 'desc');
    }
}
