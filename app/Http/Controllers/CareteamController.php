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
use App\Mail\sendNewPermissionsMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Http\Traits\NotificationTrait;
use Illuminate\Support\Facades\Session;

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
        $section       = 'careteam';
        return view('careteam.index', compact('loveone', 'loveone_slug', 'roles', 'relationships', 'section'));
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
            $member->photo = ($member->photo != '') ? asset($member->photo) :  asset(create_avatar($member,true));
            $members[$key]['careteam'] = $careteam[$member->id];
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
     * Show create/edit careteam member
     */
    public function createNewMember(Request $request)
    {
        $loveone  = loveone::whereSlug($request->loveone_slug)->first();
        if(!$loveone) return view('errors.not-found');

        $section       = 'careteam';
        $roles         = self::ROLES;
        $relationships = relationship::where('status', 1)->get();
        
        return view('careteam.create', compact('loveone', 'roles', 'relationships', 'section'));
    }

    /**
     * Edit member
     */
    public function editMember(Request $request)
    {
        $loveone  = loveone::whereSlug($request->loveone_slug)->with('careteam')->first();
        if(!$loveone) return view('errors.not-found');

        $member = User::with('permissions')->find($request->member_id);
        if(!$member) return view('errors.not-found');

        if(!$loveone->careteam->contains('user_id', $request->member_id))return view('errors.not-found');

        $member->permissions->permissions = unserialize($member->permissions->permissions);
        // dd($member);
        $section       = 'careteam';
        $roles         = self::ROLES;
        $relationships = relationship::where('status', 1)->get();
        
        return view('careteam.edit', compact('loveone', 'roles', 'relationships', 'section', 'member'));
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
            
            if(!$request->file){
                $member['photo'] = asset(create_avatar($user,true));
            }
            $this->createCareteamRow($user->id, $loveone_id, $relationship_id, $role_id);

            // TODO: SEnd email to new user with the credentials;
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
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
        $new_permissions = [
            'carehub' => intval($request->permissions['carehub']),
            'lockbox' => intval($request->permissions['lockbox']),
            'medlist' => intval($request->permissions['medlist']),
            'resources' => intval($request->permissions['resources']),
        ];

        // dd($new_permissions);
        try {
            if($request->loveone_id && $request->id){

                $old_permissions = careteam::where('loveone_id', $request->loveone_id)->where('user_id', $request->id)->first()->permissions;
                $old_permissions = unserialize($old_permissions);

                $res = careteam::where('loveone_id', $request->loveone_id)->where('user_id', $request->id)->update(['permissions' => serialize($new_permissions)]);

                $this->sendNewCareteamPermissionsEmail($request->loveone_id, $request->id, $old_permissions, $new_permissions);
                return response()->json(['success' => true]);
            }else {
                return response()->json(['success' => false]);
            }

        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'error' => $ex->getMessage()]);
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

        } catch (\Exception $e) {
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
                'inviter' => Auth::user()->name . ' '. Auth::user()->last_name,
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
    public function declineInvitation(Request $request)
    {   
        try {
            Invitation::whereToken($request->token)->delete();
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

        } catch (\Exception $e) {
            // dd($e);
            return response()->json(['success' => false]);
        }
    }

    /**
     * Send an email to the careteam member only if was granted a new permission(s)
     */
    protected function sendNewCareteamPermissionsEmail($loveone_id, $user_id, $old_permissions, $new_permissions)
    {
        $permissions = [] ;
        if($old_permissions['carehub'] == 0 && $new_permissions['carehub'] == 1) $permissions[] = 'CareHub';
        if($old_permissions['lockbox'] == 0 && $new_permissions['lockbox'] == 1) $permissions[] = 'Lockbox';
        if($old_permissions['medlist'] == 0 && $new_permissions['medlist'] == 1) $permissions[] = 'Medlist';
        if($old_permissions['resources'] == 0 && $new_permissions['resources'] == 1) $permissions[] = 'Resources';

        if(count($permissions) > 0){

            $user = User::find($user_id);
            $loveone = loveone::find($loveone_id);
            $permissions_str = implode(', ', $permissions);
            $email['permissions_str'] = $permissions_str;
            $email['loveone_name'] = $loveone->firstname;
            $email['loveone_photo'] = $loveone->photo;
            Mail::to($user->email)->send(new sendNewPermissionsMail($email));
        }
    }
}
