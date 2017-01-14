<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class AmtAlsRpt
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
        /**
         * @var \App\Model\AmtAlsRpt
         */
        $report = $request->amt_als_rpt;

        if (is_null($report->replica)) { // Should be move to middleware
            abort(Response::HTTP_FORBIDDEN, '此报告没有包含任何资料!');
        }

        return $next($request);
    }
}