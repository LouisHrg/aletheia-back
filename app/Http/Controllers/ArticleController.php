<?php

namespace App\Http\Controllers;

use App\Source;
use App\Article;
use GuzzleHttp\Client;
use App\Helpers\DateRange;
use Illuminate\Http\Request;
use App\Helpers\ArticleHelper;

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
        $articles = Article::where('word_id', '=', $word_id)->orderBy('trust','DESC')->paginate(30);
        return response($articles);
    }

    public function getArticleData(Request $request)
    {
        $url = urlencode($request->input('url'));
        $article = Article::where('url', 'LIKE', '%'.$url.'%')->first();
        if ($article === null) {
            $response = ArticleHelper::check($url);
            if(isset($response['source'])){
                $source = Source::where('name', 'LIKE', '%'.$response['source'].'%')->first();
                $sourceScore = $source->score;
            }else{
                $sourceScore = 100;
            }
            $content = isset($response['content']) ? $response['content'] : null;
            $score = ArticleHelper::calculateScore(
                $response['biased'],
                $response['fakenews'],
                $response['clickbait'],
                $content,
                $sourceScore
            );
            if($response['lang'] !== 'en'){
                $score = intval($score*1.30);
            }
            $response['score'] = $score;
            $article = new Article();
            $article->source_id = $source->id;
            $article->title = $response['title'];
            $article->url = urlencode($url);
            $article->lang = $response['lang'];
            $article->biased = $response['biased'];
            $article->score = $score;
            $article->trust = $response['fakenews'];
            $article->clickbait = $response['clickbait'];
            $article->save();
        } else {
            $response = [
                'biased' => $article->biased,
                'fakenews' => $article->trust,
                'clickbait' => $article->clickbait,
                'lang' => $article->lang,
                'score' => $article->score,
            ];
        }

        return response($response);
    }

    public function fetchByQuery($query, $date)
    {
        $http = new Client();

        $dateRange = DateRange::getDateRange($date);

        $res = $http->request('GET', 'https://api.ozae.com/gnw/articles?query='.$query.'&date='.$dateRange.'&key='.env('OZAE_API_KEY').'&hard_limit=50&order[col]=score');
        $data = json_decode($res->getBody());

        return response($data->articles);
    }

    public function manualTest()
    {
        $http = new Client();

        $articles = Article::all();

        foreach ($articles as $article) {
            if ($article->trust === 0) {
                $check = ArticleHelper::check(urlencode($article->url));
                $article->biased = $check['biased'];
                $article->trust = $check['fakenews'];
                $article->clickbait = $check['clickbait'];
                $article->save();
                echo $article->id.' saved';
            }
        }
    }
}
