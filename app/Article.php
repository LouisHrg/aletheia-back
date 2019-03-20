<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
  protected $fillable = [
      'url', 'title', 'content', 'source', 'idOzae', 'word_id'
  ];

  public function word()
  {
      return $this->belongsTo(\App\Word::class, 'word_id');
  }
}
