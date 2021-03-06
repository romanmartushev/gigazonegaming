<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class SentinelAdminUser
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
        $user = Sentinel::getUser();
        $admin = Sentinel::findRoleByName('Admin');

        if (!$user->inRole($admin)) {
            return redirect('/');
        }
        return $next($request);
    }
}
