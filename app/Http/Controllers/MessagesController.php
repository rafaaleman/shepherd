<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\NotificationTrait;
use App\Events\NewMessage;
use Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendNewMessageMail;
use App\Models\loveone;
use App\Models\careteam;
use App\Models\chat;
use App\Models\chat_message;
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
        return response()->json(['success' => true, 'data' => ['chats' => $data,'count_chats' => count($data)]], 200);
    }


    /* */
    public function getChat($id)
    {
        $chat = chat_message::where('id_chat',$id)->get();
        return response()->json(['success' => true, 'data' => ['chat' => $chat]], 200);
    }
    
    /* */
    public function newChat(Request $request)
    {
        $loveone_slug = $request->loveone_slug;
        $loveone  = loveone::whereSlug($loveone_slug)->first();
        
        $chat = new chat;
        $chat->loveone_id = $loveone->id;
        $chat->sender_id = Auth::id();
        $chat->receiver_id =  $request->user_id;
        $chat->status = 1;
        
        $chat->save();
        return response()->json(['success' => true, 'data' => ['chat' => $chat]], 200);
    }
    /* */
    public function storeMessage(Request $request){

        $validatedData = $request->validate([
            'user_id'     => 'required|numeric',
            'chat_id'     => 'required|numeric',
            'message'     => 'required|max:250',
        ]);
        $msg = new chat_message;
        $msg->id_user = $request->user_id; 
        $msg->id_chat = $request->chat_id; 
        $msg->message = $request->message; 
        $msg->status  = 1;
        $msg->save();

        if($request->urgent == "true"){
            $user = User::find($request->user_id);
            $email['message'] = $msg->message;
            /*
            dd($user);
            $email['loveone_name'] = $loveone->firstname;
            $email['loveone_photo'] = $loveone->photo;
            */
            $email['from_name'] = $user->name . ' ' . $user->lastname;
            $email['from_photo'] = $user->photo;
            Mail::to($user->email)->send(new sendNewMessageMail($email));
            
        }

        
        $chat = chat::find($request->chat_id);
        $chat->last_message = $request->message; 
        $chat->status  = 1;
        $chat->save();
        
        broadcast(new NewMessage($msg));

        $data = chat_message::where('id_chat',$request->chat_id)->get();

        return response()->json(['success' => true, 'data' => ['chat' => $data]], 200);
    }



    /* Messages */
    public function getMessages(Request $request)
    {
        $loveone_slug = $request->loveone_slug;
        $loveone  = loveone::whereSlug($loveone_slug)->first();
        $messages = chat::where('id',$request->id);
        return response()->json(['success' => true, 'data' => ['messages' => $messages]], 200);
    }

    public function deleteMessage($id){
        $data = chat_message::find($id);
        $data->delete();
        return response()->json(['success' => true, 'data' => ['message' => 'deleted']], 200);
    }

    public function deleteChat($id){
        $data = chat::find($id);
        $x = chat_message::where('id_chat',$id)->get();
        foreach($x as $y){
            $y->delete();
        }
        $data->delete();
        
        return response()->json(['success' => true, 'data' => ['message' => 'deleted']], 200);
    }

    public function lastMessages(Request $request){
        $resp=array();
        $x =0;
        $loveone_slug = $request->loveone_slug;
        $user_id = $request->user_id;
        
        $loveone  = loveone::whereSlug($loveone_slug)->first();


        $chats = chat::where('loveone_id',$loveone->id)
                    ->where('sender_id',$user_id)
                    ->orWhere('receiver_id',$user_id)
                    ->get();

                    
        if($chats->count()==0){
            $resp['num_message']  = 0;
            $resp['last_message'] = "";
            return response()->json(['success' => true, 'data' => $resp], 200);
        }
        foreach($chats as $c){
            $resp['last_message'] = " ";
            if($c->sender_id != $user_id){
                $usr = User::find($c->sender_id);
                $resp['last_message'] = $usr->name . ' ' . $usr->lastname;
            }
            $x++;
            $resp['num_message'] = $x;
            
        }
        if($resp['last_message'] == " " && $x > 0){
            
            $id = $chats[$x-1]->id;

            $chats = chat_message::where('id_chat',$id)->get();
            foreach($chats as $c){
                $usr = User::find($c->id_user);
                $resp['last_message'] = $usr->name . ' ' . $usr->lastname;
            }
            
        }
        return response()->json(['success' => true, 'data' => $resp], 200);
        //$x = chat_message::where('id_chat',$id)->get();
    }
}
