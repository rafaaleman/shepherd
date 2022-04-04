<?php

namespace App\Http\Controllers;

use App\User;
use DateTime;
use App\Models\Tourjs;
use App\Models\loveone;
use App\Models\careteam;
use App\Models\relationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $loveones = $this->getLoveones();
        Session::put('total_loveones', $loveones->count());
        $careteam_users = null;
        // dd($loveones->count());
        if($loveones->count() > 0){
            $date = new DateTime();
            return view('home', compact('loveones', 'careteam_users','date'));
        } else {
            return view('new');
        }

    }

    /**
     * 
     */
    public function newUser()
    {
        return view('new');
    }

    /**
     * Get my loveones
     */
    protected function getLoveones($status = '')
    {
        if($status == 'all'){
            $careteams = careteam::where('user_id', Auth::user()->id)->get()->keyBy('loveone_id');
        } else {
            $careteams = careteam::where('user_id', Auth::user()->id)->where('status', 1)->get()->keyBy('loveone_id');
        }

        $loveoneIds = $careteams->pluck('loveone_id')->toArray();
        $loveones = loveone::whereIn('id', $loveoneIds)->get();
        
        foreach ($loveones as  $loveone) {
            
            $loveone->relationshipName = $this->getRelatioinshipName(Auth::user()->id, $loveone->id);
            $loveone->careteam = $careteams[$loveone->id];
            $loveone->members = $this->getCareteamData($loveone->id);

            $request1 = new \Illuminate\Http\Request();
            $request1->replace([
                'loveone_slug' => $loveone->slug,
                'date' => date('Y-m-d'),
                'type' => 1,
                'id'   => Auth::user()->id,
            ]);

            $loveone->carepoints  = app('App\Http\Controllers\EventController')->getEvents($request1)->getData();
            $loveone->discussions = app('App\Http\Controllers\DiscussionController')->getDiscussions($request1)->getData();
            $loveone->lockbox     = app('App\Http\Controllers\LockboxController')->countDocuments($request1)->getData();
            $loveone->medlist     = app('App\Http\Controllers\MedlistController')->getMedications($request1)->getData();
            $loveone->messages    = app('App\Http\Controllers\MessagesController')->lastMessages($request1)->getData();
            $loveone->resources   = app('App\Http\Controllers\ResourceController')->getTopicsCarehub($request1);
        }
        // dd($loveones[0]->resources);
        return $loveones;
    }

    /**
     * 
     */
    public function getRelatioinshipName($user_id, $loveone_id)
    {
        $relationship = relationship::select('name')->where('id', function($query) use($user_id, $loveone_id){
                            $query->select('relationship_id')
                            ->from(with(new careteam)->getTable())
                            ->where('user_id', $user_id)->where('loveone_id', $loveone_id);
                        })->first();

        return $relationship->name;
    }


    /**
     * 
     */
    public function getCareteamData($loveone_id)
    {
        $users = User::whereIn('id', function($query) use($loveone_id){
                            $query->select('user_id')
                            ->from(with(new careteam)->getTable())
                            ->where('loveone_id', $loveone_id )->where('status', 1);
                        })->get();

        // Create initials avatar if no photo
        foreach ($users as $user) {
            if(empty($user->photo))
                asset(create_avatar($user,true));
        }

        return $users;
    }

    /**
     * 
     */
    public function profile()
    {
        $loveones = $this->getLoveones('all');
        return view('profile', compact('loveones'));
    }

    /**
     * 
     */
    public function  readTour(Request $request)
    {
        Tourjs::create([
            'user_id'=> Auth::user()->id,
            'section_name' => $request->section_name
        ]);
    }
}
