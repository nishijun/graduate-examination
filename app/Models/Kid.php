<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Step;
use App\Models\Record;

class Kid extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];
  protected $fillable = ['step_id', 'order', 'title', 'content', 'thumbnail'];

  public function step() {
    return $this->belongsTo(Step::class);
  }

  public function records() {
    return $this->hasMany(Record::class);
  }
}
