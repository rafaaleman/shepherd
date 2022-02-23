<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


use App\User;
use App\Mail\sendJoinMail;


class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $details['url'] = route('home');
        $details['email'] = "rene.ortizg@gmail.com";
        $details['pwd'] = "544546546";
        Mail::to("rene.ortizg@gmail.com")->send(new sendJoinMail($details));
        return response()->json("ok", 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {


        Log::channel('shepherd')->info($request);
        $response = array('message' => '', 'success'=>false);

        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'lastname' => 'required',
            'phone' => 'required',
            'dob' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        $name     = $request->input('name');
        $lastname = $request->input('lastname');
        $phone    = $request->input('phone', '');
        $dob      = $request->input('dob');
        $email    = $request->input('email');
        $company  = $request->input('company','454');
        $photo    = '/images/avatar2.png';
        $password = $request->input('password');

        if($validation->fails()){
            $response['message'] = "Error" ;
            $response['errors'] = $validation->messages();
        }else{
            $usr = User::where('email',$request->email)->first();
            if($usr){

                $response['message'] = "Error" ;
                $response['errors'] = "email registered";
            }else{
                $usr = User::create([
                    'name'     => $name,
                    'lastname' => $lastname,
                    'phone'    => $phone,
                    'dob'      => $dob,
                    'email'    => $email,
                    'photo'    => $photo,
                    'status'   => 1,
                    'guard'    => 1,
                    'company'  => $company,
                    'password' => Hash::make($password)
                ]);

                $response['message'] = "Successfully created user!" ;
                $response['success'] = true;
                $response['x'] = $usr;
                $response['pwd'] = $password;
                $response['errors'] = $validation->messages();


                $details['url'] = route('home');
                $details['email'] = $email;
                $details['pwd'] = $password;
                Mail::to($email)->send(new sendJoinMail($details));
            }
        }


        Log::channel('shepherd')->info($response);
        return response()->json($response, 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\lockbox_types  $lockbox_types
     * @return \Illuminate\Http\Response
     */
    public function show(lockbox_types $lockbox_types)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\lockbox_types  $lockbox_types
     * @return \Illuminate\Http\Response
     */
    public function edit(lockbox_types $lockbox_types)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\lockbox_types  $lockbox_types
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, lockbox_types $lockbox_types)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\lockbox_types  $lockbox_types
     * @return \Illuminate\Http\Response
     */
    public function destroy(lockbox_types $lockbox_types)
    {
        //
    }
}
