<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
  protected $fillable = [
      'value'
  ];

  public function articles()
  {
      return $this->hasMany(\App\Article::class, 'article', 'id');
  }
}
