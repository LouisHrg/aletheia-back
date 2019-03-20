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

    public function showContent($idOzae)
    {

        $http = new Client();

        $res = $http->request('GET', 'https://api.ozae.com/gnw/article/'.$idOzae.'/html_content?key='.env('OZAE_API_KEY'));
        
        return response($res->getBody());
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

        $res = $http->request('GET', 'https://api.fakenewsdetector.org/votes_by_content?content='.substr($content, 0, 350));
        $data = $res->getBody();

        $data = json_decode($data);

        $fakenews = intval(100-intval($data->robot->fake_news*100));

        $response = [
            'fakenews' => $fakenews,
            'clickbait' => intval($data->robot->clickbait*100),
            'biased' => intval($data->robot->extremely_biased*100),
        ];

        return response($response);
    }

}
