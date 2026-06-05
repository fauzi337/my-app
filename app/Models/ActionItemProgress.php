<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionItemProgress extends Model
{
    use HasFactory;

    protected $table = 'action_item_progress';
    protected $guarded = ['id'];

    public function actionItem()
    {
        return $this->belongsTo(ActionItem::class, 'action_item_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
