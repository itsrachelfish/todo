<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['status', 'duration', 'started_at', 'ended_at'];

    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }
}
