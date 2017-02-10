<?php

namespace App\Model;

use AmtCell as AC;
use App\Model\AmtDiag;
use App\Model\Child;
use DB;
use Illuminate\Database\Eloquent\Model;
use Wck;

class AmtCell extends Model
{
    const MIN_LEVEL = 0;
    const MAX_LEVEL = 20;

    public static $threadMap = ['l' => -1, 'e' => 0, 'h' => -1];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The standards that belong to the cell
     */
    public function standards()
    {
        return $this->belongsToMany('App\Model\AmtDiagStandard', 'cells_standards', 'cell_id', 'standard_id');
    }

    /**
     * The group that cell belong to 
     */
    public function group()
    {
        return $this->belonsTo('App\Model\AmtDiagGroup', 'group_id', 'id');
    }

    /**
     * The nextCell that cell belong to 
     */
    public function next()
    {
        return $this->belongsTo('App\Model\AmtCell', 'next_id', 'id');
    }

    /**
     * The prevCell that cell belong to 
     */
    public function prev()
    {
        return $this->belongsTo('App\Model\AmtCell', 'prev_id', 'id');
    }

    /**
     * The leagueCell that cell belong to 
     */
    public function league()
    {
        return $this->belongsTo('App\Model\AmtCell', 'league_id', 'id');
    }

    public function currentRpaGroups()
    {
        return $this->hasMany('App\Model\AmtReplicaDiagGroup', 'current_cell_id', 'id');
    }

    public function resultRpaGroups()
    {
        return $this->hasMany('App\Model\AmtReplicaDiagGroup', 'result_cell_id', 'id');
    }

    /**
     * 判斷是否為最末 cell (高or低都算)
     * 
     * @return boolean
     */
    public function isEnd()
    {
        return (static::MAX_LEVEL === $this->level) || is_null($this->prev);
    }

    /**
     * 判斷是否為閾值題
     * 
     * @return boolean
     */
    public function isThread()
    {
        return 0 < $this->getChief()->step;
    }

    /**
     * League 是否沒有包含任何 standard
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return 0 === $this->getChief()->standards()->count();
    }

    /**
     * 可讓 AmtReplicaDiag::isPass 呼叫, 用來尋找 AmtReplicaDiag 在該 AmtCell 中對應到的
     * AmtDiagStandard
     * 
     * @param  AmtReplicaDiag $replicaDiag
     * @return \App\Model\AmtDiagStandard
     */
    public function getDiagMapStandard(AmtReplicaDiag $replicaDiag)
    {
        return $this->getChief()->standards()->get()->first(function ($standard) use ($replicaDiag) {
            return $standard->diag->id === $replicaDiag->diag->id;
        });
    }

    /**
     * 最後跑分時會用到.
     *
     * 根據小朋友年齡整合 League 回傳對應之 AmtCell
     * 
     * @param  \App\Model\AmtReplica $replica
     * @return \App\Model\AmtCell
     */
    public function getLevel(AmtReplica $replica)
    {
        $defaultLevel = $replica->getLevel();

        $levels = [];

        if (!is_null($this->league_id)) {
            $leagueArr = AmtCell::where('league_id', $this->league_id)->orderBy('level', 'asc')->get()->toArray();

            $levels = array_pluck($leagueArr, 'level');
        }

        if (Wck::isEmpty($levels)) {
            return $this->level;
        }

        return $this->getBubbleLeve($levels, $defaultLevel);
    }

    protected function getBubbleLeve(array $levels, $defaultLevel)
    {
        /*
        |--------------------------------------------------------------------------
        | 算分邏輯
        |--------------------------------------------------------------------------
        | - 最外層先檢查 "結果level" 是否落在league 區間中(基本上應該一定是)
        |   - 檢查"預設level" 是否在區間中, 若是, 返回"預設level"
        |   - 檢查"預設level" 是否比區間最小值小, 若是, 返回區間最小值
        |   - 檢查"預設level" 是否比區間最大值大, 若是, 返回區間最大值
        | 
        | - 其他: 返回實際值
        */
        if (head($levels) <= $this->level && last($levels) >= $this->level) {
            if (head($levels) <= $defaultLevel && last($levels) >= $defaultLevel) {
                return $defaultLevel;
            }

            if (head($levels) > $defaultLevel) {
                return head($levels);
            }

            if (last($levels) < $defaultLevel) {
                return last($levels);
            }
        }        

        return $this->level;
    }

    /**
     * 取得 league 中的實質 cell
     * 
     * @return \App\Model\AmtCell
     */
    public function getChief()
    {
        return is_null($this->league) ? $this : $this->league;
    }

    /**
     * 找到 league 中 Level 最高的
     * 
     * @return \App\Model\AmtCell
     */
    public function findHighest()
    {
        if (is_null($this->league)) {
            return $this;
        }

        return AmtCell::where('league_id', $this->league_id)->orderBy('level', 'desc')->first();
    }

    /**
     * 找到 league 中 Level 最低的
     * 
     * @return \App\Model\AmtCell
     */
    public function findLowest()
    {
        if (is_null($this->league)) {
            return $this;
        }

        return AmtCell::where('league_id', $this->league_id)->orderBy('level', 'asc')->first();
    }

    /**
     * AmtReplicaDiagGroup 在該 Cell 中對應到的 AmtReplicaDiags,
     * 和 Cell 原本所包含的 AmtDiagStandards 配對驗證,
     * 搭配 Cell 定義的檢查邏輯, 最後回傳 boolean
     * 
     * @param  AmtReplicaDiagGroup $replicaGroup
     * @return boolean                      
     */
    public function isPass(AmtReplicaDiagGroup $replicaGroup)
    {
        /**
         * !!注意!!
         * 
         * 此變數會被 statementWouldBeExecuted 中的語句引用, 切不可隨意更改該變數名稱
         * 
         * @var Collection
         */
        $replicaDiags = static::findDoneDiags($replicaGroup);
    
        $statementWouldBeExecuted = AC::setStr($this->getChief()->statement)->convertToStatment();
                
        return eval('return ' . $statementWouldBeExecuted . ';');
    }

    /**
     * 回傳閾值題最後應該呈現的 level
     * 
     * @param  \App\Mpdel\AmtReplicaDiagGroup $replicaGroup
     * @return integer                           
     */
    public function getThreadResultLevel(AmtReplicaDiagGroup $replicaGroup)
    {
        $replicaDiag = static::findDoneDiags($replicaGroup)->first(function ($replicaDiag) {
            return $replicaDiag->diag->type === AmtDiag::TYPE_THREAD_ID;
        });
        
        $child = $replicaGroup->replica->child;

        if (is_null($replicaDiag)) {
            return $replicaGroup->replica->getLevel();
        }

        $value = array_first(json_decode($replicaDiag->value, true));

        return $replicaGroup->replica->getLevel() + (array_get(static::$threadMap, $value) * $this->step);
    }

    /**
     * 取得規則限制內的 level
     * 
     * @param  integer $level
     * @return integer       
     */
    public static function getRestrictLevel($level)
    {
        if ($level < AmtCell::MIN_LEVEL) {
            return AmtCell::MIN_LEVEL;
        }

        if ($level > AmtCell::MAX_LEVEL) {
            return AmtCell::MAX_LEVEL;
        }

        return $level;
    }

    /**
     * 尋找此 Cell 需要回答且尚未回答的 diags
     * 
     * @param  AmtReplicaDiagGroup $replicaGroup
     * @return Collection [\App\Model\AmtReplicaDiag]
     */
    public static function findFreshDiags(AmtReplicaDiagGroup $replicaGroup) 
    {
        $query = static::genFindDiagsQuery($replicaGroup);

        if (is_null($query)) {
            return NULL;
        }

        return $query->whereNull('amt_replica_diags.value')->get();
    }

    /**
     * 尋找此 Cell 需要回答且已經回答的 diags
     * 
     * @param  AmtReplicaDiagGroup $replicaGroup
     * @return mixed Collection[\App\Model\AmtReplicaDiag] | NULL
     */
    public static function findDoneDiags(AmtReplicaDiagGroup $replicaGroup)
    {
        $query = static::genFindDiagsQuery($replicaGroup);
        
        if (is_null($query)) {
            return NULL;
        }

        return $query->whereNotNull('amt_replica_diags.value')->get();
    }

    /**
     * 確認有無為作答的 AmtReplicaDiagGroup
     * 
     * @param  AmtReplicaDiagGroup $replicaGroup
     * @return boolean                       
     */
    public static function hasFreshDiags(AmtReplicaDiagGroup $replicaGroup)
    {
        $diags = static::findFreshDiags($replicaGroup);
        
        return !Wck::isEmpty($diags);
    }

    /**
     * Alias call AmtReplicaDiag::findFreshDiags()
     * 
     * @param  AmtReplicaDiagGroup $replicaGroup
     * @return \Illuminate\Database\Query\Builder                           
     */
    protected static function genFindDiagsQuery(AmtReplicaDiagGroup $replicaGroup)
    {
        return AmtReplicaDiag::findFreshDiags($replicaGroup);
    }
}
