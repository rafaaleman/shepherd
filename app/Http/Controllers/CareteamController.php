<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\loveone;
use App\Models\careteam;
use App\Models\Invitation;
use App\Models\relationship;
use Illuminate\Http\Request;
use App\Mail\sendJoinTeamMail;
use App\Mail\sendInvitationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

use App\Http\Traits\NotificationTrait;

class CareteamController extends Controller
{

    use NotificationTrait;
    
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
        $this->areNewNotifications($request->loveone_slug, Auth::user()->id);
        $loveone_slug = $request->loveone_slug;
        $loveone  = loveone::whereSlug($loveone_slug)->first();
        if(!$loveone){
            return view('errors.not-found');
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
        $is_admin = false;

        foreach ($members as $key => $member){
            $careteam[$member->id]->permissions = unserialize($careteam[$member->id]->permissions);
            $members[$key]['careteam'] = $careteam[$member->id];
            $member->photo = ($member->photo != '') ? asset($member->photo) :  asset('/img/avatar2.png');
            if(Auth::user()->id == $member->id && $careteam[$member->id]->role == 'admin')
                $is_admin = true;
        }

        $invitations = Invitation::where('loveone_id', $loveone->id)->get();

        return response()->json(['success' => true, 'data' => [
                                                        'loveone' => $loveone,
                                                        'careteam' => $careteam,
                                                        'members' => $members,
                                                        'invitations' => $invitations,
                                                        'is_admin' => $is_admin
                                                    ]]);
    }

    /**
     * Creates  a careteam member
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
            // dd($e);
        }
    }

    /**
     * 
     */
    protected function createCareteamRow($user_id, $loveone_id, $relationship_id, $role_id, $permissions = null)
    {
        if(!$permissions){
            $permissions = [
                'carehub' => 0,
                'lockbox' => 0,
                'medlist' => 0,
                'resources' => 0,
            ];
        }

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

    /**
     * Deletes a careteam member
     */
    public function deleteMember(Request $request)
    {
        try {
            careteam::where('loveone_id', $request->loveoneId)->where('user_id', $request->memberId)->delete();
            return response()->json(['success' => true]);

        } catch (Exception $e) {
            // dd($e);
            return response()->json(['success' => false]);
        }
    }

    /**
     * Search a member by ID
     */
    public function searchMember(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if($user){
            $alreadyInCareteam = careteam::where('loveone_id', $request->loveone_id)->where('user_id', $user->id)->first();
            if($alreadyInCareteam)
                return response()->json(['user' => 2]); // Already in careteam
            else
                return response()->json(['user' => $user]); // 
        } else {
            return response()->json(['user' => null]); // User doesnt exist
        }
    }

    /**
     * Admin user includes a new memberteam with permissions
     */
    public function inlcudeAMember(Request $request)
    {
        // dd($request->all());

        // $this->createCareteamRow($request->id, $request->loveone_id, $request->relationship_id, $request->role_id, $permissions);
        $invitation = Invitation::where('loveone_id', $request->loveone_id)->where('email', $request->email)->first();

        if(!$invitation){
            $permissions = [
                'carehub' => intval($request->permissions['carehub']),
                'lockbox' => intval($request->permissions['lockbox']),
                'medlist' => intval($request->permissions['medlist']),
                'resources' => intval($request->permissions['resources']),
            ];
            $token = $this->generateToken();
            $invitation = [
                'loveone_id' => $request->loveone_id,
                'email' => $request->email,
                'token' => $token,
                'role' => $request->role_id,
                'permissions' => serialize($permissions),
                'relationship_id' => $request->relationship_id,
            ];
            Invitation::create($invitation);
            $loveone = loveone::find($request->loveone_id);


            // send email
            $details = [
                'url' => route('careteam.joinTeam'), 
                'role' => $request->role_id,
                'loveone_name' => $loveone->firstname . ' ' . $loveone->lastname,
                'loveone_photo' => $loveone->photo,
                'leader' => Auth::user()->name . ' ' . Auth::user()->lastname,
            ];
    
            Mail::to($request->email)->send(new sendJoinTeamMail($details));
        }

        return response()->json(['success' => true]);
    }

    /**
     * Invite an external subject to a teamcare
     */
    public function sendInvitation(Request $request)
    {
        // dd($request->all());
        $invitation = Invitation::where('loveone_id', $request->loveone_id)->where('email', $request->email)->first();

        if(!$invitation){
            $token = $this->generateToken();
            $invitation = [
                'loveone_id' => $request->loveone_id,
                'email' => $request->email,
                'token' => $token,
                'role' => $request->role_id,
                'permissions' => serialize($request->permissions),
                'relationship_id' => $request->relationship_id,
            ];
            Invitation::create($invitation);
            $loveone = loveone::find($request->loveone_id);


            // send email
            $details = [
                'url' => route('register_invitation', $token),
                'role' => $request->role_id,
                'loveone_name' => $loveone->firstname . ' ' . $loveone->lastname,
                'loveone_photo' => $loveone->photo,
            ];
    
            Mail::to($request->email)->send(new sendInvitationMail($details));
        }

        return response()->json(['success' => true]);
    }

    /**
     * 
     */
    public function deleteInvitation(Request $request)
    {
        Invitation::find($request->invitationId)->delete();
        return response()->json(['success' => true]);
    }

    /**
     * 
     */
    public function joinTeam()
    {
        return view('careteam.joinTeam');
    }

    public function getInvitations(){

        $invitations = Invitation::where('email', Auth::user()->email)->get();
        $loveonesIds = $invitations->pluck('loveone_id')->toArray();
        $loveones = loveone::whereIn('id', $loveonesIds)->get()->keyBy('id');

        foreach ( $invitations as $invitation ) {
            $invitation->loveone = $loveones[$invitation->loveone_id];
        }

        return response()->json(['invitations' => $invitations]);
    }

    /**
     * 
     */
    public function acceptInvitation(Request $request)
    {   
        try {
            app('App\Http\Controllers\Auth\RegisterController')->acceptInvitation($request->user_id, $request->token);
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false]);
        }
    }

    /**
     * 
     */
    protected function generateToken(){
        $permitted_chars = '023456789abcdefghjkmnopqrstuvwxyz';
        $token = substr(str_shuffle($permitted_chars), 0, 20);
        return $token;
    }

    /**
     * Enable/Disable careteam
     */
    public function changeStatus(Request $request)
    {
        try {
            careteam::where('loveone_id', $request->loveoneId)->where('user_id', $request->userId)->update(['status' => $request->status]);
            return response()->json(['success' => true]);

        } catch (Exception $e) {
            // dd($e);
            return response()->json(['success' => false]);
        }
    }
}
