<?php

namespace App\Console\Commands;

use App\Model\AmtReplica;
use Illuminate\Console\Command;

class ReportCache extends Command
{
    const CHUNK_SIZE = 25;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rpt:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '產生報表快取';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $replicas = AmtReplica::whereNull('step_2_stats')
            ->where('status', AmtReplica::STATUS_DONE_ID)
            ->chunk(static::CHUNK_SIZE, function ($replicas) {
            $replicas->each(function ($replica) {
                AAR::getLevelStats($replica->report);
                
            });
        });
    }
}
