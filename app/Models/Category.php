<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Step;

class Category extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];
  protected $fillable = ['name'];

  public function steps() {
    return $this->hasMany(Step::class);
  }
}
