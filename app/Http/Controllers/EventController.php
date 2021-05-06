<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateEventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\event;
use App\Models\loveone;
use App\Models\careteam;
use App\User;




class EventController extends Controller
{
    public function index(Request $request)
    {   
        $is_admin = false;
        $loveone  = loveone::whereSlug($request->loveone_slug)->first();
        if($loveone){
            
            $careteam = careteam::where('loveone_id', $loveone->id)->get()->keyBy('user_id');
            $membersIds = $careteam->pluck('user_id')->toArray();
            $members = User::whereIn('id', $membersIds)->get();
            $events = event::where('loveone_id', $loveone->id)->get();
            foreach ($members as $key => $member){
                $members[$key]['careteam'] = $careteam[$member->id];
                if(Auth::user()->id == $member->id && $careteam[$member->id]->role == 'admin')
                    $is_admin = true;
            }

        } else {

            $loveone = null;
            $membersIds = null;
            $members = null;
            $events = null;
        }
        //dd($loveone);

        return view('carehub.index',compact('events','careteam', 'loveone', 'members', 'is_admin'));
    }

    public function createForm($loveone_slug){
        $loveone  = loveone::whereSlug($loveone_slug)->first();
        $careteam = careteam::where('loveone_id', $loveone->id)->with(['user'])->get()->keyBy('user_id');
        //dd($careteam);
        return view('carehub.create_event',compact('loveone','careteam'));
    }

    public function createUpdate(Request $request)
    {
        $data = $request->all();
        dd($data);
        //$data['assigned_ids'] = implode(',', $request->assigned);
        //$relationship_id = $data['relationship_id'];
        //$data['phone']   = intval($data['phone']);
       // $data['slug']    = Str::slug($data['firstname'].' '.$data['lastname'].' '.time());
        $data['assigned_ids'] = implode(',',$request->assigned);
        unset($data['_token']);
        unset($data['assigned']);
        //unset($data['relationship_id']);

        // edit
        if($request->id > 0)
            $event = event::update($data);
        // create
        else{
            $event = event::create($data);
            //$this->createCareteam(Auth::user()->id, $loveone->id, $relationship_id);
        }

        // if ($request->ajax()) 
        return response()->json(['success' => true, 'data' => ['event' => $event]]);
    }
}
