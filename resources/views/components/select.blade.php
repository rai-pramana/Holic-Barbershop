{{-- Dropdown bertema rekap: abu lembut, hover putih, fokus ring --}}
{{-- Params: $name, $class (tambahan), $attrs (string atribut: onchange/required/dll), $slot = <option> --}}
@php
    $variant = $variant ?? 'filter'; // 'filter' ringkas | 'form' full-width
    $base = $variant === 'form'
        ? 'w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400 cursor-pointer transition-colors'
        : 'border border-gray-200 rounded-xl pl-3 pr-8 py-1.5 text-sm text-gray-700 bg-gray-50 outline-none hover:border-gray-300 hover:bg-white cursor-pointer transition-colors';
@endphp
<select name="{{ $name }}" {{ $attrs ?? '' }} class="{{ $base }} {{ $class ?? '' }}">
    {{ $slot }}
</select>
