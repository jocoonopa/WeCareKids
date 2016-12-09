<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtReplicaLog extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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