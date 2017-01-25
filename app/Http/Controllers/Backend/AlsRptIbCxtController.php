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

        $this->middleware('can:view,als_rpt_ib_cxt')->only('show', 'edit');
        $this->middleware('can:update,als_rpt_ib_cxt')->only('edit', 'update');
        $this->middleware('can:delete,als_rpt_ib_cxt')->only('destroy');
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){}
}
