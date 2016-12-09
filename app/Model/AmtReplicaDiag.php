<?php

namespace App\Model;

use App\Model\AmtDiag;
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

    /**
     * 檢查此 AmtReplicaDiag 是否可通過傳入之 AmtDiagStandard 之驗證
     * 
     * @return boolean
     */
    public function isPass()
    {
        $standard = $this->group->currentCell->getDiagMapStandard($this);

        switch ($this->type)
        {
            case AmtDiag::TYPE_SWITCH_ID:
                return $this->procSwitch($standard);
            break;

            case AmtDiag::TYPE_SLIDER_ID:
                return $this->procRange($standard);
            break;

            case AmtDiag::TYPE_RADIO_ID:
                return $this->procRadio($standard);
            break;

            default:
                return false;
            break;
        }

        return false;
    }

    protected function procSwitch(AmtDiagStandard $standard)
    {
        return (bool) $this->value === (bool) $standard->condition_value;
    }

    protected function procRange(AmtDiagStandard $standard)
    {
        $conditions = json_decode($standard->condition_value, true);

        $min = array_get($conditions, 'm');
        $max = array_get($conditions, 'M');

        if (!is_null($min)) {
            if ((int) $this->value < (int) $this->min) {
                return false;
            }
        }

        if (!is_null($max)) {
            if ((int) $this->value > (int) $this->max) {
                return false;
            }
        }

        return true;
    }

    protected function procRadio(AmtDiagStandard $standard)
    {
        $condition = array_first(json_decode($standard->condition_value, true));
        $answer = array_first(json_decode($this->value, true));

        return $answer === $condition;
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
}
