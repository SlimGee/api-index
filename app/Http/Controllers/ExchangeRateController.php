<?php

namespace App\Http\Controllers;

use League\Csv\Reader;

class ExchangeRateController extends Controller
{
    public function index()
    {
        $csv = Reader::createFromPath(database_path('exchange_rate.csv'), 'r');
        $csv->setHeaderOffset(0);

        $data = [];

        foreach ($csv as $key => $record) {
            $data[$record['Date']] = $record['Exchange'];
        }

        $data = collect($data);

        $chartData = $data;

        return view('exchange.index', ['data' => $data, 'chartData' => $chartData]);
    }
}
