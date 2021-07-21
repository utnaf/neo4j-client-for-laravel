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
                <h4 class="text-l font-bold text-blue-600">{{ $beer->name }}</h4>
                <h5 class="text-sm">Brewed by <strong>{{ $beer->brewery }}</strong></h5>
                <h6 class="text-sm">{{ $beer->brewery_city }} ({{ $beer->brewery_state }})</h6>
            </div>
        </div>
        <div>
            <ul class="text-sm">
                <li><strong>Style</strong>: {{ $beer->style }}</li>
                <li><strong>Alcohol</strong>: {{ round($beer->abv * 100, 1) }}%</li>
                <li><strong>IBU</strong>: {{ $beer->ibu }}</li>
                <li><strong>Rating</strong>: {{ $beer->rating }}</li>
            </ul>
        </div>
    </div>
</div>
