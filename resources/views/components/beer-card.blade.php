<div class="max-w-sm bg-white border-2 border-gray-300 p-6 rounded-md tracking-wide shadow-lg">
    <div id="header" class="flex mb-4">
        <div id="header-text" class="sm">
            <h4 id="name" class="text-l font-semibold text-blue-600">{{ $beer->name }}</h4>
            <h5 id="job" class="font-semibold ">{{ $beer->style }}</h5>
        </div>
    </div>
    <div>
        <div class="text-gray-600">
            Brewed by <strong>{{ $beer->brewery }}</strong>
        </div>
        <div class="text-gray-400 text-right">
            <strong>{{ $beer->review_count }}</strong> reviews.
        </div>
    </div>
</div>
