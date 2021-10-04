@inject('beerImageService', \App\Service\BeerImageService::class)

<div class="bg-white flex border-1 border-gray-300 rounded-md tracking-wide">
    <div class="h-20 w-20 flex-initial">
        <div class="inline-block rounded-l bg-no-repeat bg-cover bg-center w-full h-full"
             style="background-image: url('{{ $beerImageService->get() }}')">
        </div>
    </div>
    <div class="flex-grow">
        <div class="p-2">
            <h3 class="text-lg font-bold text-yellow-500">{{ $recommendedBeer->beerName }}</h3>
            <h4 class="font-bold">{{ $recommendedBeer->style }}</h4>
            <h4>Recommended because: <strong>{{ implode(',', $recommendedBeer->reason) }}</strong></h4>
        </div>
    </div>
    <x-beer-review-form :beerId="$recommendedBeer->beerId"></x-beer-review-form>

</div>
