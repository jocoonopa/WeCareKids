<?php

namespace App\Console\Commands;

use App\Model\Amt;
use App\Model\AmtDiag;
use Illuminate\Console\Command;

class RepairDiag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'diag:repair {amtId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '修正 AmtDiag type 設置錯誤';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->repairType();
    }

    protected function repairType()
    {
        $amtId = $this->argument('amtId');

        $this->info("開始修正Amt::find({$amtId})的 AmtDiag.type ...\n------------------\n");
        
        Amt::find($amtId)->diags()->chunk(100, function ($diags) {
            foreach ($diags as $diag) {
                $diag->update(['type' => $this->getType($diag)]);

                $this->info("[{$diag->description}]   修正完成!");
            }    
        });
        
        $this->info("\n------------------\n修正完成");
    }

    /**
     * 根據 diag 的 available_value 取得對應的正確 type
     * 
     * @param  AmtDiag $diag
     * @return integer       
     */
    protected function getType(AmtDiag $diag)
    {
        $av = json_decode($diag->available_value, true);

        if (!is_array($av)) {
            return AmtDiag::TYPE_SWITCH_ID;
        }
        
        if (array_key_exists('m', $av) || array_key_exists('M', $av)) {
            return AmtDiag::TYPE_SLIDER_ID;
        }

        if (array_key_exists('l', $av) || array_key_exists('e', $av) || array_key_exists('h', $av)) {
            return AmtDiag::TYPE_THREAD_ID;
        }

        return AmtDiag::TYPE_RADIO_ID;
    }
}