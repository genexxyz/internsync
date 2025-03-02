@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 text-medium font-medium text-gray-500 bg-gray-200 rounded-md">
                <i class="fa fa-chevron-left text-sm"></i>Previous
            </span>
        @else
            <button wire:click="previousPage" rel="prev" class="inline-flex items-center px-4 py-2 text-medium font-medium text-secondary hover:text-accent transition ease-in-out duration-150">
                <i class="fa fa-chevron-left text-sm"></i>Previous
            </button>
        @endif

        {{-- Pagination Links --}}
        <div>
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 ">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 border-secondary border-2  rounded-md">{{ $page }}</span>
                        @else
                            <button wire:click="gotoPage({{ $page }})" class="inline-flex items-center px-4 py-2 text-sm bg-secondary shadow-md font-medium text-white hover:bg-accent rounded-md transition ease-in-out duration-150">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage" rel="next" class="inline-flex items-center px-4 py-2 text-medium font-medium text-secondary hover:text-accent transition ease-in-out duration-150">
                Next <i class="fa fa-chevron-right text-sm"></i>
            </button>
        @else
            <span class="inline-flex items-center px-4 py-2 text-medium font-medium text-gray-500 bg-gray-200 rounded-md">
                Next<i class="fa fa-chevron-right text-sm"></i>
            </span>
        @endif
    </nav>
@endif
