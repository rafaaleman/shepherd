<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\NotificationTrait;
use Session;

use App\Models\messages;
use App\Models\loveone;
use App\Models\careteam;
use App\User;


class MessagesController extends Controller
{
    public function prueba()
    {
        //
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $loveone_slug = $request->loveone_slug;
        $loveone  = loveone::whereSlug($loveone_slug)->first();

        if(!$loveone){
           // dd("no existe");
        }
        return view('messages.index',compact('loveone','loveone_slug'));
    }

}
