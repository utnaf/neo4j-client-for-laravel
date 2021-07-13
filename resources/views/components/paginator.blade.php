<div class="grid grid-cols-2 gap-0 mb-2">
    <div>
        <x-link-button href="?page={{ $currentPage - 1 }}" disabled="{{ $currentPage == '0' }}">Prev</x-link-button>
    </div>
    <div>
        <x-link-button href="?page={{ $currentPage + 1 }}" disabled="{{ $count != '9' }}" class="float-right">Next</x-link-button>
    </div>
</div>
