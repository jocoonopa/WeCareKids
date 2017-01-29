<?php

namespace App\Console\Commands;

use AmtAlsRpt as AAR;
use App\Model\AlsRptIbCxt;
use App\Model\Guardian;
use Illuminate\Console\Command;

class MapCxtChildandParent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guardian:repair';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '配對所有 cxt 中的小孩家長';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*
        |--------------------------------------------------------------------------
        | Iterate Cxt to map Guardian and Child 
        |--------------------------------------------------------------------------
        | 1. 檢查 cxt 有無對應 report，若無則 continue
        | 2. 檢查 cxt {家長 + 手機} 有無對應guardian，若無則新增
        | 3. 關聯 $cxt->report->child 和 $guardian 
        |
        |
        */
        AlsRptIbCxt::chunk(50, function ($cxts) {
            $this->info("\n------------------------------------------------------\nGuardian 程序處理開始\n------------------------------------------------------\n");
            foreach ($cxts as $cxt) {
                if (is_null($cxt->report)) {
                    continue;
                }

                /**
                 * 透過電話號碼對應到的家長
                 * 
                 * @var \App\Model\Guardian | NULL
                 */
                $guardian = AAR::procGuardian($cxt);

                $this->info("{$guardian->name} 處理完成\n");
            }

            $this->info("\n------------------------------------------------------\nGuardian 所有程序處理完成\n");
        });
    }
}
