<?php

declare(strict_types=1);

namespace App\Classes;

use App\Models\Income;
use Log;

class IncomesLoader extends BaseLoader
{
    protected static $apiUrl = '/api/incomes';

    protected static function load(array &$data): void
    {
        Log::info(__METHOD__, ['Inserting data to DB']);
        Income::insert($data);
    }
}
