<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Models\careteam;
use App\Models\Invitation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['updateUser', 'showRegistrationForm2']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:50'],
            'phone'    => ['required', 'string', 'max:20'],
            'dob'      => ['date'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'lastname' => $data['lastname'],
            'phone'    => $data['phone'],
            'dob'      => $data['dob-year'].'-'.$data['dob-month'].'-'.$data['dob-day'],
            'email'    => $data['email'],
            'photo'    => (isset($data['photo'])) ? $data['photo'] : '/images/no-avatar.png',
            'status'   => 1,
            'company'  => ($data['company']) ?: '',
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Show the application registration form from email receiving possible "company" variable
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm(Request $request)
    {
        $company = (!empty($request->company))? $request->company : '';
        return view('auth.register', compact('company'));
    }

    /**
     * Show the application registration form from email invitation
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm2(Request $request)
    {
        $token = $request->token;
        $invitation = Invitation::whereToken($token)->first();

        // Session::flush();
        Auth::logout();

        if($invitation){
            $email = $invitation->email;
            return view('auth.register', compact('email', 'token'));
        } else {
            return view('auth.register');

        }
    }

    /**
     * 
     */
    public function acceptInvitation($user_id, $token)
    {
        $invitation = Invitation::whereToken($token)->first();
        
        if($invitation){
            $careteam = [
                'loveone_id' => $invitation->loveone_id,
                'user_id' => $user_id,
                'relationship_id' => $invitation->relationship_id,
                'role' => $invitation->role,
                'status' => 1,
                'permissions' => $invitation->permissions,
            ];
            
            careteam::create($careteam);
            Invitation::whereToken($token)->delete();
        }
    }

    /**
     * Update user
     */
    public function updateUser(Request $request)
    {
        $data = $request->all();
        $user = json_decode($data['user']);

        $data = [
            'name'     => $user->name,
            'lastname' => $user->lastname,
            'phone'    => $user->phone,
            'email'    => $user->email,
        ];
        

        if(!empty($user->password) && $user->password == $user->password_confirmation){
            $data['password'] = Hash::make($user->password);
        }

        $photo = '';
        if($user->photo){

            $prefix = str_replace('@', '_at_', $user->email);
            $photoName = $prefix.'.'.$request->file->getClientOriginalExtension();
            $request->file->move(public_path('members/'), $photoName);
            $photo = '/members/'.$photoName;

            $data['photo'] = $photo;
        }

        // dd($data);
        try {
            
            User::where('id', Auth()->user()->id )->update($data);
            return response()->json(['success' => true, 'photo' => $photo]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }

    }
}
