<?php

namespace App\Http\Controllers;

use App\Source;

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
}
