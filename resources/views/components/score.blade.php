<div class="flex justify-start items-start">
    <div class="h-3.5 w-24 pt-1.5 text-sm">
        {{ $slot }}
    </div>
    <div class="flex items-center mt-2 mb-0.5" title="{{$score}}">
        @foreach(range(1, round($score)) as $_scoreIdx)
            <div class="rounded-full mr-0.5 w-3.5 h-3.5 inline-block border border-green-400 bg-green-400"></div>
        @endforeach
        @foreach(range(round($score) + 1, 5) as $_scoreIdx)
            <div class="rounded-full mr-0.5 w-3.5 h-3.5 inline-block border border-green-400 bg-green-50"></div>
        @endforeach
    </div>
    <div class="h-3.5 w-24 pt-1.5 text-sm text-gray-400 ml-1">
        ({{ $score }})
    </div>

</div>
