<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserPower;

class SectionAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(Auth::guard($guard)->check()){
            $user_power = UserPower::where('user_id',auth()->user()->id)
                ->where('power_type','A')
                ->first();

            if (Auth::user()->group_id==8 or (!empty(auth()->user()->section_id) && !empty($user_power))) {
                return $next($request);
            }else{
                return redirect('/');
            }
        }else{
            return redirect()->route('glogin');
        }

    }
}
