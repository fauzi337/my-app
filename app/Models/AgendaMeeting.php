<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaMeeting extends Model
{
    use HasFactory;

    protected $table = 'agenda_meeting';
    protected $guarded = ['id'];

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function jam()
    {
        return $this->belongsTo(Jam::class, 'jam_id');
    }

    public function parties()
    {
        return $this->belongsTo(Parties::class, 'parties_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function picInternal()
    {
        return $this->belongsTo(Pegawai::class, 'picintern_id');
    }

    public function picExternal()
    {
        return $this->belongsTo(Pegawai::class, 'picextern_id');
    }

    public function meetingResult()
    {
        return $this->hasOne(MeetingResult::class, 'agenda_meeting_id');
    }
}
