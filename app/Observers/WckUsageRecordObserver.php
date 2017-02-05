<?php

namespace App\Observers;

class WckUsageRecordObserver
{
    public function created(\App\Model\WckUsageRecord $usage)
    {
        $organization = $usage->organization;
        $organization->points = $organization->points + (int) $usage->variety;
        $organization->save();

        $usage->current_remain = $organization->points;
        $usage->save();
    }
}