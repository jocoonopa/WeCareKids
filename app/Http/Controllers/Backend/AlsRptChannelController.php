<?php

namespace App\Http\Controllers\Backend;

use AlsRpt;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Model\AlsRptIbChannel;
use Illuminate\Http\Request;

class AlsRptChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $channels = AlsRptIbChannel::all();

        return view('backend/als_rpt_channel/index', compact('channels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | 其實可以不用新增頁面
        |--------------------------------------------------------------------------
        |
        | 對老師來說, 最方便的流程應該是在 @index 按下新增按鈕後
        | 直接 post @store 自動產生一個從今天開始為期七天有效的 channel,
        | 畫面重整後直接跳出該 channel 的 QRCode Modal.
        |
        */
        return view('backend/als_rpt_channel/create');
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
