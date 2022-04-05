<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Models\loveone;
use App\Models\careteam;
use App\Models\discussion;
use App\Models\chat;
use App\Models\chat_message;
use App\Mail\sendNewMessageMail;
use App\Events\NewMessage;
use App\Http\Traits\NotificationTrait;


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
        $selected_discussions = $request->discussions; 
        $loveone_slug = $request->loveone_slug;
        $loveone  = loveone::whereSlug($loveone_slug)->first();
        $discussions = self::discussions($loveone_slug,Auth::id());
        $careteam     = self::careteam($request->loveone_slug);
        if(!$loveone){
           // dd("no existe");
        }
        $section = 'discussions';
        return view('discussion.index',compact('loveone','loveone_slug','section','discussions','careteam'));

    }
    
    public function create(Request $request)
    {
        $loveone_slug = $request->loveone_slug;
        $loveone      = loveone::whereSlug($loveone_slug)->first();
        $careteam     = self::careteam($request->loveone_slug);

        if(!$loveone){
           // dd("no existe");
        }
        $section = 'discussions';
        
        return view('discussion.create',compact('loveone','loveone_slug','section','careteam'));

    }

    public function store(Request $request)
    {
        
        $data = $request->all();
        
        $loveone_slug = $request->loveone_slug;
        $loveone  = loveone::whereSlug($loveone_slug)->first();
        
        $discussion                 = new discussion;
        $discussion->loveone_id     = $loveone->id;
        $discussion->owner_id       = Auth::id();
        $discussion->name           = $data["name"];
        $discussion->last_message   = $data["message"];
        $discussion->users          = json_encode($data["users"]);
        $discussion->status         = 1;
        $discussion->save();
        
        $msg          = new chat_message;
        $msg->id_user = Auth::id();
        $msg->id_chat = $discussion->id;
        $msg->message = $data["message"];
        $msg->status  = 1;
        $msg->save();

        return response()->json(['success' => true, 'data' => ['discussion' => $discussion]], 200);
    }

    public function getCareteam(Request $request)
    {
        $careteam = self::careteam($request->loveone_slug);
        return response()->json(['success' => true, 'data' => ['careteam' => $careteam]], 200);
    }

    /* Chats */
    public function getDiscussions(Request $request)
    {
        $loveone_slug = $request->loveone_slug;
        $data = self::discussions($loveone_slug,Auth::id());

        return response()->json(['success' => true, 'data' => ['discussions' => $data,'count_discussions' => count($data)]], 200);
    }

    /* */
    public function getChat($id)
    {
        $messages = chat_message::where('id_chat',$id)->get();
        return response()->json(['success' => true, 'data' => ['messages' => $messages]], 200);
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

        if($request->urgent == "true" || $request->urgent == "1"){
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

        
        $discussion = discussion::find($request->chat_id);
        $discussion->last_message = $request->message; 
        $discussion->new_message  = 1;
        $discussion->save();
        
        broadcast(new NewMessage($msg));

        $data = chat_message::where('id_chat',$request->chat_id)->get();

        return response()->json(['success' => true, 'data' => ['chat' => $data]], 200);
    }







    /** Internas */

    protected function careteam($slug){
        $users = array();        
        $loveone  = loveone::whereSlug($slug)->first();        
        $careteam = careteam::where('loveone_id',$loveone->id)->get();
        foreach($careteam as $u){
            $user = $u->user;
            if($user->id != Auth::id()){
                $users[] =array('id'=>$user->id,'name'=> $user->name . ' ' . $user->lastname,'photo' => $user->photo, 'status' => $user->status,'selected' => false);
            }
        }
        return $users;
    }

    protected function discussions($slug,$user){
        $data = array();
        $loveone  = loveone::whereSlug($slug)->first();
        $chats = discussion::where('loveone_id',$loveone->id)->get();
       
        foreach($chats as $c){
            $us = explode(',',json_decode($c->users));
            $c->users = $us;
            if($c->owner_id == $user){
                $data[] = $c;
            }else{
                if (in_array($user, $us)) {
                    $data[] = $c;
                }
            }
        }
        return $data;
           
    }





















    #########################################################################################################################################################################################

    


    
    

    


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
