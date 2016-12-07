<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PrintCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'print:category';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將底層分類複製輸出 AmtDiagGroup';

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
        //
    }
}
