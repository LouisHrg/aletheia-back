<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
  protected $fillable = [
      'url', 'title', 'content', 'idOzae', 'source_id', 'word_id'
  ];

  public function word()
  {
      return $this->belongsTo(\App\Word::class, 'word_id');
  }
}
