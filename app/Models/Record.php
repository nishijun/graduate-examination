<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Kid;

class Record extends Model
{
  protected $fillable = ['kid_id', 'user_id'];

  public function user() {
    return $this->belongsTo(User::class);
  }
  public function kid() {
    return $this->belongsTo(Kid::class);
  }
}
