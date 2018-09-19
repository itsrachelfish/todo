<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['status', 'type', 'description'];

    public function parent()
    {
        return $this->belongsTo('App\Models\Task', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Task', 'parent_id');
    }

    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    public function options()
    {
        return $this->hasMany('App\Models\TaskOption');
    }

    public function statuses()
    {
        return $this->hasMany('App\Models\Status');
    }
}
