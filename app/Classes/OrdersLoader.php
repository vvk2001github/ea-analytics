<?php

declare(strict_types=1);

namespace App\Classes;

use App\Models\Order;
use Log;

class OrdersLoader extends BaseLoader
{
    protected static $apiUrl = '/api/orders';

    protected static function load(array &$data): void
    {
        // Тут можно добавить валидацию данных
        Log::info(__METHOD__, ['Inserting data to DB']);
        Order::insert($data);
    }
}
