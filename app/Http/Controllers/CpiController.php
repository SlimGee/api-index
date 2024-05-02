<?php

namespace App\Http\Controllers;

use League\Csv\Reader;

class CpiController extends Controller
{
    public function index()
    {
        $csv = Reader::createFromPath(database_path('cpi.csv'), 'r');
        $csv->setHeaderOffset(0);

        $data = [];

        foreach ($csv as $key => $record) {
            $data[$record['Date']] = [
                'monthly_inflation' => $record['Monthly_Inflation_Rate'],
                'annual_inflation' => $record['Annual_Inflation_Rate'],
            ];
        }

        $data = collect($data);

        $monthlyInflation = $data->map(fn($value) => $value['monthly_inflation']);
        $annualInflation = $data->map(fn($value) => $value['annual_inflation']);

        return view('cpi.index', ['data' => $data, 'annualInflation' => $annualInflation, 'monthlyInflation' => $monthlyInflation]);
    }
}
