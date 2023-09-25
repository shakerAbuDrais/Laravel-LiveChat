<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'subject',
        'pass_mark',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_subject')
            ->withPivot('obtained_mark');
    }
}
