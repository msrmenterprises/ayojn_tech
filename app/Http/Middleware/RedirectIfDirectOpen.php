<?php

namespace App\Http\Middleware;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfDirectOpen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(Auth::user()){
            $user_id = Auth::user()->id;
            $user = User::find($user_id);
            if($user->userstatus == 1){
                return $next($request);
            }
            else{
                abort(403, 'No Access');
            }
        }
      
    }
}
