<?php

namespace App\Http\Controllers\Backend;

use AlsRpt;
use App\Http\Requests;
use App\Model\AlsRptIbChannel;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AlsRptIbChannelController extends Controller
{
    const QRCODE_DEFAULT_SIZE = 350;

    /**
     * 取得使用者建立的所有 channels
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $channels = AlsRptIbChannel::findByCreater(Auth::user())->get();

        return view('backend/als_rpt_ib_channel/index', compact('channels'));
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
        | 不用新增頁面
        |--------------------------------------------------------------------------
        |
        | 對老師來說, 最方便的流程應該是在 @index 按下新增按鈕後
        | 直接 post @store 自動產生一個從今天開始為期七天有效的 channel,
        | 畫面重整後直接跳出該 channel 的 QRCode Modal.
        |
        */
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $channel = AlsRptIbChannel::createPrototype(Auth::user());
            $channel->save();

            return redirect('/backend/analysis/r/i/channel')->with('success', "新增成功: {$channel->id}");
        } catch (\Exception $e) {
            return redirect('/backend/analysis/r/i/channel')->with('error', "新增失敗: {$e->getMessage()}");
        }
    }

    /**
     * 展示呈現該 Channel 對外 QRCode
     *
     * @param  \App\Model\AlsRptIbChannel  $channel
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function qrcode(AlsRptIbChannel $channel, Request $request)
    {
        $size = $request->get('size', static::QRCODE_DEFAULT_SIZE);

        return view('backend/als_rpt_ib_channel/showQrCode', compact('channel', 'size'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\AlsRptIbChannel  $channel
     * @return \Illuminate\Http\Response
     */
    public function show(AlsRptIbChannel $channel)
    {
        $this->authorize('view', $channel);

        return view('backend/als_rpt_ib_channel/show', compact('channel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\AlsRptIbChannel  $channel
     * @return \Illuminate\Http\Response
     */
    public function edit(AlsRptIbChannel $channel)
    {
        return view('backend/als_rpt_ib_channel/edit', compact('channel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\AlsRptIbChannel  $channel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AlsRptIbChannel $channel)
    {
        $this->authorize('update', $channel);

        try {
            $channel->update([
                'is_open' => (bool) $request->get('is_open', false),
                'open_at' => Carbon::instance(new \DateTime($request->get('open_at', false))),
                'close_at' => Carbon::instance(new \DateTime($request->get('close_at', false)))
            ]);

            return redirect('/backend/analysis/r/i/channel')->with('success', "編輯成功: {$channel->id}");
        } catch (\Exception $e) {
            return redirect("/backend/analysis/r/i/channel/{$channel->id}/edit")->with('error', "編輯失敗: {$e->getMessage()}");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){}
}
