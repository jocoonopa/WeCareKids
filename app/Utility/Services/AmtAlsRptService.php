<?php

namespace App\Utility\Services;

use App\Model\AmtAlsRpt;
use App\Model\AmtCategory;
use App\Model\AmtReplica;
use App\Model\WckUsageRecord;
use App\Utility\Services\AmtAlsRpt\CourseTrait;
use App\Utility\Services\AmtAlsRpt\GuardianTrait;
use App\Utility\Services\AmtAlsRpt\SuggestionTrait;
use DB;

// 智能運動能力等級, 粗大動作等敘述留給職治師填寫 
class AmtAlsRptService
{
    use CourseTrait, SuggestionTrait, GuardianTrait;

    protected $defaultLevel;

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

    public function calculateAverageLevel(array $levelStatus)
    {
        $sum = 0;
        $count = 0;

        foreach ($levelStatus as $level) {
            if (empty($level)) {
                continue;
            }

            $sum += $level;
            $count ++;
        }

        return floor($sum/$count);
    }

    public function getFeelIntegrationLevel(array $levelStatus)
    {
        return $this->getSpecificHighLevel($levelStatus, AmtCategory::ID_FEEL_INTEGRATE);
    }

    public function getRoughActLevel(array $levelStatus)
    {
        return $this->getSpecificHighLevel($levelStatus, AmtCategory::ID_ROUGH_ACTION);
    }

    protected function getSpecificHighLevel(array $levelStatus, $id)
    {
        $sumLevel = 0;
        $count = 0;
        $categorys = AmtCategory::find($id)->childs()->get();

        foreach ($categorys as $category) {
            $level = array_get($levelStatus, $category->content);

            if (is_null($level)) {
                continue;
            }

            $sumLevel += (int) $level;
            $count ++; 
        }

        return floor($sumLevel/$count);
    }

    /**
     * 根據傳入的 AmtCategory, 取得此 AmtReplica 所有關聯隸屬的 AmtReplicaDiagGroup,
     * 加總 group 的 level後返回平均 level
     *
     * @param \App\Model\AmtAlsRpt $report
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
                if ($group->isDone()) {
                    $count ++;
                    $level += $group->getLevel();
                }                
            });
        }
        
        return 0 === $count ? '' : floor($level/$count);
    }

    /**
     * 產生使用紀錄
     *
     * WckUsageRecordObserver::created() 會被觸發, 更新 Organization 的剩餘金額
     * 
     * @param  \App\Model\AmtAlsRpt $report
     * @return \App\Model\WckUsageRecord       
     */
    public function genUsageRecord(AmtAlsRpt $report)
    {
        $usage = new WckUsageRecord;
        $usage->user()->associate($report->replica->creater);
        $usage->child()->associate($report->replica->child);
        $usage->organization()->associate($report->replica->creater->organization);
        $usage->usage()->associate($report);
        $usage->variety = WckUsageRecord::COST_PER_REPLICA;
        $usage->brief = "<a href=\"/backend/amt_als_rpt/{$report->id}\" target=\"_blank\">產生{$report->replica->child->name}的評測報告</a>";

        $usage->save();

        return $usage;
    }

    /**
     * 取得九大分類的等級統計陣列
     * 
     * @param  \App\Model\AmtAlsRpt $report
     * @return array            [分類名稱: 統計等級]
     */
    public function getLevelStats(AmtAlsRpt $report)
    {
        $levelStats = [];
        
        $categorys = AmtCategory::findIsStat()->get();
        
        foreach ($categorys as $category) {
            $levelStats[$category->content] = static::getLevelByCategory($report, $category);
        }

        return $levelStats;
    }

    /**
     * 根據傳入的 levelStats 陣列和預設的小孩等級, 進行優弱勢能力陣列組成
     * 
     * @param  array  $levelStats 
     * @param  integer $defaultLevel
     * @return array [優勢: [[分類名稱1: 等級1], [分類名稱2: 等級2] ...]]
     */
    public function getComplexStats(array $levelStats, $defaultLevel)
    {
        $complexStats = ['优势能力' => [], '符合标准' => [], '弱势能力' => []];

        $sum = array_sum($levelStats);
        
        foreach ($levelStats as $content => $levelStat) {
            if ($this->isWeakAbility($defaultLevel, $levelStat)) {
                $complexStats['弱势能力'][] = [$content => $levelStat]; 

                continue;
            }

            if ($this->isStrongAbility($sum, $levelStat)) {
                $complexStats['优势能力'][] = [$content => $levelStat];

                continue;
            }

            $complexStats['符合标准'][] = [$content => $levelStat];
        }

        return $complexStats;
    }

    protected function isStrongAbility($sum, $level)
    {
        return (float) $level/$sum >= 0.13;
    }

    protected function isWeakAbility($defaultLevel, $level)
    {
        return $level <= ($defaultLevel - AmtAlsRpt::ABILITY_COMPARE_THREAD_ID);
    }
}