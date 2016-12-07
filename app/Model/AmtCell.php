<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtCell extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The standards that belong to the cell
     */
    public function standards()
    {
        return $this->belongsToMany('App\Model\AmtDiagStandard', 'cells_standards', 'cell_id', 'standard_id');
    }

    /**
     * The group that cell belong to 
     */
    public function group()
    {
        return $this->belonsTo('App\Model\AmtDiagGroup', 'group_id', 'id');
    }

    /**
     * The nextCell that cell belong to 
     */
    public function next()
    {
        return $this->belongsTo('App\Model\AmtCell', 'next_id', 'id');
    }

    /**
     * The prevCell that cell belong to 
     */
    public function prev()
    {
        return $this->belongsTo('App\Model\AmtCell', 'prev_id', 'id');
    }

    /**
     * The leagueCell that cell belong to 
     */
    public function league()
    {
        return $this->belongsTo('App\Model\AmtCell', 'league_id', 'id');
    }

    public function currentRpaGroups()
    {
        return $this->hasMany('App\Model\AmtReplicaDiagGroup', 'current_cell_id', 'id');
    }

    public function resultRpaGroups()
    {
        return $this->hasMany('App\Model\AmtReplicaDiagGroup', 'result_cell_id', 'id');
    }
}
