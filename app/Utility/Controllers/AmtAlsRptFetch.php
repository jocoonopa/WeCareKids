<?php

namespace App\Utility\Controllers;

use AmtAlsRpt as AAR;
use App\Model\AlsRptIbCxt;
use App\Model\AmtAlsRpt;
use App\Model\Guardian;
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
     * @return boolean
     */
    protected function bindCxtIfNeedTo(AmtAlsRpt $report)
    {
        /*
        |--------------------------------------------------------------------------
        | 家長，小孩，報告，剖析量表綁定
        |--------------------------------------------------------------------------
        | 1. 判斷報告有無綁定剖析量表，若有則終止處理
        | 2. 取得剖析量表，若沒有剖析量表則終止
        | 3. 判斷剖析量表內的家長資料是否已經有根據手機號碼對應到的 Guardian 實體，若無則新增，有則更新
        | 4. 將 Child 和 Guardian 做綁定, 
        | 5. Cxt 帶來的 Child 資料更新至 Child
        | 6. 報告和剖析量表綁定
        */
        // ~1
        if (!is_null($report->cxt)) {
            return false;    
        }

        /**
         * 報告關聯的小朋友
         * 
         * @var \App\Model\Child
         */
        $child = $report->replica->child;

        // ~2
        /**
         * 尚未被 Mapping 的剖析問卷
         * 
         * @var \App\Model\AlsRptIbCxt | NULL
         */
        $cxt = AlsRptIbCxt::findOrphanByChild($child)->first(); 
        if (is_null($cxt)) {
            return false;
        }

        // ~3.
        /**
         * 透過電話號碼對應到的家長
         * 
         * @var \App\Model\Guardian | NULL
         */
        $guardian = Guardian::where('mobile', trim($cxt->phone))->first();                

        // Guardian 新增和更新欄位補上
        if (is_null($guardian)) {
            $guardian = Guardian::_create($cxt->filler_name, $cxt->phone, $cxt->filler_sex, $cxt->email);
        } else {
            $guardian->update([
                'name' => $cxt->filler_name,
                'mobile' => $cxt->phone,
                'sex' => $cxt->filler_sex,                        
                'email' => $cxt->email
            ]);
        }

        // 關聯家長和小朋友
        $guardian->childs()->syncWithoutDetaching([$child->id]);

        // 更新家長和小朋友 pivot 的 relation 欄位
        $guardian->childs()->updateExistingPivot($child->id, ['relation' => $cxt->relation]);

        // ~4
        $child->guardians()->syncWithoutDetaching([$guardian->id]);

        // ~5
        $child->update([
            'identifier' => $cxt->child_identifier,
            'school_name' => $cxt->school_name,
            'grade' => $cxt->grade,
            'sex' => $cxt->child_sex,
            'birthday' => $cxt->child_birthday
        ]);

        // ~6
        $report->cxtBelongs()->associate($cxt);
        $report->save();

        $cxt->report()->associate($report);
        $cxt->save();                                

        return true;
    }
}