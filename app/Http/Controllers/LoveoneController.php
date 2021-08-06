<?php

namespace App\Http\Controllers;

use App\Models\loveone;
use App\Models\careteam;
use App\Models\condition;
use Illuminate\Support\Str;
use App\Models\relationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\CreateLoveoneRequest;

class LoveoneController extends Controller
{
   

    /**
     * Shows the create loveone form
     */
    public function index()
    {
        $relationships = relationship::where('status', 1)->get();
        $conditions    = condition::where('status', 1)->get();
        return view('loveone.create', compact('relationships', 'conditions'));
    }

    /**
     * 
     */
    public function setLoveone(Request $request)
    {
        $loveone  = loveone::find($request->id);

        if($loveone){
            return response()->json(['loveone' => $loveone]);
        } else {
            return response()->json(['loveone' => null]);
        }
    }

    /**
     * Creates a loveone register
     */
    public function createUpdate(Request $request)
    {
        $data = $request->all();
        $data = json_decode($data['loveone']);
        $data = collect($data)->toArray();
        // dd($data);

        $data['conditions'] = implode(',', $data['conditions']);
        $relationship_id = $data['relationship_id'];
        $data['phone']   = intval($data['phone']);
        
        
        unset($data['_token']);
        unset($data['relationship_id']);
        unset($data['photo']);

        // CREATE
        if($data['id'] == 0){
            // Verify if exists
            $loveone = loveone::where('email', $data['email'])->orWhere('phone', $data['phone'])->first();
            // dd($loveone);
            if($loveone){
                return response()->json(['success' => false, 'error' => 'This Loveone already exists (email/phone). Please verify.']);
            }
            // dd($data);
            $data['slug']    = Str::slug($data['firstname'].' '.$data['lastname']);
            $loveone = loveone::create($data);
            $this->createCareteam(Auth::user()->id, $loveone->id, $relationship_id);

            if($request->file){

                $prefix = $data['phone'];
                $photoName = $prefix.'.'.$request->file->getClientOriginalExtension();
                $request->file->move(public_path('loveones/'.$loveone->id.'/'), $photoName);
                $data['photo'] = '/loveones/'.$loveone->id.'/'.$photoName;
                loveone::where('id', $loveone->id)->update(['photo' => $data['photo']]);
            }
            $loveone_id = $loveone->id;

        } else {

            // Verify if exists
            $loveone = loveone::find($data['id']);
            // dd($loveone);
            if(!$loveone){
                return response()->json(['success' => false, 'error' => "This Loveone doesn't exists. Please verify."]);
            }

            loveone::where('id', $data['id'])->update($data);
            careteam::where('loveone_id', $loveone->id)->where('user_id', Auth::user()->id)->update(['relationship_id' => $relationship_id]);

            if($request->file){

                $prefix = $data['phone'];
                $photoName = $prefix.'.'.$request->file->getClientOriginalExtension();
                $request->file->move(public_path('loveones/'.$loveone->id.'/'), $photoName);
                $data['photo'] = '/loveones/'.$loveone->id.'/'.$photoName;
                loveone::where('id', $data['id'])->update(['photo' => $data['photo']]);
            }
            $loveone_id = $data['id'];
        }
        $loveone = loveone::find($loveone_id);

        // if ($request->ajax()) 
        return response()->json(['success' => true, 'data' => ['loveone' => $loveone]]);
    }

    /**
     * Shows the edit loveone form
     */
    public function edit(Request $request)
    {
        $loveone  = loveone::whereSlug($request->loveone_slug)->first();

        if(!$loveone){
            return view('errors.404');
        }

        $careteam = careteam::where('loveone_id', $loveone->id)->where('user_id', Auth::user()->id)->first();
        if($careteam->role != 'admin'){
            return view('errors.404');
        }

        $loveone->careteam = $careteam;
        $relationships     = relationship::where('status', 1)->get();
        $conditions        = condition::where('status', 1)->get();

        return view('loveone.create', compact('relationships', 'conditions', 'loveone'));
    }

    /**
     * Create a careteam
     */
    protected function createCareteam($user_id, $loveone_id, $relationship_id)
    {

        $permissions = [
            'carehub' => 1,
            'lockbox' => 1,
            'medlist' => 1,
            'resources' => 1,
        ];

        $careteam = [
            'loveone_id' => $loveone_id,
            'user_id' => $user_id,
            'relationship_id' => $relationship_id,
            'role' => 'admin',
            'status' => 1,
            'permissions' => serialize($permissions),
        ];
        careteam::create($careteam);
    }
}
