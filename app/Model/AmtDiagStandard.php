<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtDiagStandard extends Model
{
    const MIN_LEVEL = 1;
    const MAX_LEVEL = 20;

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

                return $value >= (int) array_get($conditions, 'm') 
                    && $value <= (int) array_get($conditions, 'M')
                ;
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
