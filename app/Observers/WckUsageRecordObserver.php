<?php

namespace App\Observers;

class WckUsageRecordObserver
{
    public function created(\App\Model\WckUsageRecord $usage)
    {
        $usage->current_remain = $usage->organization->points + (int) $usage->variety;
        $usage->save();

        $organization = $usage->user->organization;
        $organization->points = $organization->points + (int) $usage->variety;
        $organization->save();
    }
}