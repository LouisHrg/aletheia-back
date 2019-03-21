<?php

namespace App\Http\Controllers;

use App\Word;
use App\Source;
use App\Article;

use Illuminate\Http\Request;

class WordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        $words = Word::paginate(10);
        return response($words);
    }

    public function show($id)
    {
        $word = Word::findOrFail($id);
        return response($word);
    }
}
