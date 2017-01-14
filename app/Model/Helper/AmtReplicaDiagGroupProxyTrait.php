<?php

namespace App\Model\Helper;

use App\Model\AmtCell;

trait AmtReplicaDiagGroupProxyTrait
{
    /**
     * Proxy of switchCell
     * 
     * @param  boolean $isPass
     * @return boolean
     */
    public function switchCellProxy($isPass, $command)
    {
        // 若為閾值題，直接返回false
        if ($this->currentCell->isThread()) {
            $command->info("Cell:{$this->currentCell->id} 為閾值型\n");           
            
            $this->update(['dir' => (int) $isPass]);

            $this->bindCurrentCell($this->currentCell);
            $command->line("#Group綁定Cell:{$this->currentCell->id} !\n");

            $command->info("Cell:{$this->currentCell->id} 結束\n");   

            return false;
        }

        if ($this->isDirTerminate($isPass)) {
            $command->line("------ 進入終止判斷 ------!\n");
            $resultCell = true === $isPass ? $this->currentCell->next : $this->currentCell->prev;

            $this->bindCurrentCell($resultCell);
            $command->line("#Group綁定Cell:{$next->id} !\n");

            return false;
        }

        $this->update(['dir' => (int) $isPass]);

        return true === $isPass ? $this->swtichToNextCellProxy($command) : $this->swtichToPrevCell($command);
    }

    protected function swtichToNextCellProxy($command)
    {
        $command->info("\n@往 Next 方向運行 \n=====================================================>\n");

        //1
        if (is_null($this->currentCell)) {
            $command->info("Cell: {$this->currentCell->id} 不存在, 結束跑分\n");

            return false;
        }
        //2
        $next = $this->currentCell->findHighest()->next;
        $diags = AmtCell::findDoneDiags($this);
        $command->info("尋得Next: {$next->id}");
        $command->line('#邏輯條件: ' . $next->statement);
        foreach ($next->standards as $standard) {
            $diag = $standard->getMatched($diags);

            if (!is_null($diag)) {
                $command->line("<red> [{$diag->getUTF8value()}]</red>" . ' => ' . $standard->id . ':' . $standard->diag->description . ':' . $standard->getCondDesc());
            } else {
                $command->line('<magenta>未對應</magenta> => ' . $standard->id . ':' . $standard->diag->description . ':' . $standard->getCondDesc());
            }    
        }
        echo "\n";

        if (is_null($next) || $next->id === $this->currentCell->id) {
            $command->info("Next: {$next->id} 為自己，結束跑分!\n");

            return false;
        }
        //3
        if ($next->isEmpty()) {// 本身沒有任何standards
            $command->info("Next:{$next->id} 為空Cell!");

            return false;
        }
        //4
        $this->bindCurrentCell($next);
        $command->line("#Group綁定Cell:{$next->id} !");

        //5
        if (AmtCell::hasFreshDiags($this)) {
            $command->error("尚有未處理題目!");
            echo "\n";

            return true;
        }
        //5a
        if ($this->currentCell->isEnd()) {
            $command->info("Cell:{$this->currentCell->id} 為末端Cell, 跑分結束!\n");

            return false;
        }
        //5b
        if (!$this->currentCell->isPass($this)) {
            $command->info("<!>Cell:{$this->currentCell->id} 驗證不通過");

            $this->bindCurrentCell($this->currentCell->prev);

            $command->line("#Group綁定Cell:{$this->currentCell->id}, 跑分結束!\n");

            return false;
        }

        //6
        if ($next->isPass($this)) {
            $command->info("\n-------------------- 驗證通過, Go To Next Cell --------------------------\n");

            return $this->swtichToNextCellProxy($command);
        }

        $command->info("Cell:{$next->id} 驗證未通過, 跑分結束!\n");

        return false;
    }

    protected function swtichToPrevCellProxy($command)
    {
        $command->info("\n@往 Prev 方向運行 \n======================================================>\n");

        // 1
        if (is_null($this->currentCell)) {
            $command->info("Cell: {$this->currentCell->id} 不存在, 結束跑分\n");

            return false;
        }
        //2
        $prev = $this->currentCell->findLowest()->prev;
        $diags = AmtCell::findDoneDiags($this);
        $command->info("尋得Prev: {$prev->id}");
        $command->line('邏輯條件: ' . $prev->statement);
        foreach ($prev->standards as $standard) {
            $diag = $standard->getMatched($diags);

            if (!is_null($diag)) {
                $command->line("<red> [{$diag->getUTF8value()}]</red>" . ' => ' . $standard->id . ':' . $standard->diag->description . ':' . $standard->getCondDesc());
            } else {
                $command->line('<magenta>未對應</magenta> => ' . $standard->id . ':' . $standard->diag->description . ':' . $standard->getCondDesc());
            }    
        }
        echo "\n";
        
        if (is_null($prev)) {
            $command->info("Prev不存在, 跑分結束!\n");
            
            return false;
        }
        //3
        if ($prev->isEmpty()) {
            $command->info("Prev:{$prev->id} 為空Cell!");

            $this->bindCurrentCell($prev);

            $command->line("#Group綁定Cell:{$prev->id}, 跑分結束!\n");
             
            return false;
        }
        //4
        $this->bindCurrentCell($prev);
        
        $command->line("#Group綁定Cell:{$prev->id}\n");

        //5
        if (AmtCell::hasFreshDiags($this)) {
            $command->error("尚有未處理題目!");
            echo "\n";

            return true;
        }
        
        //5a
        if ($this->currentCell->isEnd()) {
            $command->info("Cell:{$this->currentCell->id} 為末端Cell, 跑分結束!\n");

            return false;
        }

        //5b
        if ($this->currentCell->isPass($this)) {
            $command->info("<!>Cell:{$this->currentCell->id} 驗證通過, 跑分結束!\n");

            return false;
        }

        //6
        if (!$prev->isPass($this)) {
            $command->info("\n-------------------- 驗證失敗, Go To Prev Cell --------------------------\n");

            return $this->swtichToPrevCellProxy($command);
        } 

        $command->info("Cell:{$this->currentCell->id} 驗證通過, 跑分結束!\n");

        return false;
    }
}