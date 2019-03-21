<?php

namespace App\Http\Controllers;

use App\Word;
use App\Source;
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

        $res = $http->request('GET', 'https://api.ozae.com/gnw/ngrams?date=20190301__20190320&limit=20&key='.env('OZAE_API_KEY'));
        $data = json_decode($res->getBody());

        for ($i = 0; $i < 20; $i ++) {

            if(in_array($data->ngrams[$i]->ngram, Word::RESTRICTED)){
              continue;
            }

            $word = Word::updateOrCreate(
                [ 'value' => $data->ngrams[$i]->ngram ],
                [ ]
            );
            for ($j = 0; $j < 10; $j ++) {
                $_res = $http->request('GET', 'https://api.ozae.com/gnw/article/'.$data->ngrams[$i]->articles_ids[$j].'?key='.env('OZAE_API_KEY'));
                $_data = json_decode($_res->getBody());
                $source = Source::where('name', '=', $_data->source->domain)->first();
                if (!is_null($source)) {
                    Article::updateOrCreate(
                        [ 'idOzae' => $_data->id ],
                        [ 'url' => $_data->url, 'title' => $_data->name, 'idOzae' => $_data->id, 'source_id' => $source->id, 'word_id' => $word->id ]
                    );
                }
            }
        }

        return response('ok !');
    }
}
