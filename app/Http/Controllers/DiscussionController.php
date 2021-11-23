<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\discussion;
use App\Models\loveone;
use App\Models\Invitation;
use App\Models\careteam;
use DateTime;
use DateInterval;

use App\Http\Traits\NotificationTrait;

class DiscussionController extends Controller
{
    use NotificationTrait;
    //use Notification;

    const DISCUSSIONS_TABLE = 'discussions';

    public function createForm($loveone_slug){
        $loveone  = loveone::whereSlug($loveone_slug)->first();
        $careteam = careteam::where('loveone_id', $loveone->id)->with(['user'])->get()->keyBy('user_id');
        $date_now = new DateTime();
        $date_now->sub(new DateInterval('P1D'));
        //dd($careteam);
        return view('carehub.create_discussion',compact('loveone','careteam','date_now'));
    }

    public function createUpdate(Request $request)
    {
        $data = $request->all();
        $data['creator_id'] = Auth::user()->id;
        $assigned_ids = $data['assigned'];
        $data['assigned_ids'] = json_encode($data['assigned']);
        
        unset($data['_token']);
        //dd($data);
        // edit
        if($request->id > 0)
            $discussion = discussion::where('id',$request->id)->update($data);
        // create
        else{
            unset($data['id']);
            //dd($data);
            $discussion = discussion::create($data);
            
            foreach($assigned_ids as $user_id){
                $notification = [
                    'user_id'    => $user_id,
                    'loveone_id' => $request->loveone_id,
                    'table'      => self::DISCUSSIONS_TABLE,
                    'table_id'   => $discussion->id,
                    'event_date' => $discussion->created_at
                ];
                $this->createNotification($notification);

            }



        }

        $discussion->assigned = json_decode($discussion->assigned_ids);
  
        return response()->json(['success' => true, 'data' => ['discussion' => $discussion]]);
    }

    
    public function getDiscussions(Request $request)
    {
        $loveone  = loveone::whereSlug($request->loveone_slug)->first();
        $careteam = careteam::where('loveone_id', $loveone->id)->with(['user'])->get();
        foreach ($careteam as $key => $team){
            // dd($team);
             if(isset($team->user)){
                 $team->user->photo = ($team->user->photo != '') ? asset($team->user->photo) :  asset('img/avatar2.png');
                 if(Auth::user()->id == $team->user_id && $team->role == 'admin')
                     $is_admin = true;
             }
            
         }
      //  $invitations = Invitation::where('loveone_id', $loveone->id)->get();
        $discussions = discussion::where('loveone_id', $loveone->id)
        ->where('status',1)
        ->orderBy("created_at")
        ->with(['messages'])
        ->get();
   
        foreach($discussions as $discussion){
            $discussion->members = $careteam->whereIn('user_id',json_decode($discussion->assigned_ids));
            
        }
        
        
       // dd($events);
        $date = new DateTime($request->date);
        return response()->json(['success' => true, 'data' => [
            'discussions' => $discussions,
        ]]);
    }


    public function archiveDiscussion(Request $request){
        //dd($request->id);
        discussion::find($request->id)->update(['status' => 0]);
        return response()->json(['success' => true]);
    }


    public function getDiscussion(Request $request)
    {

        $discussion = discussion::where('id', $request->id)->with('messages','creator')->first();
        $loveone  = loveone::whereSlug($request->slug)->first();
        $careteam = careteam::where('loveone_id', $loveone->id)->with(['user'])->get();
        $is_careteam = false;
        $id_careteam = 0;
        foreach ($careteam as $key => $team){
            if(isset($team->user)){
                $team->user->photo = ($team->user->photo != '') ? $team->user->photo :  asset('img/avatar2.png');
            }
        }

        foreach ($careteam as $key => $team){
            if(isset($team->user)){
                $team->user->photo = ($team->user->photo != '') ? $team->user->photo :  asset('img/avatar2.png');
            }
        }

        $discussion->members = $careteam->whereIn('user_id',json_decode($discussion->assigned_ids));
        foreach($discussion->members as $member){
            if(Auth::user()->id == $member->user_id ){
                $is_careteam = true;
                $id_careteam = $member->id;
            }
        }

        $date_temp = new DateTime($discussion->date . " " . $discussion->time);
        
        $discussion->time_cad_gi = $date_temp->format('g:i');
        $discussion->time_cad_a = $date_temp->format('a');
        $discussion->date_title = $date_temp->format('l, m.j.Y');
        
        //dd($discussion->messages);
        foreach ($discussion->messages as $key => $message){
            $date_temp_m = new DateTime($message->date . " " . $message->time);

            $date_now = new DateTime();
            $interval = $date_temp_m->diff($date_now);
           // dump($date_temp, $date_now, $interval,);

            if($interval->format('%H') == 0){
                $discussion->messages[$key]->date_title_msj = $interval->i .'m ago';
            }else if($interval->format('%H') > 0 && $interval->format('%H') < 24){
                $discussion->messages[$key]->date_title_msj = $interval->format('%H h %i m') .' ago';
            }else{
                $discussion->messages[$key]->date_title_msj = $date_temp_m->format('j M Y');
            }

            $discussion->messages[$key]->time_cad_gi = $date_temp_m->format('g:i');
            $discussion->messages[$key]->time_cad_a = $date_temp_m->format('a');
            $discussion->messages[$key]->date_title = $date_temp_m->format('j M Y');
            $discussion->messages[$key]->creator_img = ($message->creator->user->photo != '') ? $message->creator->user->photo :  asset('img/avatar2.png');
            
         }
         $discussion->creator->photo = ($discussion->creator->photo != '') ? $discussion->creator->photo :  asset('img/avatar2.png');
         //dd();
        // dd($event);
        return view('carehub.discussion_detail',compact('discussion','is_careteam','id_careteam'));

    }

}
