@inject('beerImageService', \App\Service\BeerImageService::class)

<div class="bg-white flex border-b-2 border-gray-300 rounded-md tracking-wide">
    <div class="h-20 w-20 flex-initial">
        <div class="inline-block rounded-l bg-no-repeat bg-cover bg-center w-full h-full"
             style="background-image: url('{{ $beerImageService->get() }}')">
        </div>
    </div>
    <div class="flex-grow">
        <div class="p-2">
            <h3 class="text-lg font-bold text-yellow-500">{{ $review->beerName }}</h3>
            <h4 class="text-sm">{{ $review->beerStyle }}</h4>
        </div>
    </div>
    <div>
        <div class="text-green-500 font-bold text-2xl mt-6 mr-5">
            {{ $review->rating }}
        </div>
    </div>
</div>
