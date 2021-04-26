<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\loveone;
use App\Models\careteam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CareteamController extends Controller
{
    /**
     * 
     */
    public function index(Request $request)
    {
        $is_admin = false;
        $loveone  = loveone::whereSlug($request->loveone_slug)->first();
        if($loveone){
            
            $careteam = careteam::where('loveone_id', $loveone->id)->get()->keyBy('user_id');
            $membersIds = $careteam->pluck('user_id')->toArray();
            $members = User::whereIn('id', $membersIds)->get();

            foreach ($members as $key => $member){
                $members[$key]['careteam'] = $careteam[$member->id];
                if(Auth::user()->id == $member->id && $careteam[$member->id]->role == 'admin')
                    $is_admin = true;
            }

        } else {

            $loveone = null;
            $membersIds = null;
            $members = null;
        }

        return view('careteam.index', compact('careteam', 'loveone', 'members', 'is_admin'));
    }
}
