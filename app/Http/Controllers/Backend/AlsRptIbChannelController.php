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
        $channels = AlsRptIbChannel::simplePaginate(10);
        //$channels = AlsRptIbChannel::findByCreater(Auth::user())->get();

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
        | 不用新增页面
        |--------------------------------------------------------------------------
        |
        | 对老师来说, 最方便的流程应该是在 @index 按下新增按钮后
        | 直接 post @store 自动产生一个从今天开始为期七天有效的 channel,
        | 画面重整后直接跳出该 channel 的 QRCode Modal.
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
            return redirect('/backend/analysis/r/i/channel')->with('error', "新增失败: {$e->getMessage()}");
        }
    }

    /**
     * 展示呈现该 Channel 对外 QRCode
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
        //$this->authorize('view', $channel);

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
        //$this->authorize('update', $channel);

        try {
            $channel->update([
                'is_open' => (bool) $request->get('is_open', false),
                'open_at' => Carbon::instance(new \DateTime($request->get('open_at', false))),
                'close_at' => Carbon::instance(new \DateTime($request->get('close_at', false)))
            ]);

            return redirect('/backend/analysis/r/i/channel')->with('success', "编辑成功: {$channel->id}");
        } catch (\Exception $e) {
            return redirect("/backend/analysis/r/i/channel/{$channel->id}/edit")->with('error', "编辑失败: {$e->getMessage()}");
        }
    }

    /**
     * Update the specified channel is_open 
     *
     * @param  \App\Model\AlsRptIbChannel  $channel
     * @return \Illuminate\Http\Response
     */
    public function toggleOpen(AlsRptIbChannel $channel)
    {
        $channel->is_open = !$channel->is_open;
        $channel->save();

        $message = $channel->is_open ? '頻道已開啟' : '頻道已關閉';

        return redirect("/backend/user/{$channel->creater->id}/edit")->with('success', $message); 
    }
}
