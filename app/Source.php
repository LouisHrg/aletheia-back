<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
  const EDITIONS = [
    'en-us-ny',
    'en-us-sf',
    'en-gb',
    'fr-fr',
    'fr-be',
    'nl-be',
    'de-de'
  ];

  protected $fillable = [
      'idOzae', 'name', 'edition', 'score'
  ];

    public function articles()
  {
      return $this->hasMany(\App\Article::class, 'article', 'id');
  }



}
