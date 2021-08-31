<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reviews') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($reviews->count() === 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3>You haven't wrote any review yet.</h3>
                </div>
            @else
                <div class="bg-transparent overflow-hidden grid grid-cols-2 gap-4">
                    @foreach($reviews as $review)
                        <x-review-card :review=$review></x-review-card>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
