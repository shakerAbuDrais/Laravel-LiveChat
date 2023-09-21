<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
  protected $table = 'subject';

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
