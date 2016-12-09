<?php

namespace App\Model;

use App\Model\AmtCell;
use Illuminate\Database\Eloquent\Model;

class AmtReplicaDiagGroup extends Model
{
    const STATUS_DONE_ID = 2;
    const STATUS_SKIP_ID = 10;

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
        if (static::STATUS_DONE_ID !== $this->status) {
            return NULL;
        }

        if (is_null($this->dir)) {
            return $this->replica->getLevel();
        }

        if (is_null($this->resultCell)) {
            return 0;
        }

        $chief = $this->resultCell->findMatchedByReplica($this->replica);

        return 0 === $chief->step ? $chief->level : $chief->getThreadResultLevel($this);
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

    public function switchCell($isPass)
    {
        if ($this->isDirTerminate($isPass)) {
            return false;
        }

        $this->update(['dir' => (int) $isPass]);

        return true === $isPass ? $this->swtichToNextCell() : $this->swtichToPrevCell();
    }

    protected function isDirTerminate($isPass)
    {
        return !is_null($this->dir) && ((bool) $this->dir !== (bool) $isPass);
    }

    /*
    |--------------------------------------------------------------------------
    | AmtReplicaDiagGroup 切換到較高等級的 AmtCell
    |--------------------------------------------------------------------------
    | 1. 取得 league 中最高等級之 cell 之 next
    | 2. 若為空, return false
    | 3. 若 next 沒有任何 standard, return false
    | 4. 若有 standard, 更新 AmtReplicaDiagGroup->currentCell
    | 5. 若 standard 對應到的 AmtReplicaDiag 有尚未作答的, 回傳 true
    | 6. 若所有 AmtReplicaDiag 都已經有答案, 進行驗證
    | 7. 驗證過關, 遞迴 $this->swtichToNextCell()
    | 8. 若驗證沒過, return false
    */
    public function swtichToNextCell() 
    {
        if (is_null($this->currentCell)) {
            return false;
        }
        
        $next = $this->currentCell->findHighest()->next;

        if (is_null($next)) {
            return false;
        }

        if ($next->isEmpty()) {// 本身沒有任何standards
            return false;
        }

        $this->update(['current_cell_id' => $next->id]);

        if (AmtCell::hasFreshDiag($this)) {
            return true;
        }

        return $next->isPass($this) ? $this->swtichToNextCell() : false;
    }

    /*
    |--------------------------------------------------------------------------
    | AmtReplicaDiagGroup 切換到較低等級的 AmtCell
    |--------------------------------------------------------------------------
    | 1. 取得 league 中最低等級之 prev
    | 2. 若為空, 則 return false
    | 3. 若 prev 沒有任何 standard, 則回傳 false
    | 4. 若有 standard, 更新 AmtReplicaDiagGroup->currentCell
    | 5. 若 standard 對應到的 replicaDiag 有尚未作答的, 回傳 true
    | 6. 若所有 AmtReplicaDiag 都已經有答案, 進行驗證
    | 7. 若驗證沒過關, 遞迴 $this->swtichToPrevCell()
    | 8. 若驗證過了, return false
    */
    public function swtichToPrevCell() 
    {
        if (is_null($this->currentCell)) {
            return false;
        }

        $prev = $this->currentCell->findLowest()->prev;
        
        if (is_null($prev)) {
            return false;
        }

        if ($prev->isEmpty()) {
            return false;
        }

        $this->update(['current_cell_id' => $prev->id]);

        if (AmtCell::hasFreshDiag($this)) {
            return true;
        }

        return $prev->isPass($this) ? $this->swtichToPrevCell() : false; 
    }
}
