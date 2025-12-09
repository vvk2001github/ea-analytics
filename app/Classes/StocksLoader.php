<?php

declare(strict_types=1);

namespace App\Classes;

use App\Models\Stock;
use Carbon\Carbon;
use Log;

class StocksLoader extends BaseLoader
{
    protected static $apiUrl = '/api/stocks';

    protected static function load(array &$data): void
    {
        Log::info(__METHOD__, ['Inserting data to DB']);
        Stock::insert($data);
    }

    protected static function getAdditionalQueryParams(): array
    {
        $today = Carbon::now()->format('Y-m-d');

        return [
            'dateFrom' => $today,
        ];
    }
}
