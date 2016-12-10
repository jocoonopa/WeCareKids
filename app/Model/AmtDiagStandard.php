<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

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
                    $output .= "{$max}<=,";
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
