<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\AlsRptIbCxt;
use Auth;

class AlsRptIbCxtController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('can:view,als_rpt_ib_cxt')->only('show');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\AlsRptIbCxt
     * @return \Illuminate\Http\Response
     */
    public function show(AlsRptIbCxt $cxt)
    {
        $privateKey = $cxt->private_key;
        
        return view('frontend/als_rpt_ib_cxt/index', compact('cxt', 'privateKey'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cxts = AlsRptIbCxt::where('status', '<>', AlsRptIbCxt::STATUS_HASNOT_SUBMIT)->latest()->paginate(env('PERPAGE_COUNT', 50));
        $channel = Auth::user()->getOwnChannel();

        return view('backend/als_rpt_ib_cxt/index', compact('cxts', 'channel'));
    }
}
