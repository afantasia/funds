<?php

namespace App\Console\Commands;

use App\Http\Controllers\StockController;
use Illuminate\Console\Command;

class MakeNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'makeNews';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '버러지 만들기';

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
     * @return int
     */
    public function handle()
    {
        $stockController=new StockController();
        $stockController->makeNews();
        return 0;
    }
}
