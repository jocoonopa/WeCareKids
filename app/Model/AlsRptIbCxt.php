<?php

namespace App\Model;

use App\Model\AlsCategory;
use App\Model\Child;
use Illuminate\Database\Eloquent\Model;

/**
 * AnalysisReportInboundContext
 */
class AlsRptIbCxt extends Model
{
    protected $table = 'als_rpt_ib_cxts';

    const STATUS_HASNOT_SUBMIT = 0;
    const STATUS_HAS_SUBMIT    = 1;
    
    const SYMBOL_LAND          = '—';
    const SYMBOL_SENSITIVE     = '○';
    const SYMBOL_SEARCH        = '§';
    const SYMBOL_DODGE         = '|';

    const SENSE_KEY_TASTE      = 'taste';
    const SENSE_KEY_ACTION     = 'action';
    const SENSE_KEY_VISUAL     = 'visual';
    const SENSE_KEY_AUDITORY   = 'auditory';
    const SENSE_KEY_TOUCH      = 'touch';
    const SENSE_KEY_ACTIVITY   = 'activity';

    const SEX_MALE_ID = 1;
    const SEX_FEMALE_ID = 0;

    public static $symbolMap = array(
        self::SYMBOL_LAND => '低登陆量',
        self::SYMBOL_SENSITIVE => '感觉敏感',
        self::SYMBOL_SEARCH => '感觉需求',
        self::SYMBOL_DODGE => '感觉逃避'
    ); 

    public static $map = array(
        self::SYMBOL_LAND => array(
            '0_2','0_5','1_3',
            '1_6','2_4','2_6',
            '3_9','3_10','3_12',
            '4_1','4_4','4_5',
            '5_2','5_5','5_9'
        ),
        self::SYMBOL_SEARCH => array(
            '0_1','0_3','0_7',
            '1_1','1_5','2_0',
            '2_2','3_1','3_3',
            '3_5','4_0','4_2',
            '4_7','5_0','5_8'
        ),
        self::SYMBOL_SENSITIVE => array(
            '0_6','1_0','1_4',
            '1_7','2_3','2_5',
            '2_8','3_0','3_4',
            '3_6','3_7','4_8',
            '5_1','5_4','5_10'
        ),
        self::SYMBOL_DODGE => array(
            '0_0','0_4','1_2',
            '2_1','2_7','2_9',
            '3_2','3_8','3_11',
            '4_3','4_6','4_9',
            '5_3','5_6','5_7'
        )
    );

    public static $senseQnums = array(
        self::SENSE_KEY_TASTE => ['0_0','0_1','0_2','0_3','0_4','0_5','0_6','0_7'],
        self::SENSE_KEY_ACTION => ['1_0','1_1','1_2','1_3','1_4','1_5','1_6','1_7'],
        self::SENSE_KEY_VISUAL => ['2_0','2_1','2_2','2_3','2_4','2_5','2_6','2_7','2_8','2_9'],
        self::SENSE_KEY_TOUCH => ['3_0','3_1','3_2','3_3','3_4','3_5','3_6','3_7','3_8','3_9','3_10','3_11','3_12'],
        self::SENSE_KEY_ACTIVITY => ['4_0','4_1','4_2','4_3','4_4','4_5','4_6','4_7','4_8','4_9'],
        self::SENSE_KEY_AUDITORY => ['5_0','5_1','5_2','5_3','5_4','5_5','5_6','5_7','5_8','5_9','5_10']
    );

    public static $senseDesc = array(
        self::SENSE_KEY_TASTE => '味觉/嗅觉',
        self::SENSE_KEY_ACTION => '动作',
        self::SENSE_KEY_VISUAL => '视觉',
        self::SENSE_KEY_AUDITORY => '听觉',
        self::SENSE_KEY_TOUCH => '触觉',
        self::SENSE_KEY_ACTIVITY => '活动量'
    );

    public static $senseStandardColumns = [
        [self::SYMBOL_SENSITIVE, self::SYMBOL_DODGE], // 神经阈值(低)
        [self::SYMBOL_SEARCH, self::SYMBOL_LAND], // 神经阈值(高)
        [self::SYMBOL_DODGE, self::SYMBOL_SEARCH],  // 行为反应/自我调节(主动)
        [self::SYMBOL_LAND, self::SYMBOL_SENSITIVE] // 行为反应/自我调节(被动)
    ];

    /**
     * 味觉/嗅觉, 视觉, 动作, 听觉, 触觉, 活动量
     * 
     * t => 0 表示阈值低, 1 表示阈值高
     * a => 0 表示被动, 1 表示主动
     * ['t' => NULL, 'a' => NULL]
     * 
     * @var array
     */
    public static $senseStandards = [
        self::SENSE_KEY_TASTE => [10,15,5,10],
        self::SENSE_KEY_VISUAL => [10,10,15,15],
        self::SENSE_KEY_ACTION => [10,10,15,5],
        self::SENSE_KEY_AUDITORY => [15,10,15,15],
        self::SENSE_KEY_TOUCH => [15,15,20,15],
        self::SENSE_KEY_ACTIVITY => [15,15,5,15]
    ];

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
        'email',
        'report_id',
        'status'
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

    public function report()
    {
        return $this->belongsTo('App\Model\AmtAlsRpt', 'report_id', 'id');
    }

    public function scopeFindOrphanByChild($query, Child $child)
    {
        return $query
            ->whereNull('report_id')
            ->where('status', static::STATUS_HAS_SUBMIT)
            ->where('child_name', $child->name)
            ->whereDate('child_birthday', "{$child->birthday->format('Y-m-d')}")
        ;
    }

    /**
     * 取得感觉处理型态分析资料
     *
     * 六大感觉类型 [味觉/嗅觉, 动作, 视觉, 听觉, 触觉, 活动量]
     *
     * [
     *     '六大感觉-之一': [10, 15, 20, 15] //低登陆量, 感觉寻求, 感觉敏感, 感觉逃避
     * ]
     * @return array
     */
    public function getSenseAlsData()
    {
        $result = [];

        foreach (static::$senseQnums as $senseName => $qNames) {
            $tasteGrade = 0;
            $searchGrade = 0;
            $sensitiveGrade = 0;
            $dodgeGrade = 0;

            foreach ($qNames as $qName) {
                if (in_array($qName, static::$map[self::SYMBOL_LAND])) {
                    $tasteGrade += 1 + $this->getContentValue($qName);
                }

                if (in_array($qName, static::$map[self::SYMBOL_SEARCH])) {
                    $searchGrade += 1 + $this->getContentValue($qName);
                }

                if (in_array($qName, static::$map[self::SYMBOL_SENSITIVE])) {
                    $sensitiveGrade += 1 + $this->getContentValue($qName);
                }

                if (in_array($qName, static::$map[self::SYMBOL_DODGE])) {
                    $dodgeGrade += 1 + $this->getContentValue($qName);
                }
            }
            
            $result[$senseName] = [$tasteGrade, $searchGrade, $sensitiveGrade, $dodgeGrade];
        }

        return $result;
    }

    public static function createPrototype(AlsRptIbChannel $channel)
    {
        $cxt = new AlsRptIbCxt;
        $cxt->channel_id = $channel->id;
        $cxt->private_key = md5(uniqid());
        $cxt->status = static::STATUS_HASNOT_SUBMIT;

        return $cxt;
    }

    protected function getContent()
    {
        return json_decode($this->content, true);
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

    public function getQuadrantSumLevel($symbol)
    {
        $num = 0;
        $qNums = array_get(static::$map, $symbol);
        $content = $this->getContent();
        $conditions = [];

        foreach ($qNums as $qNum) {
            $num += array_get($content, $qNum) + 1;
        }

        switch ($symbol)
        {
            case static::SYMBOL_LAND:
                $conditions = [
                    [15, 22], [23, 28], [29, 43], [44, 50],[51, 75]
                ];
            break;

            case static::SYMBOL_SEARCH:
                $conditions = [
                    [15, 26], [27, 33], [34, 48], [49, 54], [55, 76]
                ];
            break;

            case static::SYMBOL_DODGE:
                $conditions = [
                    [15, 25], [26, 31], [32, 46], [47, 52], [53, 75]
                ];
            break;

            case static::SYMBOL_SENSITIVE:
                if (static::SEX_MALE_ID === $this->child_sex) {
                    $conditions = [
                        [15, 21], [22, 27], [28, 44], [45, 51], [52, 75]
                    ];
                }

                if (static::SEX_FEMALE_ID  === $this->child_sex) {
                    $conditions = [
                        [15, 26], [27, 32], [33, 48], [49, 54], [55, 75]
                    ];
                }
                    
            break;

            default:
                return 0;
            break;
        }

        foreach ($conditions as $key => $condition) {
            if ($condition[0] <= $num && $condition[1] >= $num) {
                return [
                    'l' => $key, 
                    'g' => $num
                ];
            }
        }

        return ['l' => 0, 'g' => 0];
    }

    /**
     * 取得剖析量表个象限统计分数
     * 
     * @example [
     *  '—' => num,
     *  '§' => num,
     *  '○' => num,
     *  '|' => num
     * ]
     * @return array
     */
    public function getQuadrantSums()
    {
        /**
         * @var array
         */
        $content = $this->getContent();

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

    /**
     * 取得分数最高的分类
     * 
     * @return \App\Model\AlsRptIbCxt
     */
    public function getMaxAlsCategory()
    {
        $sums = $this->getQuadrantSums();

        return AlsCategory::where('symbol', array_first(array_keys($sums, max($sums))))->first();
    }
}
