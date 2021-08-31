@inject('beerImageService', \App\Service\BeerImageService::class)

<div class="max-w-sm bg-white border-b-2 border-gray-300 rounded-md tracking-wide">
    <div class="h-56">
        <div class="relative rounded-t-md inline-block bg-no-repeat bg-cover bg-center w-full h-full"
             style="background-image: url('{{ $beerImageService->get() }}')">
            <div title="This beer has {{ $beer->review_count }} reviews"
                 class="absolute top-0 right-0 shadow-md w-8 h-8 overflow-hidden text-center rounded-full bg-green-500 inline-block text-xs text-indigo-50 m-1 pt-2">
                {{ $beer->review_count }}
            </div>
        </div>
    </div>
    <div class="p-4">
        <div class="flex mb-4">
            <div class="sm">
                <h4 class="text-lg font-bold text-yellow-500">{{ $beer->name }}</h4>
                <h5 class="text-sm">Brewed by <strong>{{ $beer->brewery->name }}</strong></h5>
                <h6 class="text-sm">{{ $beer->brewery->city }} ({{ $beer->brewery->state }})</h6>
            </div>
        </div>
        <div>
            <ul class="text-sm">
                <li><strong>Style</strong>: {{ $beer->style }}</li>
                <li><strong>Alcohol</strong>: {{ round($beer->abv * 100, 1) }}%</li>
                <li><strong>IBU</strong>: {{ $beer->ibu ?? '-' }}</li>
                <li><strong>Rating</strong>: {{ round($beer->rating, 1) }}</li>
            </ul>
        </div>
        @if(!$beer->reviewed_by_user)
            <hr class="my-2" />
            <form id="create_review_beer_{{ $beer->id }}" class="flex pt-2 mb-2 text-sm" method="POST" action="{{ route("reviews.store") }}">
                @csrf
                <input type="hidden" name="beer_id" value="{{ $beer->id }}">
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
                <x-button>Ok</x-button>
            </form>

        @endif
    </div>
</div>
