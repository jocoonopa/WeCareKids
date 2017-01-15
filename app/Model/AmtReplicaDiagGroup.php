<?php

namespace App\Model;

use App\Model\AmtCategory;
use App\Model\AmtCell;
use App\Model\Helper\AmtReplicaDiagGroupProxyTrait;
use Illuminate\Database\Eloquent\Model;

class AmtReplicaDiagGroup extends Model
{
    const STATUS_INIT = 0;
    const STATUS_DONE_ID = 2;
    const STATUS_SKIP_ID = 10;

    use AmtReplicaDiagGroupProxyTrait;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function diags()
    {
        return $this->hasMany('App\Model\AmtReplicaDiag', 'group_id', 'id');
    }

    public function replica()
    {
        return $this->belongsTo('App\Model\AmtReplica', 'replica_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo('App\Model\AmtDiagGroup', 'group_id', 'id');
    }

    public function currentCell()
    {
        return $this->belongsTo('App\Model\AmtCell', 'current_cell_id', 'id');
    }

    public function resultCell()
    {
        return $this->belongsTo('App\Model\AmtCell', 'result_cell_id', 'id');
    }

    /**
     * 取得此 AmtReplicaGroup 的level
     * 
     * @return mixed
     */
    public function getLevel()
    {
        if (!$this->isFinish()) {
            return NULL;
        }

        if ($this->isSkip()) {
            return $this->replica->getLevel();
        }

        if (is_null($this->resultCell)) {
            return 0;
        }

        /**
         * Chief cell
         * 
         * @var \App\Model\AmtCell
         */
        $chief = $this->resultCell->getChief();

        $level = $chief->isThread() ? $chief->getThreadResultLevel($this) : $chief->getLevel($this->replica);

        return AmtCell::getRestrictLevel($level);
    }

    /**
     * 檢查此  AmtReplicaGroup 是否已經完成
     * 
     * @return boolean
     */
    public function isDone()
    {
        return static::STATUS_DONE_ID === $this->status;
    }

    /**
     * 檢查此 AmtReplicaGroup 是否為被略過
     * 
     * @return boolean
     */
    public function isSkip()
    {
        return static::STATUS_SKIP_ID === $this->status;
    }

    public function isFinish()
    {
        return $this->isSkip() || $this->isDone();
    }

    /**
     * 檢查該Group 是否為第一次被訪問
     * 
     * @return boolean
     */
    public function isFirstEntry()
    {
        return is_null($this->dir) && is_null($this->currentCell) && is_null($this->resultCell);
    }

    /**
     * 找到初次進入該 Group, 此 AmtReplica.child 對應到的 Cell
     * 
     * @return \App\Model\AmtCell
     */
    public function findEntryMapCell()
    {
        return $this->group->cells()->where('level', $this->replica->getLevel())->first();
    }

    /**
     * 將目前的 AmtReplica 設定為略過狀態
     * 
     * @return \App\Model\AmtReplica
     */
    public function skip()
    {
        $this->update(['status' => static::STATUS_SKIP_ID]);

        return $this;
    }

    /**
     * 將 AmtReplicaGroup 的狀態更新為終止狀態
     * 
     * @return \App\Model\AmtReplicaGroup
     */
    public function finish()
    {
        // 更新目前作答 group 之 level
        return $this->update([
            'result_cell_id' => (is_null($this->currentCell)) ? NULL : $this->currentCell->id,
            'level' => is_null($this->currentCell) ? 0 : $this->getLevel(),
            'status' => AmtReplicaDiagGroup::STATUS_DONE_ID
        ]);
    }

    /**
     * 根據傳入的standard驗證結果, 切換指向的 AmtCell
     *
     * 若判斷為終止狀態, 移動完畢後回傳 false
     * 若不為終止, 進行重新指向
     * 
     * @param  boolean $isPass
     * @return boolean
     */
    public function switchCell($isPass)
    {   
        // 若為閾值題，直接返回false
        if ($this->currentCell->isThread()) {
             $this->update(['dir' => (int) $isPass]);

             $this->bindCurrentCell($this->currentCell);

             return false;
        }

        if ($this->isDirTerminate($isPass)) {
            $resultCell = true === $isPass ? $this->currentCell->next : $this->currentCell->prev;

            $this->bindCurrentCell($resultCell);

            return false;
        }

        $this->update(['dir' => (int) $isPass]);

        return true === $isPass ? $this->swtichToNextCell() : $this->swtichToPrevCell();
    }

    /*
    |--------------------------------------------------------------------------
    | AmtReplicaDiagGroup 切換到較高等級的 AmtCell
    |--------------------------------------------------------------------------
    | 1. 取得 league 中最高等級之 cell 之 next
    | 2. 若為空或 next和自身id相同, 表示到底了(level20), return false
    | 3. 若 next 沒有任何 standard, return false
    | 4. 若有 standard, 更新 AmtReplicaDiagGroup->currentCell
    | 5. 若 standard 對應到的 AmtReplicaDiag 有尚未作答的, 回傳 true
    | 5a. 若為最末, 驗證完後必定結束
    | 5b. 驗證失敗，終止，綁定回prev
    | 6. 若所有 AmtReplicaDiag 都已經有答案, 進行驗證
    | 7. 驗證過關, 遞迴 $this->swtichToNextCell()
    | 8. 若驗證沒過, return false
    |--------------------------------------------------------------------------
    | 這邊回傳之布林值是用來告訴 controller 切換cell 的動作成功或失敗，
    | 若失敗則controller 要進行 切換 group 的動作
    */
    public function swtichToNextCell() 
    {
        //1
        if (is_null($this->currentCell)) {
            return false;
        }
        //2
        $next = $this->currentCell->findHighest()->next;

        if (is_null($next) || $next->id === $this->currentCell->id) {
            return false;
        }
        //3
        if ($next->isEmpty()) {// 本身沒有任何standards
            return false;
        }
        //4
        $this->bindCurrentCell($next);
        //5
        if (AmtCell::hasFreshDiags($this)) {
            return true;
        }
        //5a
        if ($this->currentCell->isEnd()) {
            return false;
        }
        //5b
        if (!$this->currentCell->isPass($this)) {
            $this->bindCurrentCell($this->currentCell->prev);

            return false;
        }

        //6
        return $next->isPass($this) ? $this->swtichToNextCell() : false;
    }

    /*
    |--------------------------------------------------------------------------
    | AmtReplicaDiagGroup 切換到較低等級的 AmtCell
    |--------------------------------------------------------------------------
    | 1. 取得 league 中最低等級之 prev
    | 2. 若為空, 表示已經到底(level1), 則 return false
    | 3. 若 prev 沒有任何 standard, 則回傳 false
    | 4. 若有 standard, 更新 AmtReplicaDiagGroup->currentCell
    | 5. 若 standard 對應到的 replicaDiag 有尚未作答的, 回傳 true
    | 5a. 若為最末, 驗證完後必定結束
    | 5b. 若驗證過關，則終止，回傳 false
    | 6. 若所有 AmtReplicaDiag 都已經有答案, 進行驗證
    | 7. 若驗證沒過關, 遞迴 $this->swtichToPrevCell()
    | 8. 若驗證過了, return false
    |
    */
    public function swtichToPrevCell() 
    {
        // 1
        if (is_null($this->currentCell)) {
            return false;
        }
        //2
        $prev = $this->currentCell->findLowest()->prev;
        
        if (is_null($prev)) {
            return false;
        }
        //3
        if ($prev->isEmpty()) {
             $this->bindCurrentCell($prev);
             
            return false;
        }
        //4
        $this->bindCurrentCell($prev);
        //5
        if (AmtCell::hasFreshDiags($this)) {
            return true;
        }
        
        //5a
        if ($this->currentCell->isEnd()) {
            return false;
        }

        //5b
        if ($this->currentCell->isPass($this)) {
            return false;
        }

        //6
        return !$prev->isPass($this) ? $this->swtichToPrevCell() : false; 
    }

    protected function bindCurrentCell(AmtCell $cell = NULL)
    {        
        $this->currentCell()->associate($cell);
        $this->save();
    }

    protected function isDirTerminate($isPass)
    {
        return !is_null($this->dir) && ((bool) $this->dir !== (bool) $isPass);
    }
}
