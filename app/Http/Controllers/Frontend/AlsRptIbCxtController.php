<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\CxtRequest;
use App\Model\AlsRptIbChannel;
use App\Model\AlsRptIbCxt;
use Carbon\Carbon;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SfResponse;

class AlsRptIbCxtController extends Controller
{
    /*
    |-------------------------------------------------------------------------
    | 這邊的權限檢查要移到 Middleware
    |--------------------------------------------------------------------------
    |
    | 目前沒時間所以暫時先寫在controller內, 之後要轉移出來
    |
    */
    public function __construct()
    {
        $this->middleware('access.rpt.channel')->only('index', 'auth');
        $this->middleware('edit.rpt.cxt')->only('update');
    }

    /**
     *
     * @param \App\Model\AlsRptIbChannel $channel
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(AlsRptIbChannel $channel, Request $request)
    {
        $privateKey = $request->cookie($channel->public_key);

        /**
         * 透過私鑰尚未提交的 AlsRptIbCxt
         * 
         * @var \App\Model\AlsRptIbCxt
         */
        $cxt = $channel->findNotSubmitCxtsByPrivateKey($privateKey)->first();

        // 若沒有尚未提交的 cxt, 導向登入頁準備新增cxt
        if (is_null($cxt)) {
            return redirect("/analysis/r/i/channel/{$channel->id}/cxt/auth?public_key={$channel->public_key}");
        }

        return view('frontend/als_rpt_ib_cxt/index', compact('cxt', 'privateKey'));
    }

    /**
     * 家長 Logout 頁面
     * 
     * @param  AlsRptIbChannel $channel
     * @return \Illuminate\Http\Response
     */
    public function logout(AlsRptIbChannel $channel)
    {
        return redirect("/analysis/r/i/channel/{$channel->id}/cxt/login")->withCookie(Cookie::forget($channel->public_key));
    }

    /**
     * 家長驗證頁面
     * 
     * @param  AlsRptIbChannel $channel
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function auth(AlsRptIbChannel $channel, Request $request)
    {
        $cxt = AlsRptIbCxt::createPrototype($channel);
        $cxt->save();

        $privateKey = $cxt->private_key;

        $response = new Response(view('frontend/als_rpt_ib_cxt/index', compact('cxt', 'privateKey')));

        return $response->withCookie(cookie($channel->public_key, $privateKey));
    }

    /**
     * 剖析報表填寫結束頁面
     * 
     * @param  AlsRptIbCxt $cxt
     * @return \Illuminate\Http\Response          
     */
    public function finish(AlsRptIbCxt $cxt)
    {
        return view('frontend/als_rpt_ib_cxt/finish', compact('cxt'));
    }

    public function show(Request $request, AlsRptIbCxt $cxt)
    {
        return redirect("/analysis/r/i/channel/{$cxt->channel->id}/cxt/login");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Model\AlsRptIbCxt $cxt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AlsRptIbCxt $cxt)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        return $this->_updatAjax($request, $cxt);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CxtRequest $request
     * @param  \App\Model\AlsRptIbCxt $cxt
     * @return \Illuminate\Http\Response
     */
    protected function submit(CxtRequest $request, AlsRptIbCxt $cxt)
    {
        try {
            $data = $request->all();
            $data['status'] = AlsRptIbCxt::STATUS_HAS_SUBMIT;
            $data['child_birthday'] = Carbon::instance(new \DateTime(array_get($data, 'child_birthday')));

            $cxt->update($data);

            return redirect("/analysis/r/i/cxt/{$cxt->id}/finish")->withCookie(Cookie::forget($cxt->channel->public_key));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    protected function _updatAjax(Request $request, AlsRptIbCxt $cxt)
    {
        try {
            $data = $request->all();
            $data['child_birthday'] = Carbon::instance(new \DateTime(array_get($data, 'child_birthday')));

            $cxt->update($data);
            
            return response()->json([
                'status' => SfResponse::HTTP_OK,
                'id' => $cxt->id,
                'msg' => 'success',
                'timestamp' => time()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => SfResponse::HTTP_INTERNAL_SERVER_ERROR,
                'id' => $cxt->id,
                'msg' => $e->getMessage(),
                'timestamp' => time()
            ]);
        }
    }
}
