{{-- Header kolom sortable: klik untuk sort asc/desc, panah indikator arah aktif --}}
{{-- Params: $label, $key, $current (kolom aktif), $dir ('asc'|'desc'), $align='text-left', $class='px-6 py-3' --}}
@php
    $isActive = ($current ?? null) === $key;
    $nextDir = ($isActive && ($dir ?? 'asc') === 'asc') ? 'desc' : 'asc';
    $qs = array_merge(request()->except(['sort', 'dir', 'page']), ['sort' => $key, 'dir' => $nextDir]);
@endphp
<th class="{{ $align ?? 'text-left' }} text-xs font-semibold uppercase tracking-wide {{ $class ?? 'px-6 py-3' }} {{ $isActive ? 'text-gray-900' : 'text-gray-500' }}">
    <a href="{{ request()->url() . '?' . http_build_query($qs) }}" class="inline-flex items-center gap-1 hover:text-gray-900 transition-colors">
        {{ $label }}
        @if($isActive)
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                @if(($dir ?? 'asc') === 'asc')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
                @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                @endif
            </svg>
        @else
            <svg class="w-3 h-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
            </svg>
        @endif
    </a>
</th>
