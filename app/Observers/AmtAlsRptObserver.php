<?php

namespace App\Observers;

use AmtAlsRpt as AAR;
use App\Model\AmtAlsRpt;

class AmtAlsRptObserver
{
    public function created(AmtAlsRpt $report)
    {
        AAR::genUsageRecord($report);
    }
}