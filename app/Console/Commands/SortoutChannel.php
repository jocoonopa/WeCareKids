<?php

namespace App\Console\Commands;

use App\Model\AlsRptIbChannel;
use App\Model\User;
use DB;
use Illuminate\Console\Command;

class SortoutChannel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'channel:sortout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '為所有使用者建立一個新的channel, 並將原本的cxt 都歸納到此cahnnel 下';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("\n---------------開始搬移使用者 cxts ---------------\n\n");  

        DB::beginTransaction();
        try {
            User::chunk(50, function ($users) {
                $users->each(function ($user) {
                    $this->info("\n{$user->name}開始處理...");   
                    $channel = AlsRptIbChannel::createPrototype($user);
                    $channel->save();

                    $this->info("\n-- {$channel->id}頻道建立完成");      
                });
            });

            User::chunk(50, function ($users) {
                $users->each(function ($user) {
                    $this->moveCxts($user);

                    $this->info("\n{$user->name}處理完成!\n\n");         

                    $this->removeUselessChannels($user);                
                });
            });

            DB::commit();

            $this->info("\n--------------- 搬移結束 ---------------\n\n");    
        } catch (\Exception $e) {
            DB::rollback();

            $this->info("\n{$e->getMessage()}");
        }
    }

    /**
     * 搬移使用者擁有的 cxts 至新增的channel
     * 
     * @param  User $user    
     */
    protected function moveCxts(User $user)
    {
        $user->cxts->each(function ($cxt) {
            if (is_null($cxt->report)) {
                return;
            }

            $cxt->channel()->associate($cxt->report->owner->channels()->orderBy('id', 'desc')->first());
            $cxt->save();

            $this->info("\n---- cxt:{$cxt->id}搬移完成!");
        });
    }

    /**
     * 移除使用者擁有的沒必要channel
     * 
     * @param  User $user
     */
    protected function removeUselessChannels(User $user)
    {
        $count = $user->channels->count();
        $channels = $user->channels->slice(0, $count - 1);
        foreach ($channels->all() as $channel) {
            $channel->delete();
        }
    }
}
