<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckListItem extends Model
{
    protected $guarded = [
        'id'
    ];

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }
}
