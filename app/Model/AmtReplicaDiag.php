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

    public function getMaxLevel()
    {   
        return (0 === $this->standard->step) 
            ? $this->standard->max_level 
            : $this->standard->max_level + $this->standard->step
        ;
    }

    public function isInvalidDiag()
    {
        return AmtDiag::TYPE_SWITCH_ID === $this->diag->type && false === $this->value;
    }

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
}
