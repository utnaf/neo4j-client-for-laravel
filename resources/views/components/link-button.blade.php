@if(!isset($disabled) || !$disabled)
    <a {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150']) }}>
        {{ $slot }}
    </a>
@else
    <div {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest ring-green-300 opacity-25 transition ease-in-out duration-150']) }}>
        {{ $slot }}
    </div>
@endif

