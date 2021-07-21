<a {{ $attributes->merge(['class' =>
    'inline-flex items-center px-4 py-2 bg-gray-300 text-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-200 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-100 disabled:opacity-25 transition ease-in-out duration-150'])
}}>
    {{ $slot }}
</a>
