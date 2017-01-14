<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests;
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
        $this->middleware('access.rpt.channel')->only('index');
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

        // 若沒有私鑰, 導向登入頁準備產生私鑰
        if (is_null($privateKey)) {
            return redirect("/analysis/r/i/channel/{$channel->id}/cxt/login");
        }

        /**
         * 透過私鑰尚未提交的 AlsRptIbCxt
         * 
         * @var \App\Model\AlsRptIbCxt
         */
        $cxt = $channel->findNotSubmitCxts($request->cookie($channel->public_key))->first();

        // 若沒有尚未提交的 cxt, 導向登入頁準備新增cxt
        if (is_null($cxt)) {
            return redirect("/analysis/r/i/channel/{$channel->id}/cxt/login");
        }

        return view('frontend/als_rpt_ib_cxt/index', compact('cxt', 'privateKey'));
    }

    /**
     * 家長 Login 頁面
     * 
     * @param  AlsRptIbChannel $channel
     * @return \Illuminate\Http\Response
     */
    public function login(AlsRptIbChannel $channel)
    {
        return view('frontend/als_rpt_ib_cxt/login', compact('channel'));
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
        $validator = $this->validate($request, [
            'phone' => 'required|between:10,13',
        ]);

        if (!is_null($validator)) {
            return redirect("/analysis/r/i/channel/{$channel->id}/cxt/login")->withErrors($validator)->withInput();
        }
        
        $phone = $request->get('phone');

        /**
         * 透過電話尋找尚未提交的 AlsRptIbCxt
         * 
         * @var \App\Model\AlsRptIbCxt
         */
        $cxt = $channel->findNotSubmitCxtsByPhone($phone)->first();

        // 若cxt不存在, 產生一個新的cxt
        if (is_null($cxt)) {
            $cxt = AlsRptIbCxt::createPrototype($channel);
            $cxt->phone = $phone;
            $cxt->save();
        }

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
        return $request->ajax() ? $this->_updatAjax($request, $cxt) : $this->_update($request, $cxt);
    }

    protected function _update(Request $request, AlsRptIbCxt $cxt)
    {
        try {
            $data = $request->all();
            $data['status'] = AlsRptIbCxt::STATUS_HAS_SUBMIT;
            $data['child_birthday'] = Carbon::instance(new \DateTime(array_get($data, 'child_birthday')));

            $cxt->update($data);

            return redirect("/analysis/r/i/cxt/{$cxt->id}/finish");
        } catch (\Exception $e) {
            return redirect("/analysis/r/i/channel/{$cxt->id}/cxt")->with('error', $e->getMessage());
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
