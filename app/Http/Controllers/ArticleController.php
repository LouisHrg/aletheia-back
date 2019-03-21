<?php

namespace App\Http\Controllers;

use App\Source;
use App\Article;
use GuzzleHttp\Client;
use Goutte\Client as GoutteClient;
use App\Helpers\DateRange;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

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
        $url = urlencode($request->input('url'));

        $http = new Client();

        $res = $http->request('GET', 'https://api.diffbot.com/v3/article?token=c0554deda254dc2d9400abf9b2b2674a&url='.$url);
        $data = $res->getBody();


        $data = json_decode($data)->objects;


        $content = $data['0']->text;
        $title = $data['0']->title;
        $country = strtolower($data['0']->publisherCountry);

        $res = $http->request('GET', 'https://api.fakenewsdetector.org/votes?url='.$url.'&title=article');
        $data = $res->getBody();



        $data = json_decode($data);

        $fakenews = intval(100-intval($data->robot->fake_news*100));

        $response = [
            'contry' => $country,
            'title' => $title,
            'title' => $title,
            'fakenews' => $fakenews,
            'clickbait' => intval($data->robot->clickbait*100),
            'biased' => intval($data->robot->extremely_biased*100)
        ];

        //dd(intval($response['fakenews']) * intval($response['biased']));

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


}
