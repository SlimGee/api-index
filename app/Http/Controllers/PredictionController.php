<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GeminiAPI\Resources\Parts\TextPart;
use GeminiAPI\Client;
use Illuminate\Support\Facades\Http;

class PredictionController extends Controller
{
    public function show()
    {
        return view('predictions.show');
    }

    public function store()
    {
        $map = [
            'exchange_rate' => 'http://127.0.0.1:5000/predict_exchange_rate',
            'cpi' => 'http://127.0.0.1:5000/predict_cpi',
        ];
        // Validate the request...
        $validated = request()->validate([
            'prediction' => 'required|string',
            'value' => 'required',
        ]);

        $prediction = $validated['prediction'];
        $value = $validated['value'];

        $response = Http::post($map[$prediction], [
            'value' => $value,
        ]);

        $predicted = $response->json()['predicted'];

        $client = app(Client::class);
        $prompts = [
            'cpi' => 'Explain the reasons behind the predicted CPI of {cpi} given the exchange or ZWL{exchange_rate} to 1 USD.give a very short answer that can be understood by a person who did not see the question.',
            'exchange_rate' => 'What are the top 3 factors that are most likely driving the predicted exchange rate of ZWL{exchange_rate} to 1 USD on {date}?give a very short answer that can be understood by a person who did not see the question.',
        ];

        if ($prediction === 'exchange_rate') {
            $prompt = str_replace('{exchange_rate}', $predicted, $prompts[$prediction]);
            $prompt = str_replace('{date}', Carbon::parse($value)->format('Y-m-d'), $prompt);
        } else {
            $prompt = str_replace('{cpi}', $predicted, $prompts[$prediction]);
            $prompt = str_replace('{exchange_rate}', $value, $prompt);
        }

        $chat = $client->geminiPro()->startChat();
        $response = $chat->sendMessage(new TextPart($prompt));

        $aiResponse = $response->text();

        return back()->with('predicted', $predicted)->with('prediction', $prediction)->with('value', $value)->with('prompt', $prompt)->with('aiResponse', $aiResponse);
    }
}
