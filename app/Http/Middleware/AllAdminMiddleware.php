<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserPower;
use Illuminate\Support\Facades\Auth;

class AllAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user_power = UserPower::where('user_id',auth()->user()->id)
                ->where('power_type','A')
                ->first();

            if(auth()->user()->group_id==9 or auth()->user()->group_id==8 or auth()->user()->admin==1 or (!empty(auth()->user()->section_id) and !empty($user_power))){
                return $next($request);
            }else{
                return redirect('/');
            }
        }else{
            return redirect('glogin');
        }
    }
}
