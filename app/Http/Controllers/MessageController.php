<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\message;
use App\Models\message_discussion;

use DateTime;

class MessageController extends Controller
{
    
    public function createMessage(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        unset($data['assigned']);
        $date = new DateTime();
        $data['date'] = $date->format('Y-m-d');
        $data['time']  = $date->format('h:i:s');
        $data['status'] = 1;
        //dd($data,Auth::user());
       // dd($data);
        $message = message::create($data);
        $message->time_cad_gi = $date->format('g:i');
        $message->time_cad_a = $date->format('a');
        $message->photo = (Auth::user()->photo != '') ? asset(Auth::user()->photo) :  asset('img/avatar2.png');
        $message->name = Auth::user()->name. ' ' . Auth::user()->lastname;
        // if ($request->ajax()) 
        return response()->json(['success' => true, 'message' => $message]);
    }

    public function createMessageDiscussion(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        unset($data['assigned']);
        $date = new DateTime();
        $data['date'] = $date->format('Y-m-d');
        $data['time']  = $date->format('h:i:s');
        $data['status'] = 1;
        //dd($data,Auth::user());
       // dd($data);
        $message = message_discussion::create($data);
        $message->time_cad_gi = $date->format('g:i');
        $message->time_cad_a = $date->format('a');
        $message->photo = (Auth::user()->photo != '') ? asset(Auth::user()->photo) :  asset('img/avatar2.png');
        $message->name = Auth::user()->name. ' ' . Auth::user()->lastname;
        // if ($request->ajax()) 
        return response()->json(['success' => true, 'message' => $message]);
    }
}
