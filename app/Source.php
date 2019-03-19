<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
  const EDITIONS = [
    'en-us-ny',
    'en-us-df',
    'en-gb',
    'fr-fr',
    'fr-be',
    'nl-be',
    'de-de'
  ];

  protected $fillable = [
      'idOzae'
  ];
}
