<?php

namespace App\Utility\Services;

use App\Model\AmtAlsRpt;
use App\Model\AmtCategory;
use App\Model\AmtReplica;
use DB;

// 智能運動能力等級, 粗大動作等敘述留給職治師填寫 
class AmtAlsRptService
{
    /**
     * # Greater 的定義: 
     * AmtReplicaDiagGroup::getLevel() > AmtReplica::getLevel() + AmtAlsRpt::ABILITY_COMPARE_THREAD_ID
     *
     * 先找出 AmtReplicaDiagGroup, 以此為基準配合 step = AmtCategory::STEP_STAT_ID , 關聯出 AmtCategory,
     * 呼叫 AmtCategory::findFinals() 找出所有隸屬的最末分類,
     * 加總每個 step === AmtCategory::STEP_STAT_ID 的 AmtCategory 之 finals() level, 
     * 並且已 @example 格式紀錄儲存.
     * 
     * @example [
     *    {'categoryName': 'Level'}
     * ]
     * 
     * @return array 
     */
    public function fetchCategorysAmtLevelData()
    {
        /**
         * 以分類名稱為鍵名, 分類 level 為值得陣列為元素的二維陣列 
         * 
         * @var array
         */
        $amtLevelData = [];

        /**
         * 統計層級的分類
         * 
         * @var Collection
         */
        $statCategorys = AmtCategory::findIsStat()->get();

        $statCategorys->each(function ($statCategory) use (&$amtLevelData) {
            $amtLevelData[] = [
                $statCategory->content => $this->getLevelByCategory($statCategory)
            ];
        });

        return $amtLevelData;
    }

    /**
     * 根據傳入的 AmtCategory, 取得此 AmtReplica 所有關聯隸屬的 AmtReplicaDiagGroup,
     * 加總 group 的 level後返回平均 level
     *
     * @param \App\Model\AmtCategory $category
     * @return integer
     */
    public function getLevelByCategory(AmtAlsRpt $report, AmtCategory $category)
    {
        /**
         * 分類下 AmtReplicaDiagGroup 計數
         * 
         * @var integer
         */
        $count = 0;

        /**
         * 計算的 level
         * 
         * @var integer
         */
        $level = 0;

        /**
         * 存放取得的最末 AmtCategory 
         * 
         * @var array [\App\Model\AmtCategory]
         */
        $finals = [];

        // 透過 \App\Model\AmtCategory::findFinals() 找尋並儲存最末分類
        $category->findFinals($finals);
        
        foreach ($finals as $final) {
            $groups = $report->replica->findGroupsByCategory($final);

            $groups->each(function ($group) use (&$level, &$count) {
                $count ++;
                $level += $group->getLevel();
            });
        }
        
        return 0 === $count ? 0 : floor($level/$count);
    }
}