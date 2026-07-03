<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class WonDetailChecklist extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'won_detail_checklists';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_checked' => 'boolean',
            'checked_at' => 'datetime',
        ];
    }

    public function wonDetail()
    {
        return $this->belongsTo(WonDetail::class, 'won_detail_id');
    }
}
