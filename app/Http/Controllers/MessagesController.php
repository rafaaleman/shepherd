<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\NotificationTrait;
use Session;


use App\Models\loveone;
use App\Models\careteam;
use App\Models\chat;
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

    public function getCareteam(Request $request)
    {
        $users = array();
        $loveone_slug = $request->loveone_slug;
        $loveone  = loveone::whereSlug($loveone_slug)->first();
        
        $careteam = careteam::where('loveone_id',$loveone->id)->get();
        foreach($careteam as $u){
            $user = $u->user;
            if($user->id != Auth::id()){
                $users[] =array('id'=>$user->id,'name'=> $user->name . ' ' . $user->lastname,'photo' => $user->photo, 'status' => $user->status);
            }
        }
        return response()->json(['success' => true, 'data' => ['careteam' => $users]], 200);
    }

    /* Chats */
    public function getChats(Request $request)
    {
        $data = array();
        $loveone_slug = $request->loveone_slug;
        $loveone  = loveone::whereSlug($loveone_slug)->first();

        $chats = chat::where('loveone_id',$loveone->id)
                                ->where('sender_id',Auth::id())
                                ->orWhere('receiver_id',Auth::id())->get();
        foreach($chats as $c){
            if($c->sender_id == Auth::id()){
                $c->user = User::find($c->receiver_id);
            }else{
                $c->user = User::find($c->sender_id);
            }
            $data[] = $c;
        }
        return response()->json(['success' => true, 'data' => ['chats' => $data]], 200);
    }





    /* Messages */
    public function getMessages(Request $request)
    {
        $loveone_slug = $request->loveone_slug;
        $loveone  = loveone::whereSlug($loveone_slug)->first();
        $messages = chat::where('id',$request->id);
        return response()->json(['success' => true, 'data' => ['messages' => $messages]], 200);
    }
}