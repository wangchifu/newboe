<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SchoolSignMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(auth()->check()){
            $user_power = check_b_user(auth()->user()->code,auth()->user()->id);

            if (Auth::guard($guard)->check() && Auth::user()->group_id==1 && $user_power) {
		return $next($request);
            }else{
                return redirect('/');
            }
        }else{
            return redirect()->route('glogin');
        }

    }
}
