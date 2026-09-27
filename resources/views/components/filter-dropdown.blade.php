{{-- Tombol filter dropdown kustom bertema tombol Periode (abu, ikon, chevron, menu popup) --}}
{{-- Params: $id (unik), $name, $label, $icon (svg), $options ([value => label]), $value, $allLabel,
     $formId ('history-form' default), $redirect (false — value = URL tujuan),
     $noreload (false — pilih opsi hanya update tampilan + hidden input, tanpa submit/redirect;
                cocok untuk form yg butuh aksi kustom via onchange JS) --}}
@php
    $formId = $formId ?? 'history-form';
    $isRedirect = $redirect ?? false;
    $noReload = $noreload ?? false;
    $labelText = $label ?? '';
    $isForm = ($theme ?? 'filter') === 'form';
@endphp
<div class="relative" data-filter-dropdown="{{ $id }}">
    @if($labelText !== '')
    <label class="block text-xs font-medium text-gray-500 mb-1">{{ $labelText }}</label>
    @endif
    <button type="button" data-dd-btn="{{ $id }}"
            class="flex items-center gap-2 {{ $isForm ? 'w-full bg-white border border-gray-300 px-4 py-2.5 hover:border-gray-400' : 'bg-gray-50 border border-gray-200 px-3 py-1.5 hover:border-gray-300 hover:bg-white min-w-[150px] font-medium' }} rounded-xl text-sm text-gray-700 transition-all cursor-pointer">
        {!! $icon ?? '' !!}
        <span data-dd-label="{{ $id }}" class="truncate">{{ $options[$value] ?? $allLabel }}</span>
        <svg class="w-3 h-3 text-gray-400 ml-auto flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
    </button>
    @if(!$isRedirect)
    <input type="hidden" name="{{ $name }}" id="dd-input-{{ $id }}" value="{{ $value }}">
    @endif
    <div data-dd-menu="{{ $id }}" data-dd-form="{{ $formId }}" data-dd-redirect="{{ $isRedirect ? '1' : '0' }}" data-dd-noreload="{{ $noReload ? '1' : '0' }}"
         class="hidden absolute z-30 mt-1.5 {{ $isForm ? 'w-full' : 'min-w-[150px] right-0' }} max-h-64 overflow-auto bg-white border border-gray-100 rounded-2xl shadow-xl p-1.5">
        @if(!$isRedirect)
        <button type="button" data-dd-val="" data-dd-target="{{ $id }}"
                class="w-full text-left px-3 py-2 rounded-xl text-sm transition-colors {{ ((string)$value === '') ? 'bg-gray-900 text-white font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
            {{ $allLabel }}
        </button>
        @endif
        @foreach($options as $val => $lbl)
        <button type="button" data-dd-val="{{ $val }}" data-dd-target="{{ $id }}"
                class="w-full text-left px-3 py-2 rounded-xl text-sm transition-colors {{ ((string)$value === (string)$val) ? 'bg-gray-900 text-white font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
            {{ $lbl }}
        </button>
        @endforeach
    </div>
</div>
