<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckRoles
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
        $roles = array_slice(func_get_args(), 2);

        //dd(Auth::user());
        if(Auth::user()->hasRole($roles)){
            return $next($request);
        }

        return redirect('/');

    }
}
