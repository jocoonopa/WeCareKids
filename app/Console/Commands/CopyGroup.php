<?php

namespace App\Console\Commands;

use App\Model\AmtDiagGroup;
use Illuminate\Console\Command;

class CopyGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy:group {src} {des}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '複製現有 AmtDiagGroup';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $srcId = $this->argument('src');
        $desId = $this->argument('des');

        AmtDiagGroup::where('amt_id', $srcId)->get()->each(function ($group) use($desId) {
            $group = AmtDiagGroup::create([
                'category_id' => $group->category_id,
                'content' => $group->content,
                'amt_id' => $desId
            ]);

            $this->info("{$group->id}複製完成!");
        });
    }
}
