<?php

namespace App\Http\Controllers\Backend;

use App\Model\AmtDiagGroup;
use App\Model\AmtDiagStandard;
use Illuminate\Http\Request;

class AmtDiagStandardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AmtDiagGroup $group)
    {
        return view('/backend/amt_diag_standard/index', compact('group'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AmtDiagGroup $group, AmtDiagStandard $standard)
    {
        return view('/backend/amt_diag_standard/edit', compact('group', 'standard'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AmtDiagGroup $group, AmtDiagStandard $standard)
    {
        $standard->update([
            'diag_id' => $request->get('diag_id'),
            'condition_value' => $request->get('condition_value'),
            'min_level' => $request->get('min_level'),
            'max_level' => $request->get('max_level')
        ]);

        return redirect("/backend/amt_diag_group/{$group->id}/amt_diag_standard")->with('success', "{$standard->id}更新完成");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, AmtDiagGroup $group)
    {
        $standard = new AmtDiagStandard;

        return view('/backend/amt_diag_standard/create', compact('group', 'standard'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, AmtDiagGroup $group)
    {
        $standard = AmtDiagStandard::create([
            'diag_id' => $request->get('diag_id'),
            'condition_value' => $request->get('condition_value'),
            'min_level' => $request->get('min_level'),
            'max_level' => $request->get('max_level')
        ]);

        return redirect("/backend/amt_diag_group/{$group->id}/amt_diag_standard")->with('success', "{$standard->id}新增完成");
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
