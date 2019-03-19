<?php

namespace App\Http\Controllers;

use App\Source;
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
