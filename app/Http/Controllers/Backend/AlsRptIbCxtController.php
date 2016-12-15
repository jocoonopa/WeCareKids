<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\AlsRptIbCxt;
use Auth;

class AlsRptIbCxtController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Model\AlsRptIbCxt
     * @return \Illuminate\Http\Response
     */
    public function show(AlsRptIbCxt $cxt)
    {
        //$this->authorize('view', $cxt->channel);

        $sums = $cxt->getQuadrantSums();

        return view('backend/als_rpt_ib_cxt/show', compact('cxt', 'sums'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){}

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
