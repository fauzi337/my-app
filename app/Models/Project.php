<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'project';
    protected $guarded = ['id'];

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function meetingResults()
    {
        return $this->hasMany(MeetingResult::class, 'project_id');
    }

    public function actionItems()
    {
        return $this->hasMany(ActionItem::class, 'project_id');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }
}
