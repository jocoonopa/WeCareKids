<?php

namespace App\Http\Controllers\Backend;

use App\Model\AmtDiag;
use App\Model\AmtDiagGroup;
use Illuminate\Http\Request;

class AmtDiagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AmtDiagGroup $group)
    {
        return view('backend/amt_diag/index', compact('group'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(AmtDiagGroup $group)
    {
        $diag = new AmtDiag;

        return view('backend/amt_diag/create', compact('group', 'diag'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AmtDiagGroup $group, Request $request)
    {
        $diag = AmtDiag::create([
            'group_id' => $group->id,
            'description' => $request->get('description'),
            'type' => $request->get('type'),
            'available_value' => $request->get('available_value')
        ]);

        return redirect("/backend/amt_diag_group/{$group->id}/amt_diag/create")->with('success', "{$diag->id}新增成功!");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  AmtDiagGroup $group
     * @param  AmtDiag $diag
     * @return \Illuminate\Http\Response
     */
    public function edit(AmtDiagGroup $group, AmtDiag $diag)
    {
        return view('backend/amt_diag/edit', compact('group', 'diag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AmtDiagGroup $group
     * @param  AmtDiag $diag
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(AmtDiagGroup $group, AmtDiag $diag, Request $request)
    {
        $diag->update([
            'description' => $request->get('description'),
            'type' => $request->get('type'),
            'available_value' => $request->get('available_value')
        ]);

        return redirect("/backend/amt_diag_group/{$group->id}/amt_diag")->with('success', "{$diag->id}修改成功!");
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
