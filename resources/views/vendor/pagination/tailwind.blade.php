@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
    <div class="text-xs text-gray-500">
        Menampilkan
        <span class="font-semibold text-gray-700">{{ $paginator->firstItem() }}</span>
        –
        <span class="font-semibold text-gray-700">{{ $paginator->lastItem() }}</span>
        dari
        <span class="font-semibold text-gray-700">{{ $paginator->total() }}</span>
        data
    </div>

    <div class="flex items-center gap-1">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-gray-300 cursor-not-allowed select-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-gray-500 hover:bg-amber-50 hover:text-amber-700 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-8 h-8 text-gray-400 text-xs">…</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 text-white text-xs font-bold shadow-sm shadow-amber-200">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-gray-600 text-xs font-medium hover:bg-amber-50 hover:text-amber-700 transition-all">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-gray-500 hover:bg-amber-50 hover:text-amber-700 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        @else
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-gray-300 cursor-not-allowed select-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </span>
        @endif
    </div>
</nav>
@endif
