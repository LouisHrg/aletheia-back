<?php

namespace App\Http\Controllers;

use App\Article;
use GuzzleHttp\Client;

use Illuminate\Http\Request;

class ArticleController extends Controller
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
        $articles = Article::paginate(10);
        return response($articles);
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);
        return response($article);
    }

    public function fetchByWord($word_id)
    {
        $http = new Client();

        $articles = Article::where('word_id', '=', $word_id)->paginate(10);

        return response($articles);
    }

}
