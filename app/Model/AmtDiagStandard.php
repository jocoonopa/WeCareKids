<?php

namespace App\Model;

use App\Model\Child;
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
}
