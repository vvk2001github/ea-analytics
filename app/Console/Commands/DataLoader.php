<?php

namespace App\Console\Commands;

use App\Classes\IncomesLoader;
use App\Classes\OrdersLoader;
use App\Classes\SalesLoader;
use App\Classes\StocksLoader;
use App\Models\Income;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Stock;
use Illuminate\Console\Command;

class DataLoader extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->clearDB();

        SalesLoader::execute();
        OrdersLoader::execute();
        StocksLoader::execute();
        IncomesLoader::execute();
    }

    private function clearDB(): void
    {
        Sale::query()->truncate();
        Order::query()->truncate();
        Stock::query()->truncate();
        Income::query()->truncate();
    }
}
