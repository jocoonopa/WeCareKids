<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * AnalysisReportInboundContext
 */
class AlsRptIbCxt extends Model
{
    protected $table = 'als_rpt_ib_cxts';

    const STATUS_HASNOT_SUBMIT = 0;
    const STATUS_HAS_SUBMIT = 1;

    public static function createPrototype(AlsRptIbChannel $channel)
    {
        $cxt = new AlsRptIbCxt;
        $cxt->channel_id = $channel->id;
        $cxt->private_key = md5(uniqid());
        $cxt->status = static::STATUS_HASNOT_SUBMIT;

        return $cxt;
    }

    public function channel()
    {
        return $this->belongsTo('App\Model\AlsRptIbChannel');
    }
}
