{{-- Input field auth: label + input + error display --}}
{{-- Params: $id, $name, $label, $type='text', $value='', $placeholder='', $required=true, $autofocus=false, $extra='' --}}
<div class="mb-4">
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-300 mb-2">{{ $label }}</label>
    <input type="{{ $type ?? 'text' }}" id="{{ $id }}" name="{{ $name ?? $id }}"
           value="{{ $value ?? '' }}"
           @if($required ?? true) required @endif
           @if($autofocus ?? false) autofocus @endif
           @if(!empty($extra)) {!! $extra !!} @endif
           class="w-full bg-gray-800/60 border @error($name ?? $id) border-red-500 @else border-white/10 @enderror rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-500 transition-colors"
           placeholder="{{ $placeholder ?? '' }}">
    @error($name ?? $id)
        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
    @enderror
</div>
