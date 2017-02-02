<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\AlsRptIbCxt;
use Auth;
use DB;
use Wck;

class AlsRptIbCxtController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('can:view,cxt')->only('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $channel = Wck::getUserChannel();
        $organization = Auth::user()->organization;
        $organizationId = is_null($organization) ? 0 : $organization->id;
       
        $cxts = AlsRptIbCxt::select('als_rpt_ib_cxts.*')
            ->leftJoin('als_rpt_ib_channels', 'als_rpt_ib_channels.id', '=', 'als_rpt_ib_cxts.channel_id')
            ->leftJoin('users', 'users.id', '=', 'als_rpt_ib_channels.creater_id')
            ->where('users.organization_id', '=', $organizationId)
            ->orderBy('als_rpt_ib_cxts.created_at', 'desc')
            ->groupBy('als_rpt_ib_cxts.id')
            ->paginate(env('PERPAGE_COUNT', 50))
        ;
    
        return view('backend/als_rpt_ib_cxt/index', compact('cxts', 'channel'));
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
}
