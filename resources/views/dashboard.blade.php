<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="flex justify-between mx-auto space-x-6 max-w-5xl sm:px-6 lg:px-8">
            <div class="overflow-hidden w-full bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-2 text-lg font-semibold">
                        Exchange Rate
                    </div>
                    1USD as to ZWL{{ Number::format($exchangeToday, 2) }}
                </div>
            </div>
            <div class="overflow-hidden w-full bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-2 text-lg font-semibold">
                        CPI
                    </div>
                    {{ Number::format($cpiToday, 2) }}
                </div>
            </div>

            <div class="overflow-hidden w-full bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-2 text-lg font-semibold">
                        AI Insight
                    </div>
                    {!! Str::markdown($aiResponse) !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
