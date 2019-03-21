<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{

  const RESTRICTED = [
    'Facebook',
    'Twitter',
    'France',
    'Français',
    'français',
    'AFP',
    'afp',
  ];

  protected $fillable = [
      'value'
  ];

  public function articles()
  {
      return $this->hasMany(\App\Article::class, 'article', 'id');
  }
}
