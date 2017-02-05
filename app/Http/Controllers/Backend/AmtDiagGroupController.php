<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\AmtDiagGroupRequest;
use App\Model\Amt;
use App\Model\AmtDiagGroup;
use Illuminate\Http\Request;

class AmtDiagGroupController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('super.user');
    }

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
}
