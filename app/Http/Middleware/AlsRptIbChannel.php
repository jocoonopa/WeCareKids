<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class AlsRptIbChannel
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
         * The channel found by public_key,
         * 
         * @var \App\Model\AlsRptIbChannel
         */
        $channel = $request->als_rpt_ib_channel;
        
        if (is_null($channel)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if (!$channel->isValid()) {
            abort(Response::HTTP_FORBIDDEN, '頻道目前不開放或已經截止');
        }

        if (!$channel->isPublicKeyValid($request->get('public_key'))) {
            abort(Response::HTTP_FORBIDDEN, '公鑰驗證不合法');
        }

        return $next($request);
    }
}
