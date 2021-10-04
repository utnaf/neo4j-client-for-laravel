<form id="create_review_beer_{{ $beerId }}" class="flex pt-2 mb-2 text-sm" method="POST" action="{{ route("reviews.store") }}">
    @csrf
    <input type="hidden" name="beer_id" value="{{ $beerId }}">
    <h3 class="mr-2">Rate:</h3>
    <ul class="flex">
        @foreach(range(1, 5) as $value)
            <li class="flex-grow">
                <label>
                    <input class="mx-2" type="radio" name="rating" value="{{$value}}"/>
                    {{$value}}
                </label>
            </li>
        @endforeach
    </ul>
    <div class="flex-grow">&nbsp;</div>
    <x-button class="h-6">Ok</x-button>
</form>
