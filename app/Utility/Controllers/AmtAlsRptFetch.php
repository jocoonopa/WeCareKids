<?php

namespace App\Utility\Controllers;

use AmtAlsRpt as AAR;
use App\Model\AmtAlsRpt;
use App\Model\AlsRptIbCxt;
use Illuminate\Http\Request;

trait AmtAlsRptFetch
{
    /**
     * 取得產生報告需要用得的資料
     * 
     * @param  \App\Model\AmtAlsRpt $report [description]
     * @return array           
     */
    protected function fetchData(AmtAlsRpt $report)
    {
        $organiztion = $report->owner->organization;
        $defaultLevel = $report->replica->getLevel();
        
        $levelStats = AAR::getLevelStats($report);// Would call AAR::getLevelByCategory
        $iLevel = AAR::getFeelIntegrationLevel($levelStats);
        $eLevel = AAR::getRoughActLevel($levelStats);
        $avgLevel = AAR::calculateAverageLevel($levelStats);
        $complexStats = AAR::getComplexStats($levelStats, $defaultLevel);
        $alsData = is_null($report->cxtBelongs) ? [] : $report->cxtBelongs->getSenseAlsData();
       
        $quarLevels = $this->getQuadrantSumLevels($report);
        $maxAlsCategory = is_null($report->cxtBelongs) ? '' : $report->cxtBelongs->getMaxAlsCategory();
        $courses = AAR::getRecommendCourses($report, $levelStats);

        return [
            'report' => $report,
            'organiztion' => $organiztion,
            'defaultLevel' => $defaultLevel,
            'levelStats' => $levelStats,
            'iLevel' => $iLevel,
            'eLevel' => $eLevel,
            'avgLevel' => $avgLevel,
            'complexStats' => $complexStats,
            'alsData' => $alsData,
            'quarLevels' => $quarLevels,
            'maxAlsCategory' => $maxAlsCategory,
            'courses' => $courses
        ];
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