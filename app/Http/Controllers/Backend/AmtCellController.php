<?php

namespace App\Http\Controllers\Backend;

use AmtCell as AC;
use App\Model\AmtCell;
use App\Model\AmtDiagGroup;
use Illuminate\Http\Request;

class AmtCellController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AmtDiagGroup $group)
    {
        return view('backend/amt_cell/index', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AmtDiagGroup $group, AmtCell $cell)
    {
        $cell->update([
            'statement' => $request->get('statement'),
            'league_id' => 0 === (int) $request->get('league_id') ? NULL : $request->get('league_id'),
            'step' => (int) $request->get('step'),
        ]);

        $str = AC::setStr($cell->statement)->convertToStatment();
        $standards = AC::getStandards();

        $cell->standards()->sync(array_pluck($standards->toArray(), 'id'));

        if($request->ajax()){
            return view('/backend/amt_cell/component/_response', compact('cell', 'group'));
        }

        return redirect("/backend/amt_diag_group/{$group->id}/amt_cell#__{$cell->id}__")->with('success', "{$cell->level}更新完成!");
    }
}
