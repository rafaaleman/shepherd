<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ResourceController extends Controller
{
    protected $apikey =  '3c4aff2abc6c40edbc69320556dd35e4';
    
    public function getTopics(Client $client){
        $response = $client->request('GET', "https://newsapi.org/v2/top-headlines?country=us&apiKey=".$this->apikey);
        $topics = get_object_vars(json_decode($response->getBody()));
        
        //dd($topics);
        return view('resources.index',compact('topics'));

    }
}
