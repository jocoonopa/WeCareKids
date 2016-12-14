<?php

namespace App\Model;

use App\Model\AmtDiag;
use App\Model\AmtReplicaDiag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AmtDiagStandard extends Model
{
    const MIN_LEVEL = 1;
    const MAX_LEVEL = 20;

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
     * The cells that belong to the standard
     */
    public function cells()
    {
        return $this->belongsToMany('App\Model\AmtCell', 'cells_standards', 'id', 'cell_id');
    }

    public function diag()
    {
        return $this->belongsTo('App\Model\AmtDiag', 'diag_id', 'id');
    }

    public function isPassWithMacthed(Collection $replicaDiags)
    {
        $replicaDiag = $replicaDiags->first(function ($replicaDiag) { 
            return $this->diag_id === $replicaDiag->diag_id; 
        });

        return $this->isPass($replicaDiag);
    }

    public function isPass(AmtReplicaDiag $replicaDiag)
    {
        switch ($this->diag->type)
        {
            case AmtDiag::TYPE_SWITCH_ID:
                return $this->procSwitch($replicaDiag);
            break;

            case AmtDiag::TYPE_SLIDER_ID:
                return $this->procRange($replicaDiag);
            break;

            case AmtDiag::TYPE_RADIO_ID:
                return $this->procRadio($replicaDiag);
            break;

            default:
                return false;
            break;
        }

        return false;
    }

    public function procSwitch(AmtReplicaDiag $replicaDiag)
    {
        return (bool) json_decode($replicaDiag->value) === (bool) json_decode($this->condition_value);
    }

    public function procRange(AmtReplicaDiag $replicaDiag)
    {
        $conditions = json_decode($this->condition_value, true);
        $answer = json_decode($replicaDiag->value);

        $min = array_get($conditions, 'm');
        $max = array_get($conditions, 'M');

        if (!is_null($min)) {
            if ((float) $answer < (float) $min) {
                return false;
            }
        }

        if (!is_null($max)) {
            if ((float) $answer > (float) $max) {
                return false;
            }
        }

        return true;
    }

    public function procRadio(AmtReplicaDiag $replicaDiag)
    {
        $condition = head(json_decode($this->condition_value, true));
        $answer = head(json_decode($replicaDiag->value, true));
        
        return $answer === $condition;
    }

    public function getCondDesc()
    {
        switch($this->diag->type)
        {
            case AmtDiag::TYPE_SWITCH_ID:
                return true === (bool) $this->condition_value ? '是' : '否';
            break;

            case AmtDiag::TYPE_SLIDER_ID:
                $condition = json_decode($this->condition_value, true);
                $min = array_get($condition, 'm');
                $max = array_get($condition, 'M');
                $output = '';
                
                if (!is_null($min)) {
                    $output .= ">={$min},";
                }

                if (!is_null($max)) {
                    $output .= "<={$max},";
                }

                return substr($output, 0, -1);
            break;

            case AmtDiag::TYPE_RADIO_ID:
                $condition = json_decode($this->condition_value, true);

                return head($condition);
            break;

            default:
                return $this->condition_value;
            break;
        }
    }
}
