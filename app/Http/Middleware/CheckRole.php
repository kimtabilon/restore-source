<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role1, $role2 = false)
    {

        $user_role = $request->user()->role()->first()->name;
        
        if ($user_role != $role1 && $user_role != $role2) {
            // Redirect...
            return redirect('dashboard');
            // return response('Unauthorised!', 401);
        }

        return $next($request);
    }
}
