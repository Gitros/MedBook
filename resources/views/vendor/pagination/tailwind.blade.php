@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-between mt-4">
        <div class="text-sm text-gray-600">
            @if ($paginator->firstItem())
                Pokazuję <span class="font-semibold">{{ $paginator->firstItem() }}</span>–<span class="font-semibold">{{ $paginator->lastItem() }}</span>
                z <span class="font-semibold">{{ $paginator->total() }}</span>
            @else
                {{ $paginator->count() }} z {{ $paginator->total() }}
            @endif
        </div>

        <div class="inline-flex items-center gap-1">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 text-sm text-gray-400 bg-gray-100 rounded cursor-not-allowed">←</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="px-3 py-1.5 text-sm text-indigo-600 bg-white border border-indigo-200 rounded hover:bg-indigo-50">←</a>
            @endif

            {{-- Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-1.5 text-sm text-gray-500">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1.5 text-sm font-semibold text-white bg-indigo-600 rounded">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                               class="px-3 py-1.5 text-sm text-indigo-600 bg-white border border-indigo-200 rounded hover:bg-indigo-50">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="px-3 py-1.5 text-sm text-indigo-600 bg-white border border-indigo-200 rounded hover:bg-indigo-50">→</a>
            @else
                <span class="px-3 py-1.5 text-sm text-gray-400 bg-gray-100 rounded cursor-not-allowed">→</span>
            @endif
        </div>
    </nav>
@endif
