<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Model\AlsRptIbChannel;
use App\Model\AlsRptIbCxt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SfResponse;

class AlsRptIbCxtController extends Controller
{
    /**
     * --------------------------------------------------------------------------
     * 家長掃描 QRCode 進入填寫問卷頁面 
     * --------------------------------------------------------------------------
     * 1. 首先判斷 public_key 可否找到對應 channel?
     *   1-a. 若無:
     *     @throw 404
     *     
     * ==========================================================================
     * 2. 檢查 channel 是否允許訪問?
     *   2-a. 若否:
     *     @throw 403
     *
     * ==========================================================================
     * 3. private_key(stored in cookie) 和 public_key 可否到一個 AlsRptIbCxt 實體? 
     *   2-a. 若有:
     *     @2-b-2
     *          
     *   2-b. 若無:
     *     1. 系統產生一個新的 AlsRptIbCxt 實體
     *     2. 產生私鑰, 儲存至 cookie
     *     3. 輸出頁面開始填寫問券
     * ==========================================================================
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /**
         * The channel found by public_key,
         * 
         * @var \App\Model\AlsRptIbChannel
         */
        $channel = $this->_findChannelOrThrownotFoundException($request->get('public_key'));
        
        $this->_isValid($channel);

        $cxt = $channel->cxts()->where('private_key', $request->cookie($channel->public_key))->first();

        if (is_null($cxt)) {
            $cxt = AlsRptIbCxt::createPrototype($channel);
            $cxt->save();
        }

        $privateKey = $cxt->private_key;

        $response = new Response(view('frontend/als_rpt_ib_cxt/index', compact('cxt', 'privateKey')));

        return $response->withCookie(cookie($channel->public_key, $privateKey));
    }

    /**
     * 透過公鑰取得 AlsRptIbChannel 實體, 若沒有找到則丟出 404 錯誤
     * 
     * @param  string $publicKey
     * @return mixed
     */
    private function _findChannelOrThrownotFoundException($publicKey)
    {
        $channel = AlsRptIbChannel::where('public_key', $publicKey)->first();

        if (is_null($channel)) {
            abort(404);
        }

        return $channel;
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
        $this
            ->_isPrivateKeyValid($cxt, $request->get('private_key'))
            ->_isValid($cxt->channel)
        ;

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

    protected function _isValid(AlsRptIbChannel $channel)
    {
        if (!$channel->isValid()) {
            abort('403');
        }

        return $this;
    }

    /**
     * 檢查私鑰是否合法, 若否則拋出 403 錯誤
     *
     * @param  \App\Model\AlsRptIbCxt $cxt
     * @param  string  $privateKey
     * @return mixed             
     */
    private function _isPrivateKeyValid(AlsRptIbCxt $cxt, $privateKey)
    {
        if (!$cxt->isPrivateKeyValid($privateKey)) {
            abort(403);
        }

        return $this;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){}
}
