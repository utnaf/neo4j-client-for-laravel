<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Beers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-paginator count="{{ count($beers) }}" currentPage="{{ \Illuminate\Support\Facades\Request::query('page', 0) }}"></x-paginator>

            <div class="bg-transparent overflow-hidden shadow-sm sm:rounded-lg grid grid-cols-3 gap-4">

                @foreach($beers as $beer)
                    <x-beer-card :beer=$beer></x-beer-card>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>
