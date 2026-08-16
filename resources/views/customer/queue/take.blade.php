@extends('layouts.app')

@section('title', 'Ambil Antrean — ' . $branch->name)

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-5">
        <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-1 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>
        <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-700 font-medium">Ambil Antrean</span>
    </nav>

    {{-- Branch Hero --}}
    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-5 md:p-6 text-white mb-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4 pointer-events-none"></div>
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 bg-white/10 border border-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-white/60 text-xs font-medium uppercase tracking-wide mb-0.5">Cabang</p>
                <h1 class="text-lg md:text-xl font-bold truncate">{{ $branch->name }}</h1>
                <p class="text-white/60 text-sm flex items-center gap-1.5 mt-0.5">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    {{ $branch->address }}
                </p>
            </div>
        </div>
    </div>

    {{-- Step indicators --}}
    <div class="flex items-center gap-2 mb-6">
        @foreach(['Pilih Layanan', 'Pilih Barber', 'Catatan'] as $i => $step)
        <div class="flex items-center gap-2 {{ $loop->last ? '' : 'flex-1' }}">
            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-gray-900 to-slate-800 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">{{ $i+1 }}</div>
            <span class="text-xs font-medium text-gray-600 whitespace-nowrap hidden sm:inline">{{ $step }}</span>
            @if(!$loop->last)
            <div class="flex-1 h-px bg-gray-200 hidden sm:block"></div>
            @endif
        </div>
        @endforeach
    </div>

    <form method="POST" action="{{ route('customer.queue.store', $branch) }}" id="take-queue-form" class="space-y-4">
        @csrf

        {{-- Step 1: Service --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-900 text-xs font-bold">1</div>
                <h2 class="font-bold text-gray-900">Pilih Layanan</h2>
                @error('service_id')
                    <span class="text-red-500 text-xs ml-auto">{{ $message }}</span>
                @enderror
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($services as $service)
                <label for="service_{{ $service->id }}" class="cursor-pointer">
                    <input type="radio" id="service_{{ $service->id }}" name="service_id" value="{{ $service->id }}"
                           class="peer sr-only" {{ old('service_id') == $service->id ? 'checked' : '' }}>
                    <div class="border-2 border-gray-100 rounded-xl p-4 peer-checked:border-gray-400 peer-checked:bg-gray-50/60 hover:border-gray-200 transition-all h-full">
                        <div class="flex justify-between items-start gap-2 mb-1.5">
                            <p class="font-semibold text-gray-900 text-sm leading-tight">{{ $service->name }}</p>
                            <span class="text-gray-900 font-bold text-sm whitespace-nowrap flex-shrink-0">{{ $service->formatted_price }}</span>
                        </div>
                        @if($service->description)
                            <p class="text-xs text-gray-500 mb-2 leading-relaxed">{{ $service->description }}</p>
                        @endif
                        <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $service->formatted_duration }}
                        </span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Step 2: Barber --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-900 text-xs font-bold">2</div>
                <div>
                    <h2 class="font-bold text-gray-900">Pilih Barber</h2>
                    <p class="text-xs text-gray-400">Kosongkan untuk barber tercepat otomatis</p>
                </div>
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                {{-- Auto option --}}
                @php
                    $fastestBarber = $barbers->first();
                @endphp
                <label for="barber_auto" class="cursor-pointer">
                    <input type="radio" id="barber_auto" name="barber_id" value="" class="peer sr-only" checked>
                    <div class="border-2 border-gray-100 rounded-xl p-4 peer-checked:border-gray-400 peer-checked:bg-gray-50/60 hover:border-gray-200 transition-all h-full">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-gray-900 to-slate-800 flex items-center justify-center flex-shrink-0 shadow-sm shadow-gray-900/20">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-2 mb-0.5">
                                    <p class="font-semibold text-gray-900 text-sm">Otomatis</p>
                                    <span class="flex-shrink-0 text-xs font-bold px-2 py-0.5 rounded-full bg-slate-900 text-white">
                                        Rekomendasi
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400 truncate mb-1.5">Pilih barber dengan giliran tercepat</p>

                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1">
                                    @if($fastestBarber)
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-600">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            Paling luang: <strong class="font-semibold text-gray-800">{{ $fastestBarber->name }}</strong>
                                        </span>
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $fastestBarber->estimated_wait_minutes > 0 ? '~'.$fastestBarber->estimated_wait_minutes.' menit' : 'Langsung dilayani' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-400">
                                            Barber tersedia
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </label>

                @foreach($barbers as $barber)
                <label for="barber_{{ $barber->id }}" class="cursor-pointer">
                    <input type="radio" id="barber_{{ $barber->id }}" name="barber_id" value="{{ $barber->id }}" class="peer sr-only">
                    <div class="border-2 border-gray-100 rounded-xl p-4 peer-checked:border-gray-400 peer-checked:bg-gray-50/60 hover:border-gray-200 transition-all">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                {{ strtoupper(substr($barber->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-2 mb-0.5">
                                    <p class="font-semibold text-gray-900 text-sm truncate">{{ $barber->name }}</p>
                                    {{-- Queue count badge --}}
                                    <span class="flex-shrink-0 text-xs font-bold px-2 py-0.5 rounded-full
                                        {{ $barber->pending_count === 0
                                            ? 'bg-gray-100 text-gray-500'
                                            : ($barber->pending_count <= 2 ? 'bg-amber-100 text-amber-700' : 'bg-red-50 text-red-600') }}">
                                        {{ $barber->pending_count === 0 ? 'Kosong' : $barber->pending_count . ' antrean' }}
                                    </span>
                                </div>
                                @if($barber->specialty)
                                    <p class="text-xs text-gray-400 truncate mb-1.5">{{ $barber->specialty }}</p>
                                @endif

                                {{-- Stats row --}}
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1">
                                    {{-- Currently serving --}}
                                    @if($barber->current_serving)
                                    <span class="inline-flex items-center gap-1 text-xs text-gray-600">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span class="font-mono font-bold text-gray-700">{{ $barber->current_serving }}</span>
                                        <span class="text-gray-400">dilayani</span>
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 text-xs text-gray-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Sedang kosong
                                    </span>
                                    @endif

                                    {{-- Estimated wait --}}
                                    @if($barber->estimated_wait_minutes > 0)
                                    <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        ~{{ $barber->estimated_wait_minutes }} menit
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Langsung dilayani
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Step 3: Notes --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-900 text-xs font-bold">3</div>
                <div>
                    <h2 class="font-bold text-gray-900">Catatan <span class="text-xs text-gray-400 font-normal">(opsional)</span></h2>
                </div>
            </div>
            <div class="p-4">
                <textarea name="notes" id="notes" rows="3"
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-400/20 transition-all resize-none placeholder:text-gray-400"
                          placeholder="Contoh: minta fade tipis, jangan terlalu pendek...">{{ old('notes') }}</textarea>
            </div>
        </div>

        {{-- Submit --}}
        <div class="sticky bottom-0 md:static z-10
                    bg-white/95 md:bg-transparent
                    backdrop-blur-md md:backdrop-blur-none
                    border-t border-gray-100 md:border-0
                    px-4 pt-3 pb-5 md:p-0
                    -mx-4 md:mx-0 mt-4">

            <button type="submit" id="submit-btn"
                    class="w-full flex items-center justify-center gap-2.5
                           bg-gradient-to-r from-gray-900 to-slate-800
                           text-white font-bold py-4 rounded-2xl
                           hover:opacity-90 active:scale-[0.98] transition-all
                           shadow-lg shadow-gray-900/20 text-base">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                <span>Ambil Nomor Antrean</span>
            </button>

            <a href="{{ route('customer.dashboard') }}"
               class="flex items-center justify-center gap-1.5 mt-3
                      text-gray-500 hover:text-gray-800 text-sm font-medium transition-colors">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

    </form>
</div>

@push('scripts')
<script>
document.getElementById('take-queue-form').addEventListener('submit', function() {
    const btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        Memproses...
    `;
});
</script>
@endpush
@endsection
