<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border-b-2 border-gray-300 rounded-md p-3">
                <h2 class="text-lg font-bold mb-2">Recommended Beers</h2>
                <ul>
                    @foreach($recommendedBeers as $recommendation)
                        <li class="mb-2">
                            <x-recommendation :recommended-beer=$recommendation></x-recommendation>
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>
    </div>
</x-app-layout>
