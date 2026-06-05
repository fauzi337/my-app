<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterActionCategory extends Model
{
    use HasFactory;

    protected $table = 'master_action_category';
    protected $guarded = ['id'];
}
