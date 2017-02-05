<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class SuperUser
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
        $user = Auth::user();

        if (!$user->isSuper()) {
            abort(403);
        }

        return $next($request);
    }
}
