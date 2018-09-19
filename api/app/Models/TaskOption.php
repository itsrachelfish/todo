<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskOption extends Model
{
    protected $fillable = ['key', 'value'];

    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }
}
