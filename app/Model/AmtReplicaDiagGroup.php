<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtReplicaDiagGroup extends Model
{
    const STATUS_DONE_ID = 2;

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

    /**
     * 取得該 AmtReplicaDiagGroup 之 測定Level
     * 
     * @return integer 
     */
    public function calculateLevel()
    {
        /*
        |--------------------------------------------------------------------------
        | 計算規則
        |--------------------------------------------------------------------------
        | 
        | 找出隸屬該 Group 且值不為空之 AmtReplicaDiag,
        | 逐一計算其落點 level, 若為 boolean 形態問題注意未通過時將該standard保存為 upThreadStandard,
        | 其 (min_level - 1)為該 Group 最高可能之 level值
        | 通過之 standard collection , 取其中 max_level 最高且未超過 upThreadStandard 的為 resultStandard
        |
        | 若沒有找到符合之 AmtReplicaDiag, 直接以 child->getLevel() 為該 group 之 level
        | 
        | PS: 若有 step 或是為 range型態的記得進行特殊處理
        */
    }

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

    public function isDone()
    {
        return static::STATUS_DONE_ID === $this->status;
    }
}
