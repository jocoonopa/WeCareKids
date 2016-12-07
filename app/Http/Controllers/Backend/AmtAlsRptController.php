<?php

namespace App\Http\Controllers\Backend;

use App\Model\AlsRptIbCxt;
use App\Model\AmtAlsRpt;
use Auth;
use DB;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpFoundation\Response;

class AmtAlsRptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = Auth::user()->reports()->orderBy('id', 'desc')->get();

        return view('/backend/amt_als_rpt/index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\AmtAlsRpt $report
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(AmtAlsRpt $report, Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | 連結 AlsRptIbCxt 
        |--------------------------------------------------------------------------
        |
        */
        if (Auth::user()->id !== $report->owner_id) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if (is_null($report->replica)) { // Should be move to middleware
            abort(Response::HTTP_FORBIDDEN, '此報告沒有包含任何資料!');
        }

        DB::beginTransaction();
        try {
            $child = $report->replica->child;

            if (is_null($report->cxt)) {
                $cxt = AlsRptIbCxt::findOrphanByChild($child)->first(); 

                if (!is_null($cxt)) {
                    $report->update(['cxt_id' => $cxt->id]);
                    $cxt->update(['report_id' => $report->id]);
                    $child->update(['identifier' => $cxt->child_identifier]);

                    $request->session()->flash('success', "已成功綁定連結{$child->name}的剖析量表!");
                }               
            }

            DB::commit();

            return view('backend/amt_als_rpt/show', compact('report', 'child'));
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return redirect('/backend/amt_als_rpt')->with('error', "{$e->getMessage()}");
        }   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
