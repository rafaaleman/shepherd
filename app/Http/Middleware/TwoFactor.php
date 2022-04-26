<?php

namespace App\Http\Middleware;

use Closure;

class TwoFactor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if($user != null){ 
           // dd(auth(),$user,$request->is('verify*'));
          // dd( $user,$request->is('verify*'), $user->two_factor_code,$user->two_factor_expires_at,'....',now());

            if(auth()->check() && $user->two_factor_code)
            {
                if($user->two_factor_expires_at->lt(now()))
                {
                    $user->resetTwoFactorCode();
                    auth()->logout();
                    return redirect()->route('login')->with('mensaje', 'The login code has expired. Please login again');
                }

                if(!$request->is('verify*'))
                {
                    
                    return redirect()->route('verify.index');
                }
            }
        }
        return $next($request);
    }
}
