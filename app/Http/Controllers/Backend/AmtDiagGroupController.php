<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\AmtDiagGroupRequest;
use App\Model\Amt;
use App\Model\AmtDiagGroup;
use Illuminate\Http\Request;

class AmtDiagGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->middleware('amt.diag.group')->only(['show', 'edit', 'update', 'destroy']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Model\Amt $amt
     * @param  App\Model\AmtDiagGroup $group
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(Amt $amt, AmtDiagGroup $group)
    {
        return view('backend.amt_diag_group.edit', compact('amt', 'group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\AmtDiagGroupRequest  $request
     * @param  App\Model\Amt $amt
     * @param  App\Model\AmtDiagGroup $group
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(AmtDiagGroupRequest $request, Amt $amt, AmtDiagGroup $group)
    {
        $group->update($request->all());

        return redirect("/backend/amt/{$amt->id}")->with('success', "<strong class=\"label label-info\">[{$group->category->content }]: {$group->content}</strong>   修改完成!");
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
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Amt $amt, AmtDiagGroup $group){}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Amt $amt, AmtDiagGroup $group){}
}
