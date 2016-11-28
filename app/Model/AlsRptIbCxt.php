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

    public static $map = array(
        '—' => array(
            '0_2','0_5','1_3',
            '1_6','2_4','2_6',
            '3_9','3_10','3_12',
            '4_1','4_4','4_5',
            '5_2','5_5','5_9'
        ),
        '§' => array(
            '0_1','0_3','0_7',
            '1_1','1_5','2_0',
            '2_2','3_1','3_3',
            '3_5','4_0','4_2',
            '4_7','5_0','5_8'
        ),
        '○' => array(
            '0_6','1_0','1_4',
            '1_7','2_3','2_5',
            '2_8','3_0','3_4',
            '3_6','3_7','4_8',
            '5_1','5_4','5_10'
        ),
        '|' => array(
            '0_0','0_4','1_2',
            '2_1','2_7','2_9',
            '3_2','3_8','3_11',
            '4_3','4_6','4_9',
            '5_3','5_6','5_7'
        )
    );

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

    public function channel()
    {
        return $this->belongsTo('App\Model\AlsRptIbChannel');
    }

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

    public function isPrivateKeyValid($privateKey)
    {
        return $this->private_key === $privateKey;
    }

    /**
     * 取得剖析量表個象限統計分數
     * 
     * @return array
     */
    public function getQuadrantSums()
    {
        /**
         * @var array
         */
        $content = json_decode($this->content, true);

        $sums = array();

        foreach (static::$map as $key => $qua) {
            if (!array_key_exists($key, $sums)) {
                $sums[$key] = 0;
            } 

            foreach ($qua as $name => $val) {
                $sums[$key] += ((int) $val) + 1;
            }
        }

        return $sums;
    }
}
