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
        $section  = 'resources';
        if(!$loveone){
            return view('errors.not-found');
        }
        // Produce: <body text='black'>
        $conditions = str_replace(",", " OR ", $loveone->conditions);
        $response = $client->request('GET', 'https://newsapi.org/v2/everything?q='.$conditions.'&language=en&from='.date('Y-m-d').'&apiKey='.$this->apikey);
        $conditions_loveone = explode(',',$loveone->conditions);
        //$response = $client->request('GET', "https://newsapi.org/v2/top-headlines?q=(litecoin||covid)&language=en&apiKey=".$this->apikey);
        //https://newsapi.org/v2/everything?q=(litecoin||covid)&apiKey=3c4aff2abc6c40edbc69320556dd35e4
        $topics = get_object_vars(json_decode($response->getBody()));
        
        //dd($topics);
        return view('resources.index',compact('topics','conditions_loveone','section'));

    }

    public function getTopicsCarehub(Request $request){

        $client = new Client();
        $loveone  = loveone::whereSlug($request->loveone_slug)->first();
        if(!$loveone){
            return 0;
        }
        // Produce: <body text='black'>
        $conditions = str_replace(",", " OR ", $loveone->conditions);
        if(empty($conditions)) $conditions = 'health';
        $url = 'https://newsapi.org/v2/everything?q='.$conditions.'&from='.date('Y-m-d').'&language=en&apiKey='.$this->apikey;
        $response = $client->request('GET', $url);
        $conditions_loveone = explode(',',$loveone->conditions);
        
        $topics = get_object_vars(json_decode($response->getBody()));
        // dd($topics);
        return $topics;
    }
    
    // general query to API, no longer used
    public function getTopicsSearch(Client $client, Request $request){
        //dd($_POST);// keyword
        $response = $client->request('GET', 'https://newsapi.org/v2/top-headlines?q='.$request->keyword.'&language=en&apiKey='.$this->apikey);
        $topics = get_object_vars(json_decode($response->getBody()));
       // dd($topics);
        return response()->json(['success' => true, 'topics' => $topics]);
    }

    // general query to API, no longer used
    public function getTopicsSearchIni(Client $client, Request $request){
        //dd($_POST);// keyword
        $response = $client->request('GET', "https://newsapi.org/v2/top-headlines?language=en&apiKey=".$this->apikey);

        $topics = get_object_vars(json_decode($response->getBody()));
        //dd($topics);
        return response()->json(['success' => true, 'topics' => $topics]);
    }
}
