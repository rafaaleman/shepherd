<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\loveone;

class ResourceController extends Controller
{
    protected $apikey =  '3c4aff2abc6c40edbc69320556dd35e4';
    
    public function getTopics(Client $client,Request $request){
        $loveone  = loveone::whereSlug($request->loveone_slug)->first();
        if(!$loveone){
            return view('errors.not-found');
        }
        // Produce: <body text='black'>
        $conditions = str_replace(",", " OR ", $loveone->conditions);
        $response = $client->request('GET', 'https://newsapi.org/v2/everything?q='.$conditions.'&apiKey='.$this->apikey);
        $conditions_loveone = explode(',',$loveone->conditions);
        //$response = $client->request('GET', "https://newsapi.org/v2/top-headlines?q=(litecoin||covid)&country=us&apiKey=".$this->apikey);
        //https://newsapi.org/v2/everything?q=(litecoin||covid)&apiKey=3c4aff2abc6c40edbc69320556dd35e4
        $topics = get_object_vars(json_decode($response->getBody()));
        
        //dd($topics);
        return view('resources.index',compact('topics','conditions_loveone'));

    }
    
    // general query to API, no longer used
    public function getTopicsSearch(Client $client, Request $request){
        //dd($_POST);// keyword
        $response = $client->request('GET', 'https://newsapi.org/v2/top-headlines?q='.$request->keyword.'&apiKey='.$this->apikey);
        $topics = get_object_vars(json_decode($response->getBody()));
        //dd($topics);
        return response()->json(['success' => true, 'topics' => $topics]);
    }

    // general query to API, no longer used
    public function getTopicsSearchIni(Client $client, Request $request){
        //dd($_POST);// keyword
        $response = $client->request('GET', "https://newsapi.org/v2/top-headlines?country=us&apiKey=".$this->apikey);

        $topics = get_object_vars(json_decode($response->getBody()));
        //dd($topics);
        return response()->json(['success' => true, 'topics' => $topics]);
    }
}
