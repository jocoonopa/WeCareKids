<?php

namespace App\Observers;

use AmtAlsRpt as AAR;
use App\Model\AmtAlsRpt;

class AmtReplicaObserver
{
    public function created(AmtAlsRpt $report)
    {
        AAR::genUsageRecord($report);
    }
}