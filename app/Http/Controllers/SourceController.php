<?php

namespace App\Http\Controllers;

use App\Source;

use GuzzleHttp\Client;
use Goutte\Client as GoutteClient;

use Illuminate\Http\Request;

class SourceController extends Controller
{
    public function index(Request $request)
    {
        $sorting = [];

        if (!is_null($request->input('sort')) && !is_null($request->input('order'))) {
            $sorting = [
                'sort' => $request->input('sort'),
                'order' => $request->input('order')
            ];
        }

        $sources = Source::when($sorting, function ($query, $sorting) {
            return $query->orderBy($sorting['sort'], $sorting['order']);
        })->paginate(13);

        return response($sources);
    }

    public function show($id)
    {
        $source = Source::findOrFail($id);
        return response($source);
    }

    public function store(Request $request)
    {
        $source = new Source();
        dd($request);

        return response('Berhasil Tambah Data');
    }

    public function content($idOzae)

    {
        $http = new Client();

        $res = $http->request('GET', 'https://api.ozae.com/gnw/article/'.$idOzae.'/html_content?key='.env('OZAE_API_KEY'));
        $data = utf8_decode(($res->getBody()));

        return response($data);
    }

    public function getContentArticle($url)
    {
        $client = new GoutteClient();

        $content = "";

        $crawler = $client->request('GET', $url);

        $data = $crawler->filter('p')->each(function ($node) {
            return $node->text()."\n";
        });

        foreach($data as $d){
            $content .= $d;
        }

        return response($content);
    }

    public function fetch(Request $request)
    {
        $http = new Client();

        $res = $http->request('GET', 'https://api.ozae.com/gnw/article/'.$idOzae.'/html_content?key='.env('OZAE_API_KEY'));
        $data = utf8_decode(($res->getBody()));

        return response($data);
    }
}
