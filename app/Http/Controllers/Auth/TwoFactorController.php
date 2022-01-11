<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\TwoFactorCode;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    public function __construct()
    {
        //two factor activo
        //$this->middleware(['auth', 'two_factor']);
        $this->middleware(['auth']);
    }

    public function index() 
    {
        if(auth()->user()->two_factor_code == null)
        {
            return redirect()->route('home');
        }
        return view('auth.two_factor');
    }

    public function store(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'integer|required',
        ],[
            'two_factor_code.integer' => 'The login code must be an integer!',
            'two_factor_code.required' => 'The login code is required!'
        ]);

        $user = auth()->user();

        if($request->input('two_factor_code') == $user->two_factor_code)
        {
            $user->resetTwoFactorCode();

            return redirect()->route('home');
        }

        return redirect()->back()->withErrors(['two_factor_code' => 'The login code you have entered does not match']);
    }

    public function resend()
    {
        $user = auth()->user();
        $user->generateTwoFactorCode();
        $user->notify(new TwoFactorCode());

        return redirect()->back()->withMessage('The login code has been sent again');
    }
}
