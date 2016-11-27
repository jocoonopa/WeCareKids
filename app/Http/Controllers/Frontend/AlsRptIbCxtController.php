<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Model\AlsRptIbChannel;
use App\Model\AlsRptIbCxt;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AlsRptIbCxtController extends Controller
{
    /**
     * --------------------------------------------------------------------------
     * 家長掃描 QRCode 進入填寫問卷頁面 
     * --------------------------------------------------------------------------
     * 1. 首先判斷 public_key 可否找到對應 channel?
     *   1-a. 若無:
     *     @return 顯示 404
     * --------------------------------------------------------------------------
     * 2. private_key(stored in cookie) 和 public_key 可否到一個 AlsRptIbCxt 實體? 
     *   2-a. 若有:
     *     2-a-1: 則檢查該 AlsRptIbCxt 狀態為何:
     *       2-a-1-a: 狀態為尚未提交:
     *         @2-b-2
     *          
     * ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||             
     *   2-b. 若無:
     *     1. 家長填寫個人資訊和小孩資訊[系統產生 private key, set COOKIE]
     *     2. 填寫問券
     *     3. 提交[AlsRptIbCxt實體狀態改為已經提交]
     *     4. 顯示提交成功回饋訊息
     *     
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $publicKey = $request->get('public_key');
        $privateKey = $request->cookie('private_key');
        $channel = AlsRptIbChannel::where('public_key', $publicKey)->first();

        if (is_null($channel)) {
            abort(404);
        }

        $cxt = $channel->cxts()->where('private_key', $privateKey)->first();
      
        if (is_null($cxt)) {
            $cxt = AlsRptIbCxt::createPrototype($channel);
            $cxt->save();

            $privateKey = $cxt->private_key;
        }

        $response = new Response(view('backend/als_rpt_cxt/index', compact('cxt')));

        return $response->withCookie(cookie('private_key', $privateKey));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
