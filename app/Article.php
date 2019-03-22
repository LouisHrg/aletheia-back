<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
  protected $fillable = [
      'url', 'image', 'title', 'content', 'idOzae', 'source_id', 'word_id', 'clickbait', 'trust', 'biased'
  ];

  public function word()
  {
      return $this->belongsTo(\App\Word::class, 'word_id');
  }

  public function source()
  {
      return $this->belongsTo(\App\Source::class, 'source_id');
  }

}
