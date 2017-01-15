<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class AmtDiagGroup
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
        $amt = $request->amt;
        $group = $request->amt_diag_group;

        if ($amt->id !== $group->amt->id) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }
}
