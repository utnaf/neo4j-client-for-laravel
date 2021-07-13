@inject('beerImageService', \App\Service\BeerImageService::class)

<div class="max-w-sm bg-white border-b-2 border-gray-300 rounded-md tracking-wide grid grid-cols-5 gap-0">
    <div class="col-span-2">
        <div class="relative rounded-l-md inline-block bg-no-repeat bg-cover bg-center w-full h-full" style="background-image: url('{{ $beerImageService->get() }}')">
            <div title="This beer has {{ $beer->review_count }} reviews"
                 class="absolute bottom-0 left-0 shadow-md w-8 h-8 overflow-hidden text-center rounded-full bg-green-500 inline-block text-xs text-indigo-50 m-1 pt-2">
                {{ $beer->review_count }}
            </div>
        </div>
    </div>
    <div class="col-span-3 p-6">
        <div class="flex mb-4">
            <div class="sm">
                <h4 class="text-l font-semibold text-blue-600">{{ $beer->name }}</h4>
                <h5 class="text-sm font-semibold ">{{ $beer->style }}</h5>
            </div>
        </div>
        <div>
            <div class="text-gray-600">
                Brewed by <strong>{{ $beer->brewery }}</strong>
            </div>
        </div>
    </div>
</div>
