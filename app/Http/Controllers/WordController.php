<?php

namespace App\Http\Controllers;

use App\Word;
use App\Article;
use GuzzleHttp\Client;

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

    public function fetch()
    {
        $http = new Client();

        $res = $http->request('GET', 'https://api.ozae.com/gnw/ngrams?date=20190103__20190109&limit=20&key='.env('OZAE_API_KEY'));
        $data = json_decode($res->getBody());

        for($i = 0; $i < 10; $i ++ ){
           $word = Word::updateOrCreate(
                [ 'value' => $data->ngrams[$i]->ngram ],
                [ ]
            );
            for($j = 0; $j < 10; $j ++ ){
                $_res = $http->request('GET', 'https://api.ozae.com/gnw/article/'.$data->ngrams[$i]->articles_ids[$j].'?key='.env('OZAE_API_KEY'));
                $_data = json_decode($_res->getBody());
                Article::updateOrCreate(
                    [ 'idOzae' => $_data->id ],
                    [ 'url' => $_data->url, 'source' => $_data->source->domain, 'title' => $_data->name, 'idOzae' => $_data->id, 'word_id' => $word->id ]
                );
            }
        }

        return response('ok !');
    }
}
