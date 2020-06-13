<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Category;
use App\Models\Kid;
use App\Models\Challenge;

class Step extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];
  protected $fillable = ['user_id', 'category_id', 'title', 'achievement_time', 'content', 'thumbnail'];

  public function user() {
    return $this->belongsTo(User::class);
  }

  public function category() {
    return $this->belongsTo(Category::class);
  }

  public function kids() {
    return $this->hasMany(Kid::class);
  }

  public function challenges() {
    return $this->hasMany(Challenge::class);
  }
}
