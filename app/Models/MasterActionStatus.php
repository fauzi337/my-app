<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterActionStatus extends Model
{
    use HasFactory;

    protected $table = 'master_action_status';
    protected $guarded = ['id'];
}
