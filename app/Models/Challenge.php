<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Step;

class Challenge extends Model
{
  protected $fillable = ['step_id', 'user_id'];

  public function step() {
    return $this->belongsTo(Step::class);
  }

  public function user() {
    return $this->belongsTo(User::class);
  }
}
