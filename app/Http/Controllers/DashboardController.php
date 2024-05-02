<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GeminiAPI\Resources\Parts\TextPart;
use GeminiAPI\Client;
use Illuminate\Support\Facades\Http;
use League\Csv\Reader;

class DashboardController extends Controller
{
    public function index()
    {
        $client = app(Client::class);

        $prompts = [
            'Provide a short, impactful statement about the overall direction of the exchange rate in the near future. Include potential implications for CPI, cpi:{cpi},exchange_rate:ZWL{exchange_rate} to 1USD. give a short answer that can be understood by a person who did not see the question.',
            'What is one economic factor, not directly modeled in my forecasts, that could significantly impact the exchange rate? Explain its potential influence. I used timeseries data from 2022 to 2023 to train my model. it forecasts the exchange rate and CPI. cpi:{cpi},exchange_rate:ZWL{exchange_rate} to 1USD. give a short answer that can be understood by a person who did not see the question.',
            'Based on current trends, is the predicted exchange rate trajectory more likely to create risks or opportunities for businesses relying heavily on imported goods? Briefly explain why. cpi:{cpi},exchange_rate:ZWL{exchange_rate} to 1USD',
            'One counterintuitive scenario that could cause the CPI to decrease even if the exchange rate rises is if... cpi:{cpi},exchange_rate:ZWL{exchange_rate} to 1USD. give a short answer that can be understood by a person who did not see the question.',
        ];

        $exchangeToday = Http::post('http://127.0.0.1:5000/predict_exchange_rate', [
            'value' => now()->format('Y-m-d'),
        ])->json()['predicted'];

        $cpiToday = Http::post('http://127.0.0.1:5000/predict_cpi', [
            'value' => $exchangeToday,
        ])->json()['predicted'];

        $prompt = str_replace('{cpi}', $cpiToday, $prompts[array_rand($prompts)]);
        $prompt = str_replace('{exchange_rate}', $exchangeToday, $prompt);

        $chat = $client->geminiPro()->startChat();
        $response = $chat->sendMessage(new TextPart($prompt));

        $aiResponse = $response->text();

        $csv = Reader::createFromPath(database_path('exchange_rate.csv'), 'r');
        $csv->setHeaderOffset(0);

        $data = [];

        foreach ($csv as $key => $record) {
            $data[$record['Date']] = $record['Exchange'];
        }

        $data = collect($data);

        $chartData = $data;

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

        // get inflation rate from cpi data
        $cpiData = $monthlyInflation->groupBy(function ($value, $key) {
            return Carbon::parse($key)->format('Y');
        })->get(2023);

        $inflationRates = $cpiData
            ->slice(1)
            ->mapWithKeys(function ($cpi, $month) use ($cpiData) {
                // dd($cpi, $cpiData->keys());

                $prevMonth = $cpiData->keys()->firstWhere(function ($value, $key) use ($month, $cpiData) {
                    return $key === array_search($month, $cpiData->keys()->all()) - 1;
                });

                $prevCpi = $cpiData->get($prevMonth);
                $inflation = (abs($cpi - $prevCpi) / $prevCpi) * 100;

                return [$month => round($inflation, 2)];
            });

        $annualInflation = $data->map(fn($value) => $value['annual_inflation']);

        return view('dashboard', [
            'exchangeToday' => $exchangeToday,
            'cpiToday' => $cpiToday,
            'prompt' => $prompt,
            'aiResponse' => $aiResponse,
            'chartData' => $chartData,
            'monthlyInflation' => $monthlyInflation,
            'annualInflation' => $annualInflation,
            'inflationRates' => $inflationRates,
        ]);
    }
}
