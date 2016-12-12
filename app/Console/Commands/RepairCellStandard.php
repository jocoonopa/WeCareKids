<?php

namespace App\Console\Commands;

use AmtCell as AC;
use App\Model\Amt;
use Illuminate\Console\Command;

class RepairCellStandard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csd:repair {amtId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '修正部分AmtCell 未將 statment 內容轉換綁定 AmtDiagStandard';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("\n開始同步 AmtCell 及 AmtDiagStandard 關聯\n\n");

        $amtId = $this->argument('amtId');
        
        Amt::find($amtId)->cells()->get()->each(function ($cell) {
            AC::setStr($cell->statement)->convertToStatment();
            
            $standards = AC::getStandards();

            $ids = array_pluck($standards->toArray(), 'id');

            $cell->standards()->sync($ids);

            $this->info("{$cell->id} 更新綁定 [" . implode($ids, ',') . "] 完成");
        });

        $this->info("\n--------------------------------\n\n所有關聯處理完成!");
    }
}
