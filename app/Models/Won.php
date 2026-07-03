<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Won extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'wons';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tanggal_kontrak' => 'date',
            'target_go_live' => 'date',
            'nilai_kontrak' => 'integer',
        ];
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function picKoordinator()
    {
        return $this->belongsTo(Pegawai::class, 'pic_koordinator_id');
    }

    public function picImplementator()
    {
        return $this->belongsTo(Pegawai::class, 'pic_implementator_id');
    }

    public function details()
    {
        return $this->hasMany(WonDetail::class, 'won_id');
    }

    public function wbs()
    {
        return $this->hasMany(ProjectWbs::class, 'won_id');
    }
}
