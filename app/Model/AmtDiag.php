<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtDiag extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public static $types = ['是非', '选项', '范围', '单选', '阈值'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    const TYPE_SWITCH_ID = 0;
    const TYPE_CHECKBOX_ID = 1;
    const TYPE_SLIDER_ID = 2;
    const TYPE_RADIO_ID = 3;
    const TYPE_THREAD_ID = 4;

    public function group()
    {
        return $this->belongsTo('App\Model\AmtDiagGroup');
    }

    public function standards()
    {
        return $this->hasMany('App\Model\AmtDiagStandard', 'diag_id', 'id');
    }

    public function getTypeName()
    {
        return array_get(static::$types, $this->type);
    }
}
