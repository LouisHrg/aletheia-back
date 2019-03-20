<?php

namespace App\Http\Controllers;

use App\Source;
use GuzzleHttp\Client;

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
                [ 'name' => $sourceData->name, 'edition' => $edition, 'score' => rand(0, 100) ]
            );
            }
        }

        return response('ok !');
    }
}
