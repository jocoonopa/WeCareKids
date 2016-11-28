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

    protected $fillable = [
        'child_name', 
        'child_sex', 
        'content', 
        'child_birthday', 
        'filler_name', 
        'relation', 
        'school_name', 
        'grade_num', 
        'phone', 
        'email'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'child_birthday',
        'created_at',
        'updated_at'
    ];

    public static function createPrototype(AlsRptIbChannel $channel)
    {
        $cxt = new AlsRptIbCxt;
        $cxt->channel_id = $channel->id;
        $cxt->private_key = md5(uniqid());
        $cxt->status = static::STATUS_HASNOT_SUBMIT;

        return $cxt;
    }

    public function getContentValue($name)
    {
        $content = json_decode($this->content, true);

        return array_get($content, $name);
    }

    public function channel()
    {
        return $this->belongsTo('App\Model\AlsRptIbChannel');
    }

    public function isPrivateKeyValid($privateKey)
    {
        return $this->private_key === $privateKey;
    }
}
