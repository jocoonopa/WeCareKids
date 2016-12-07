<?php

namespace App\Model;

use App\Model\Child;
use Illuminate\Database\Eloquent\Model;

class AmtDiagStandard extends Model
{
    const MIN_LEVEL = 1;
    const MAX_LEVEL = 20;

    public function isRange()
    {
        return $this->min_level < $this->max_level;
    }

    public function hasStep()
    {
        return 0 < $this->step;
    }

    public function getValueByChild(AmtReplicaDiag $replicaDiag, Child $child)
    {
        $level = NULL;
        $diag = $replicaDiag->diag;
        $factor = $replicaDiag->isInvalidDiag() ? -1 : 1;
        
        if ($this->isRange()) {
            $level = $child->getLevel($replicaDiag->group->replica->created_at);

            if ($this->hasStep() && AmtDiag::TYPE_SWITCH_ID === $diag->type) {
                $level = $level + ($this->step * $factor);
            }
        } else {
            $level = $this->min_level;
            
            // standard為 invalid 
            if ($this->hasStep() && AmtDiag::TYPE_SWITCH_ID === $diag->type) {
                $level = $level + ($this->step * $factor);
            } else {
                $level = (-1 === $factor) ? ($level - 1) : $level;
            }
        }

        return $level;
    }

    public function isInRange(AmtReplicaDiag $replicaDiag)
    {
        switch ($replicaDiag->diag->type)
        {
            case AmtDiag::TYPE_SWITCH_ID:
                return true;
            break;

            case AmtDiag::TYPE_CHECKBOX_ID:
                $conditions = json_decode($this->condition_value, true);
                $values = json_decode($replicaDiag->value, true);

                foreach ($conditions as $condition) {
                    if (!in_array($condition, $values)) {
                        return false;
                    }
                }

                return true;
            break;

            case AmtDiag::TYPE_SLIDER_ID:
                $conditions = json_decode($this->condition_value, true);
                $value = json_decode($replicaDiag->value);
                $min = array_get($conditions, 'm');
                $max = array_get($conditions, 'M');

                if (!is_null($min)) {
                    if ($value < $min) {
                        return false;
                    }
                }

                if (!is_null($max)) {
                    if ($value > $max) {
                        return false;
                    }
                }

                return true;
            break;

            case AmtDiag::TYPE_RADIO_ID:
                $conditions = json_decode($this->condition_value, true);
                $values = json_decode($replicaDiag->value, true);

                return array_get($conditions, 0, true) === array_get($values, 0, false);
            break;

            default:
                return false;
            break;
        }

        return false;
    }
}
