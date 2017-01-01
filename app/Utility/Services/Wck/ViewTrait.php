<?php

namespace App\Utility\Services\Wck;

use App\Model\AlsRptIbCxt;

trait ViewTrait
{
    public function getCxtStatusLabel(AlsRptIbCxt $cxt)
    {
        switch($cxt->status)
        {
            case AlsRptIbCxt::STATUS_HASNOT_SUBMIT:
                return 'label-warning';
            break;
            case AlsRptIbCxt::STATUS_HAS_SUBMIT:
                return 'label-primary';
            break;
            case AlsRptIbCxt::STATUS_HAS_MAP:
                return 'label-success';
            break;
            default:
                return 'label-danger';
            break;
        }
    }
}