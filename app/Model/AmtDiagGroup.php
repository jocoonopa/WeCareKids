<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtDiagGroup extends Model
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

    public function diags()
    {
        return $this->hasMany('App\Model\AmtDiag', 'group_id', 'id');
    }

    public function amt()
    {
        return $this->belongsTo('App\Model\Amt', 'amt_id', 'id');
    }

    public function cells()
    {
        return $this->hasMany('App\Model\AmtCell', 'group_id', 'id');
    }

    public function scopeFindValid($query)
    {
        return $query->where('id', '<', 20);
    }
}
