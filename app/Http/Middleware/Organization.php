<?php

namespace App\Http\Middleware;

use App\Model\Organization as Org;
use App\Model\User;
use Auth;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class Organization
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
        if (!$this->isAllowed(Auth::user(), $request->organization)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }

    protected function isAllowed(User $user, Org $org)
    {
        return !(!$user->isSuper() && !$org->isOwner($user));
    }
}
