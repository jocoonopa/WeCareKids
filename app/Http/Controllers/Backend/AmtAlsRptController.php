<?php

namespace App\Http\Controllers\Backend;

use AmtAlsRpt as AAR;
use App\Model\AlsRptIbCxt;
use App\Model\AmtAlsRpt;
use App\Model\AmtCategory;
use Auth;
use DB;
use Illuminate\Http\Request;
use Log;
use Wck;
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
        $reports = AmtAlsRpt::latest()->paginate(env('PERPAGE_COUNT', 50));

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
        | 连结 AlsRptIbCxt 
        |--------------------------------------------------------------------------
        |
        */
        // if (Auth::user()->id !== $report->owner_id) {
        //     abort(Response::HTTP_FORBIDDEN);
        // }

        if (is_null($report->replica)) { // Should be move to middleware
            abort(Response::HTTP_FORBIDDEN, '此报告没有包含任何资料!');
        }

        DB::beginTransaction();
        try {
            $this->bindCxtIfNeedTo($report, $request);

            $organiztion = $report->owner->organization;
            $defaultLevel = $report->replica->getLevel();
            
            $levelStats = $this->getLevelStats($report);// Would call AAR::getLevelByCategory
            $iLevel = AAR::getFeelIntegrationLevel($levelStats);
            $eLevel = AAR::getRoughActLevel($levelStats);
            $avgLevel = AAR::calculateAverageLevel($levelStats);
            
            $complexStats = $this->getComplexStats($levelStats, $defaultLevel);
            $alsData = is_null($report->cxtBelongs) ? [] : $report->cxtBelongs->getSenseAlsData();
           
            $quarLevels = $this->getQuadrantSumLevels($report);
            $maxAlsCategory = is_null($report->cxtBelongs) ? '' : $report->cxtBelongs->getMaxAlsCategory();

            DB::commit();

            return view('backend/amt_als_rpt/show', compact(
                'organiztion',
                'defaultLevel',
                'report', 
                'levelStats', 
                'avgLevel',
                'complexStats',
                'alsData',
                'iLevel',
                'eLevel',
                'quarLevels',
                'maxAlsCategory'
            ));
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return redirect('/backend/amt_als_rpt')->with('error', "{$e->getMessage()}");
        }   
    }

    /**
     * 取得四個剖析向度的 level 
     * 
     * @param  AmtAlsRpt $report
     * @return array
     */
    protected function getQuadrantSumLevels(AmtAlsRpt $report)
    {
        if (is_null($report->cxtBelongs)) {
            return [];
        }

        $quarLevels = [];

        $symbols = [
            AlsRptIbCxt::SYMBOL_LAND,
            AlsRptIbCxt::SYMBOL_SEARCH,
            AlsRptIbCxt::SYMBOL_SENSITIVE,
            AlsRptIbCxt::SYMBOL_DODGE
        ];

        foreach ($symbols as $symbol) {
            $quarLevels[$symbol] = $report->cxtBelongs->getQuadrantSumLevel($symbol); 
        }

        return $quarLevels;
    }

    /**
     * 取得九大分類的等級統計陣列
     * 
     * @param  \App\Model\AmtAlsRpt $report
     * @return array            [分類名稱: 統計等級]
     */
    protected function getLevelStats(AmtAlsRpt $report)
    {
        return AAR::getLevelStats($report);
    }

    /**
     * 根據傳入的 levelStats 陣列和預設的小孩等級, 進行優弱勢能力陣列組成
     * 
     * @param  array  $levelStats 
     * @param  integer $defaultLevel
     * @return array [優勢: [[分類名稱1: 等級1], [分類名稱2: 等級2] ...]]
     */
    protected function getComplexStats(array $levelStats, $defaultLevel)
    {
        return AAR::getComplexStats($levelStats, $defaultLevel);
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

                $request->session()->flash('success', "已成功绑定连结{$child->name}的剖析量表!");
            }               
        }

        return $this;
    }
}
