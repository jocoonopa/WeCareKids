<?php

namespace App\Utility\Services\AmtAlsRpt;

use App\Model\AlsRptIbCxt;
use App\Model\AmtAlsRpt;
use App\Model\Child;
use App\Model\Guardian;

trait GuardianTrait
{
    /**
     * 根據傳入的 Cxt 和 Child, 進行家長新增/更新和關聯
     * 
     * @param  App\Model\AlsRptIbCxt $cxt  
     * @param  App\Model\Child | null  $child
     * @return App\Model\Guardian            
     */
    public function procGuardian(AlsRptIbCxt $cxt, Child $child = NULL)
    {
        /**
         * 孩童
         * 
         * @var \App\Model\Child
         */
        $child = is_null($child) ? $cxt->report->replica->child : $child;

        /**
         * 透過電話號碼對應到的家長
         * 
         * @var \App\Model\Guardian | NULL
         */
        $guardian = Guardian::where('mobile', trim($cxt->phone))->first();                

        // Guardian 新增和更新欄位補上
        if (is_null($guardian)) {
            $guardian = Guardian::_create(
                $cxt->filler_name, $cxt->phone, 
                $cxt->filler_sex, $cxt->email
            );
        } else {
            $guardian->update([
                'name' => $cxt->filler_name,
                'mobile' => $cxt->phone,
                'sex' => $cxt->filler_sex,                        
                'email' => $cxt->email,
            ]);
        }

        // 關聯家長和小朋友
        $guardian->childs()->syncWithoutDetaching([$child->id]);

        // 更新家長和小朋友 pivot 的 relation 欄位
        $guardian->childs()->updateExistingPivot($child->id, ['relation' => $cxt->relation]);

        return $guardian;
    }
}