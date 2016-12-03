<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class AlsRptIbCxt
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
         * @var \App\Model\AlsRptIbCxt
         */
        $cxt = $request->als_rpt_ib_cxt;

        /**
         * The channel found by public_key,
         * 
         * @var \App\Model\AlsRptIbChannel
         */
        $channel = $cxt->channel;

        if (!$channel->isValid()) {
            abort(Response::HTTP_FORBIDDEN, '頻道目前不開放或已經截止');
        }

        if (!$cxt->isPrivateKeyValid($request->get('private_key'))) {
            abort(Response::HTTP_FORBIDDEN, '私鑰驗證不合法');
        }

        return $next($request);
    }
}
