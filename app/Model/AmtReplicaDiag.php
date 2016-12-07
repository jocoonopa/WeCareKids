<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtReplicaDiag extends Model
{    
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

    public function standard()
    {
        return $this->belongsTo('App\Model\AmtDiagStandard', 'standard_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo('App\Model\AmtReplicaDiagGroup', 'group_id', 'id');
    }

    public function diag()
    {
        return $this->belongsTo('App\Model\AmtDiag', 'diag_id', 'id');
    }

    public function getResultStandard()
    {
        $standards = $this->diag->standards()->get();

        foreach ($standards as $standard) {
            if (!$standard->isInRange($this)) {
                continue;
            }

            return $standard;
        }

        return NULL;
    }

    public function updateMatchStandard()
    {
        $standard = $this->getResultStandard();
 
        if (is_null($standard)) {
            return;
        }

        // 更新 AmtReplicaDiag 符合的 standard
        $this->update(['standard_id' => $standard->id]);
    }

    public function getMaxLevel()
    {   
        return (0 === $this->standard->step) 
            ? $this->standard->max_level 
            : $this->standard->max_level + $this->standard->step
        ;
    }

    public function getUTF8value()
    {
        $data = json_decode($this->value, true);

        if (!is_array($data)) {
            return $this->value;
        }

        array_walk_recursive($data, function(&$value, $key) {
            if(is_string($value)) {
                $value = urlencode($value);
            }
        });
        
        return urldecode(json_encode($data));
    }

    /**
     * @building
     * 
     * @return boolean
     */
    public function isPass()
    {
        return true;
    }

    public function isInvalidDiag()
    {
        return AmtDiag::TYPE_SWITCH_ID === $this->diag->type && false === $this->value;
    }
}
