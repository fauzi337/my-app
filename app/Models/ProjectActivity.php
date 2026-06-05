<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectActivity extends Model
{
    use HasFactory;

    protected $table = 'project_activity';
    protected $guarded = ['id'];

    public function prioritas()
    {
        return $this->belongsTo(Prioritas::class, 'prioritas_id');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function pic()
    {
        return $this->belongsTo(Pegawai::class, 'pic_id');
    }
}
