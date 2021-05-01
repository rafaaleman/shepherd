<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\loveone;
use App\Models\careteam;
use App\Models\relationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CareteamController extends Controller
{
    
    protected const ROLES = [
        'admin' => 'Careteam Leader', 
        'member' => 'Careteam Member', 
        'associate' => 'Careteam Associate', 
        'medical' => 'Medical', 
        'legal' => 'Legal', 
        'adviser' => 'Adviser', 
        'other' => 'Other',
    ];



    /**
     * 
     */
    public function index(Request $request)
    {
        $loveone_slug = $request->loveone_slug;
        $loveone  = loveone::whereSlug($loveone_slug)->first();
        if(!$loveone){
            // TODO: mostrar pag de error
        }

        $roles         = self::ROLES;
        $relationships = relationship::where('status', 1)->get();
        return view('careteam.index', compact('loveone', 'loveone_slug', 'roles', 'relationships'));
    }

    /**
     * 
     */
    public function getCareteamMembers(Request $request)
    {
        $loveone  = loveone::whereSlug($request->loveone_slug)->first();
        $careteam = careteam::where('loveone_id', $loveone->id)->get()->keyBy('user_id');
        $membersIds = $careteam->pluck('user_id')->toArray();
        $members = User::whereIn('id', $membersIds)->get();

        foreach ($members as $key => $member){
            $careteam[$member->id]->permissions = unserialize($careteam[$member->id]->permissions);
            $members[$key]['careteam'] = $careteam[$member->id];
            $member->photo = ($member->photo != '') ? env('APP_URL').'/public'.$member->photo :  asset('public/img/avatar2.png');
            if(Auth::user()->id == $member->id && $careteam[$member->id]->role == 'admin')
                $is_admin = true;
        }

        return response()->json(['success' => true, 'data' => [
                                                        'loveone' => $loveone,
                                                        'careteam' => $careteam,
                                                        'members' => $members,
                                                        'is_admin' => $is_admin
                                                    ]]);
    }

    /**
     * 
     */
    /**
     * Creates or update a careteam member
     */
    public function saveNewMember(Request $request)
    {
        $data = $request->all();
        $member = json_decode($data['member']);
        $member = collect($member)->toArray();
        // dd($member);

        $member['phone'] = intval($member['phone']);
        $role_id         = $member['role_id'];
        $relationship_id = $member['relationship_id'];
        $loveone_id      = $member['loveone_id'];

        $member['password'] = Hash::make($member['password']);
        $member['photo']   = '';
        $member['status']  = 1;
        
        unset($member['_token']);
        unset($member['role_id']);
        unset($member['relationship_id']);
        unset($member['loveone_id']);

        try {
            $user = User::where('email', $member['email'])->orWhere('phone', $member['phone'])->first();
            // dd($user);
            if($user){
                return response()->json(['success' => false, 'error' => 'This user already exists (email/phone). Please verify.']);
            }

            
            if($request->file){

                $prefix = str_replace('@', '__', $member['email']);
                $photoName = $prefix.'.'.$request->file->getClientOriginalExtension();
                $request->file->move(public_path('loveones/'.$loveone_id.'/members/'), $photoName);
                $member['photo'] = public_path('/loveones/'.$loveone_id.'/members/'.$photoName);
            }
            $user = User::create($member);
            $this->createCareteamRow($user->id, $loveone_id, $relationship_id, $role_id);

            // TODO: SEnd email to new user with the credentials;
            return response()->json(['success' => true]);

        } catch (Exception $e) {
            dd($e);
        }
    }

    /**
     * 
     */
    protected function createCareteamRow($user_id, $loveone_id, $relationship_id, $role_id)
    {
        $permissions = [
            'carehub' => 0,
            'lockbox' => 0,
            'medlist' => 0,
            'resources' => 0,
        ];

        $careteam = [
            'loveone_id' => $loveone_id,
            'user_id' => $user_id,
            'relationship_id' => $relationship_id,
            'role' => $role_id,
            'status' => 1,
            'permissions' => serialize($permissions),
        ];
        careteam::create($careteam);
    }

    /**
     * 
     */
    public function updateMemberPermissions(Request $request)
    {
        // dd($request->all());
        $permissions = [
            'carehub' => intval($request->permissions['carehub']),
            'lockbox' => intval($request->permissions['lockbox']),
            'medlist' => intval($request->permissions['medlist']),
            'resources' => intval($request->permissions['resources']),
        ];

        // dd($permissions);
        try {
            if($request->loveone_id && $request->id){
                $res = careteam::where('loveone_id', $request->loveone_id)->where('user_id', $request->id)->update(['permissions' => serialize($permissions)]);
                // TODO: SEnd email to new user with the new permissions;
                return response()->json(['success' => true]);
            }

        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th]);
        }
        
    }
}
