<?php

namespace App\Http\Controllers\Backend;

use App\Model\AlsRptIbCxt;
use App\Model\AmtAlsRpt;
use App\Model\AmtCatgory;
use Auth;
use DB;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpFoundation\Response;

class AmtAlsRptController extends Controller
{
    protected static $levelStats = [
        14 => NULL, 15 => NULL, 16 => NULL, 
        17 => NULL, 18 => NULL, 19 => NULL, 
        21 => NULL, 22 => NULL, 23 => NULL
    ];

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

        $categorys = \App\Model\AmtCategory::find(array_keys(static::$levelStats));

        foreach (static::$levelStats as $id => $levelStat) {
            $levelStats[$id] = $report->replica->getLevelByCategory($this->findMapCategory($categorys, $id));
        }

        DB::beginTransaction();
        try {
            $this->bindCxtIfNeedTo($report, $request);

            DB::commit();

            return view('backend/amt_als_rpt/show', compact('report', 'levelStats'));
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return redirect('/backend/amt_als_rpt')->with('error', "{$e->getMessage()}");
        }   
    }

    protected function findMapCategory($categorys, $id)
    {
        return $categorys->first(function($category) use ($id) { 
            return $id === $category->id;
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\AmtAlsRpt $report
     * @param  \Illuminate\Http\Request $request
     */
    protected function bindCxtIfNeedTo(AmtAlsRpt $report, Request $request)
    {
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

        return $this;
    }
}
