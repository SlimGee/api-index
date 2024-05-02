<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Exchange until dec 2023') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">

                <form method="POST" action="{{ route('predictions.store') }}">
                    <h2 class="mb-2">Predict Exchange Rate</h2>
                    <div class="flex space-x-3">
                        <div>
                            <input type="hidden" name="prediction" value="exchange_rate">
                            <x-form.input type="text" name="value" placeholder="Enter date (YYYY-MM-DD)"
                                data-controller="datepicker" />
                        </div>
                        <x-button type="submit">Predict Exchange Rate</x-button>
                    </div>
                    <div class="mt-2">
                        @if (Session::has('predicted'))
                            <p>
                                Our model predicts that
                                {{ Carbon\Carbon::parse(Session::get('value'))->addMinutes()->diffForHumans() }} 1 USD
                                {{ Carbon\Carbon::today()->gte(Carbon\Carbon::parse(Session::get('value'))) ? 'was' : 'will be' }}
                                worth
                                <strong>ZWL{{ Number::format(Session::get('predicted'), 2) }}</strong>
                            </p>

                            <div class="mt-3">
                                <strong>AI Insight:</strong>
                                <div class="font-medium text-green-800"> {!! Str::markdown(Session::get('aiResponse')) !!}</div>

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
                                'name' => '1USD = ZWL',
                                'data' => $chartData->values()->all(),
                            ],
                        ],
                        'categories' => $chartData->keys()->all(),
                        'title' => 'ZWL to 1 USD',
                        'subtitle' => 'Exchange Rate until Dec 2023',
                        'colors' => ['#dd2c00'],
                    ]) }}
                    class=""></div>
            </div>

        </div>
    </div>
</x-app-layout>
