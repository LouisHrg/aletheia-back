<?php

namespace App\Http\Controllers;

class ApiController extends Controller
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

    public function getKey(){
        return env('OZAE_API_KEY');
    }
}
