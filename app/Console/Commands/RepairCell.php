<?php

namespace App\Console\Commands;

use App\Model\AmtCell;
use Illuminate\Console\Command;

class RepairCell extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cell:repair';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '修復Cell的next錯誤';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("開始修復!\n------------------\n");
        AmtCell::chunk(100, function ($cells) {
            foreach ($cells as $cell) {
                if ($cell->level < 20) {

                    $cell->update(['next_id' => ($cell->id + 1)]);
                    $this->info("{$cell->id}:{$cell->id}->{$cell->next_id}");
                }
            }
        });
        $this->info("\n------------------\n完成");
    }
}
