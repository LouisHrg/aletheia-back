<?php

namespace App\Http\Controllers;

use App\Source;
use GuzzleHttp\Client;

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
        $sources = Source::all();


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

    public function fetch()
    {
        $http = new Client();

        $res = $http->request('GET', 'https://api.ozae.com/gnw/sources?date=20180515__20180525&edition=en-us-ny&segment=domain&topic=_&key='.env('OZAE_API_KEY'));
        $data = json_decode($res->getBody());

        foreach ($data->sources as $sourceData) {
            $source = new Source();
            $source->name = $sourceData->name;
            $source->idOzea = $sourceData->id;
            $source->save();
        }

        return response('ok !');
    }
}
