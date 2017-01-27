<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtReplicaLog extends Model
{
    protected $table = 'amt_replica_logs';
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public static function genAppendLog($pairs, $level, \App\Model\AmtReplicaDiagGroup $group = NULL)
    {
        return [
            'd' => array_keys($pairs), 
            'l' => $level, 
            's' => $group->status,
            'g' => $group->id,
            'dir' => $group->dir,
            'cc' => $group->currentCell->id,
            'rc' => is_null($group->resultCell) ? NULL : $group->resultCell->id
        ];
    }

    public function replica()
    {
        return $this->belongsTo('App\Model\AmtReplica', 'replica_id', 'id');
    }

    public function add(array $appendLog)
    {
        $logs = json_decode($this->logs, true);
        $logs[] = $appendLog;
        
        $this->logs = json_encode($logs);

        return $this;
    }

    public function pop()
    {
        $logs = json_decode($this->logs);

        array_pop($logs);

        $this->logs = json_encode($logs);

        return $this;
    }

    public function getLast()
    {
        $logs = json_decode($this->logs);

        return last($logs);
    }
}
