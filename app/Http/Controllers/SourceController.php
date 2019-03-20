<?php

namespace App\Http\Controllers;

use App\Source;
use GuzzleHttp\Client;
use Goutte\Client as GoutteClient;

use Illuminate\Http\Request;

class SourceController extends Controller
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
        $sources = Source::paginate(10);
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

        $editions = Source::EDITIONS;

        foreach ($editions as $edition) {

        $res = $http->request('GET', 'https://api.ozae.com/gnw/sources?date=20180515__20180525&edition='.$edition.'&segment=domain&topic=_&key='.env('OZAE_API_KEY'));
        $data = json_decode($res->getBody());

        foreach ($data->sources as $sourceData) {
            Source::updateOrCreate(
                [ 'idOzae' => $sourceData->id ],
                [ 'name' => $sourceData->name, 'edition' => $edition ]
            );
        }

        }

        return response('ok !');
    }
}
