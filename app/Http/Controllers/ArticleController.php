<?php

namespace App\Http\Controllers;

use App\Article;
use GuzzleHttp\Client;
use Goutte\Client as GoutteClient;

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
        $articles = Article::where('word_id', '=', $word_id)->paginate(30);
        return response($articles);
    }

    public function getArticleData(Request $request)
    {

        $url = $request->input('url');

        $client = new GoutteClient();

        $content = "";

        $crawler = $client->request('GET', $url);

        $data = $crawler->filter('p')->each(function ($node) {
            return $node->text()."\n";
        });

        foreach($data as $d){
            $content .= $d;
        }

        $http = new Client();

        $res = $http->request('GET', 'https://api.fakenewsdetector.org/votes_by_content?content='.substr($content, 300));
        $data = utf8_decode(($res->getBody()));

        return response($data);
    }

}
