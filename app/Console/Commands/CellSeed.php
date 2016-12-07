<?php

namespace App\Console\Commands;

use App\Model\Amt;
use App\Model\AmtCell;
use App\Model\AmtDiagGroup;
use Illuminate\Console\Command;

class CellSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cell:seed {amtId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '增殖 AmtCell';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $amtId = $this->argument('amtId');

        Amt::find($amtId)->groups()->get()->each(function ($group) {
            $this->info("{$group->id}開始增殖!\n------------------\n");
            $this->gen($group, NULL, 0);
        });
    }

    public function gen(AmtDiagGroup $group, AmtCell $prev = NULL, $level)
    {
        $cell = AmtCell::create([
            'group_id' => $group->id,
            'level' => $level + 1,
            'prev_id' => is_null($prev) ? NULL : $prev->id,
        ]);

        $cell->update(['next_id' => $cell->id]);

        $this->info("{$cell->id}誕生!");

        return (($level + 1) < 20) ? $this->gen($group, $cell, ($level + 1)) : $this;
    }
}
