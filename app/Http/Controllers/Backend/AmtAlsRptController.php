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

        DB::beginTransaction();
        try {
            $this->bindCxtIfNeedTo($report, $request);

            $organiztion          = $report->owner->organization;
            $defaultLevel         = $report->replica->getLevel();
            $levelStats           = $this->getLevelStats($report);
            $avgLevel             = Wck::calculateAverageLevel($levelStats);
            $complexStats         = $this->getComplexStats($levelStats, $defaultLevel);
            $alsData              = is_null($report->cxtBelongs) ? [] : $report->cxtBelongs->getSenseAlsData();
            $iLevel               = AAR::getLevelByCategory($report, AmtCategory::find(AmtCategory::ID_FEEL_INTEGRATE));
            $eLevel               = AAR::getLevelByCategory($report, AmtCategory::find(AmtCategory::ID_ROUGH_ACTION));
            $quarLevels           = $this->getQuadrantSumLevels($report);
            $maxAlsCategory       = is_null($report->cxtBelongs) ? '' : $report->cxtBelongs->getMaxAlsCategory();
            // $bestAmtCategoryName  = array_first(array_keys($levelStats, max($levelStats)));
            // $worseAmtCategoryName = array_first(array_keys($levelStats, min($levelStats)));

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
                //'bestAmtCategoryName',
                //'worseAmtCategoryName'
            ));
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return redirect('/backend/amt_als_rpt')->with('error', "{$e->getMessage()}");
        }   
    }

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

    protected function getLevelStats(AmtAlsRpt $report)
    {
        $levelStats = [];
        
        $categorys = AmtCategory::findIsStat()->get();
        
        foreach ($categorys as $category) {
            $levelStats[$category->content] = AAR::getLevelByCategory($report, $category);
        }

        return $levelStats;
    }

    protected function getComplexStats(array $levelStats, $defaultLevel)
    {
        $complexStats = ['優勢能力' => [], '符合標準' => [], '弱勢能力' => []];
        
        foreach ($levelStats as $content => $levelStat) {
            if ($levelStat <= $defaultLevel - AmtAlsRpt::ABILITY_COMPARE_THREAD_ID) {
                $complexStats['弱勢能力'][] = [$content => $levelStat]; 

                continue;
            }

            if ($levelStat >= $defaultLevel + AmtAlsRpt::ABILITY_COMPARE_THREAD_ID) {
                $complexStats['優勢能力'][] = [$content => $levelStat];

                continue;
            }

            $complexStats['符合標準'][] = [$content => $levelStat];
        }

        return $complexStats;
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
