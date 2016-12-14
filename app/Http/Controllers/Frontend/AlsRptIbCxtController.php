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
        /* 
        |--------------------------------------------------------------------------
        | 家長掃描 QRCode 進入填寫問卷頁面 
        |--------------------------------------------------------------------------
        |  1. 首先判斷 public_key 可否找到對應 channel?
        |    1-a. 若無:
        |      @throw 404
        |    
        |==========================================================================
        |  2. 檢查 channel 是否允許訪問?
        |    2-a. 若否:
        |      @throw 403
        |
        |==========================================================================
        |  3. private_key(stored in cookie) 和 public_key 可否到一個 AlsRptIbCxt 實體? 
        |    2-a. 若有:
        |      @2-b-2
        |         
        |    2-b. 若無:
        |      1. 系統產生一個新的 AlsRptIbCxt 實體
        |      2. 產生私鑰, 儲存至 cookie
        |      3. 輸出頁面開始填寫問券
        |==========================================================================
        */
        $privateKey = $request->cookie($channel->public_key);

        if (is_null($privateKey)) {
            return redirect("/analysis/r/i/channel/{$channel->id}/cxt/login");
        }

        $cxt = $channel->cxts()->where('private_key', $request->cookie($channel->public_key))->first();

        if (is_null($cxt)) {
            return redirect("/analysis/r/i/channel/{$channel->id}/cxt/login");
        }

        return view('frontend/als_rpt_ib_cxt/index', compact('cxt', 'privateKey'));
    }

    public function login(AlsRptIbChannel $channel)
    {
        return view('frontend/als_rpt_ib_cxt/login', compact('channel'));
    }

    public function logout(AlsRptIbChannel $channel)
    {
        return redirect("/analysis/r/i/channel/{$channel->id}/cxt/login")->withCookie(Cookie::forget($channel->public_key));
    }

    public function auth(AlsRptIbChannel $channel, Request $request)
    {
        $validator = $this->validate($request, [
            'phone' => 'required|between:10,13',
        ]);

        if (!is_null($validator)) {
            return redirect("/analysis/r/i/channel/{$channel->id}/cxt/login")->withErrors($validator)->withInput();
        }
        
        $phone = $request->get('phone');

        $cxt = $channel->cxts()
            ->whereNotNull('phone')
            ->where('phone', $phone)
            ->first()
        ;

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Model\AlsRptIbCxt $cxt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AlsRptIbCxt $cxt)
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
