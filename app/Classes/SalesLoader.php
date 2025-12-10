<?php

declare(strict_types=1);

namespace App\Classes;

use App\Models\Sale;
use Log;

class SalesLoader extends BaseLoader
{
    protected static $apiUrl = '/api/sales';

    protected static function load(array &$data): void
    {
        // Тут можно добавить валидацию данных
        Log::info(__METHOD__, ['Inserting data to DB']);
        Sale::insert($data);
    }
}
