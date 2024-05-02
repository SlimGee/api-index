<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('CPI data until dec 2023') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">

                <form method="POST" action="{{ route('predictions.store') }}">
                    <h2 class="mb-2">Predict CPI</h2>
                    <div class="flex space-x-3">
                        <div>
                            <input type="hidden" name="prediction" value="cpi">
                            <x-form.input type="text" name="value" id="exchange_rate"
                                placeholder="Enter exchange rate" />

                        </div>
                        <x-button type="submit">Predict CPI</x-button>
                    </div>
                    <div class="mt-2">
                        @if (Session::has('predicted'))
                            <p>
                                Our model predicts that if the exchange rate is
                                <span class="font-bold"> ZWL{{ Number::format(Session::get('value'), 2) }} to 1
                                    USD</span>,
                                then the CPI will be <strong>{{ Number::format(Session::get('predicted'), 2) }}</strong>
                            </p>

                            <div class="mt-3">
                                AI Insight:<div class="font-medium text-green-800"> {!! Str::markdown(Session::get('aiResponse')) !!}</div>
                            </div>
                        @endif
                    </div>
                </form>

            </div>


            <div>
                <div class="bg-white rounded border shadow"
                    {{ stimulus_controller('chart', [
                        'series' => [
                            [
                                'name' => 'Monthly Inflation',
                                'data' => $monthlyInflation->values()->all(),
                            ],
                            [
                                'name' => 'Annual Inflation',
                                'data' => $annualInflation->values()->all(),
                            ],
                        ],
                        'categories' => $monthlyInflation->keys()->all(),
                        'title' => 'CPI',
                        'subtitle' => 'CPI until Dec 2023',
                        'colors' => ['#dd2c00', '#72a8ff'],
                        'config' => [
                            'stroke' => [
                                'curve' => 'straight',
                            ],
                        ],
                    ]) }}>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
