<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckList extends Model
{
    protected $guarded = [
        'id'
    ];

    public function checklistItems()
    {
        return $this->hasMany(ChecklistItem::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}