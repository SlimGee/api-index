<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                <h1>CPI and Exchange Rate Prediction</h1>

                <form method="POST" action="{{ route('predictions.store') }}">
                    <h2>Predict CPI</h2>
                    <input type="hidden" name="prediction" value="cpi">
                    <x-form.input type="text" name="value" id="exchange_rate" placeholder="Enter exchange rate" />
                    <button type="submit">Predict CPI</button>
                    <p id="cpi_prediction"></p>
                    {{ session('predicted') }}

                    <h2>Predict Exchange Rate</h2>
                    <x-form.input type="text" id="date" placeholder="Enter date (YYYY-MM-DD)"
                        data-controller="datepicker" />
                    <button onclick="predictExchangeRate()">Predict Exchange Rate</button>
                    <p id="exchange_prediction"></p>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
