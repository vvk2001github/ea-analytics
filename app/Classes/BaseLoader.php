<?php

declare(strict_types=1);

namespace App\Classes;

use DB;
use Exception;
use Illuminate\Support\Facades\Http;
use Log;
use Symfony\Component\Console\Output\ConsoleOutput;

abstract class BaseLoader
{
    protected static $apiUrl = '';

    protected static string $dateFrom = '1970-01-01';

    protected static string $dateTo = '2026-12-31';

    public static function execute(): void
    {
        $total = 1;
        $to = 0;
        $page = 1;
        $lastPage = 1;
        $endpoint = static::$apiUrl;
        $key = config('dataloader.api_key');
        $fullUrl = config('dataloader.api_base_uri').static::$apiUrl;
        $output = new ConsoleOutput;

        while ($total > $to && $page <= $lastPage) {

            // TODO: Не забыть убрать
            if ($page > 3) {
                break;
            }

            $queryParams = [
                'page' => $page,
                'key' => $key,
            ];

            $response = Http::get($fullUrl, array_merge($queryParams, static::getAdditionalQueryParams()));

            if ($response->ok()) {
                try {
                    $body = json_decode($response->body(), true);
                    $total = $body['meta']['total'];
                    $from = $body['meta']['from'];
                    $to = $body['meta']['to'];
                    $lastPage = $body['meta']['last_page'];

                    Log::info('Data loading', ['url' => static::$apiUrl, 'total' => $total, 'to' => $to]);
                    $output->writeln("Loading data from endpoint $endpoint. Page $page. From record $from, to record $to, total records $total");
                    DB::beginTransaction();

                    static::load($body['data']);

                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error('Can not insert data to DB', ['page' => $page, 'message' => $e->getMessage()]);
                    $output->writeln('Can not insert data to DB. Please look at the logs.');
                }
            } else {
                Log::error('Can not load data from API', ['url' => static::$apiUrl, 'page' => $page]);
                $output->writeln('Can not insert data to DB. Please look at the logs.');
            }

            $page++;
        }
    }

    protected static function getAdditionalQueryParams(): array
    {
        return [
            'dateFrom' => static::$dateFrom,
            'dateTo' => static::$dateTo,
        ];
    }

    abstract protected static function load(array &$data): void;
}
