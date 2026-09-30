@extends('layouts.admin')

@section('title', 'Daftarkan Antrean Walk-in')
@section('page-title', 'Daftarkan Antrean Walk-in')
@section('page-subtitle', 'Buat antrean untuk pelanggan tanpa HP atau tanpa akun (otomatis tervalidasi)')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-gray-800 to-slate-700 px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <h2 class="text-white font-bold text-lg">Antrean Walk-in</h2>
                    <p class="text-gray-300 text-sm">Pelanggan tidak perlu akun — langsung aktif</p>
                </div>
            </div>
        </div>

        {{-- Info banner --}}
        <div class="px-6 pt-5 pb-0">
            <div class="bg-gray-200 border border-gray-300 rounded-xl p-3 flex items-start gap-2 text-sm text-gray-700">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Cocok untuk pelanggan yang tidak membawa HP, anak kecil, atau daftar bersama (misal: bapak dan anak). Antrean ini <strong>otomatis tervalidasi</strong> — tidak perlu scan QR.</span>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.queues.walkin.store') }}" class="p-6 space-y-5">
            @csrf

            {{-- Pilih Cabang --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Cabang <span class="text-red-500">*</span></label>
                @include('components.filter-dropdown', [
                    'id' => 'w-branch', 'name' => 'branch_id', 'label' => '',
                    'icon' => '',
                    'options' => $openBranches->pluck('name', 'id')->toArray(),
                    'value' => (string)($selectedBranch?->id ?? ''),
                    'allLabel' => $openBranches->isEmpty() ? '— Semua Cabang Tutup —' : '— Pilih Cabang —',
                    'theme' => 'form', 'noreload' => true,
                ])
                @error('branch_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            @if($selectedBranch)

            {{-- Pilih Layanan --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Layanan <span class="text-red-500">*</span></label>
                @include('components.filter-dropdown', [
                    'id' => 'w-service', 'name' => 'service_id', 'label' => '',
                    'icon' => '',
                    'options' => $services->mapWithKeys(fn($s) => [$s->id => $s->name.' — Rp '.number_format($s->price, 0, ',', '.').' ('.$s->duration_minutes.' menit)'])->toArray(),
                    'value' => (string)old('service_id', ''),
                    'allLabel' => '— Pilih Layanan —',
                    'theme' => 'form', 'noreload' => true,
                ])
                @error('service_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Pilih Barber --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Barber</label>
                @include('components.filter-dropdown', [
                    'id' => 'w-barber', 'name' => 'barber_id', 'label' => '',
                    'icon' => '',
                    'options' => $barbers->mapWithKeys(fn($b) => [$b->id => $b->name.' — '.$b->pending_count.' antrean menunggu'])->toArray(),
                    'value' => (string)old('barber_id', ''),
                    'allLabel' => 'Otomatis (barber paling sedikit antrean)',
                    'theme' => 'form', 'noreload' => true,
                ])
            </div>

            <hr class="border-gray-100">

            {{-- Data Pelanggan --}}
            <div class="space-y-4">
                <p class="text-sm font-bold text-gray-800">Data Pelanggan</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Pelanggan <span class="text-red-500">*</span></label>
                        <input type="text" name="guest_name" value="{{ old('guest_name') }}" required
                               placeholder="Contoh: Budi Santoso"
                               class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-2.5 focus:ring-2 focus:ring-gray-500 @error('guest_name') border-red-400 @enderror">
                        @error('guest_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor HP <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <input type="tel" name="guest_phone" value="{{ old('guest_phone') }}"
                               placeholder="Contoh: 081234567890"
                               class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-2.5 focus:ring-2 focus:ring-gray-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <textarea name="notes" rows="2" placeholder="Contoh: 2 orang (bapak dan anak), potong pendek"
                              class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-2.5 focus:ring-2 focus:ring-gray-500 resize-none">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <a href="{{ route('admin.queues.manage', ['branch_id' => $selectedBranch->id]) }}"
                   class="flex-1 text-center py-3 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 py-3 rounded-xl bg-gradient-to-r from-gray-800 to-slate-700 text-white text-sm font-bold hover:opacity-90 transition-opacity shadow-sm">
                    Daftarkan Antrean
                </button>
            </div>

            @else
            <div class="text-center py-8 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                <p class="text-sm">Pilih cabang terlebih dahulu</p>
            </div>
            @endif

        </form>
    </div>
</div>

@endsection

@push('scripts')
@include('components.filter-dropdown-script')
<script>
window['dd-change-w-branch'] = function (val) {
    if (val) window.location.href = '{{ route('admin.queues.walkin') }}?branch_id=' + encodeURIComponent(val);
};
</script>
@endpush
