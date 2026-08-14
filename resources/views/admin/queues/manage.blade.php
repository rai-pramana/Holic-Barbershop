@extends('layouts.admin')

@section('title', 'Loket Operasional')
@section('page-title', 'Loket Operasional')
@section('page-subtitle', 'Kelola antrean dan check-in customer dalam satu tampilan')

@section('content')

{{-- Branch Selector --}}
<div class="flex flex-wrap items-center gap-3 mb-6">
    @foreach($branches as $branch)
    <a href="{{ route('admin.queues.manage', ['branch_id' => $branch->id]) }}"
       class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all
              {{ $selectedBranch?->id === $branch->id
                  ? 'bg-gradient-to-r from-gray-900 to-slate-800 text-white shadow-lg shadow-gray-900/20'
                  : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-300 hover:text-gray-900' }}">
        {{ $branch->name }}
    </a>
    @endforeach
</div>

{{-- Flash Messages --}}
@if(session('success'))
<div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 text-sm mb-5" id="flash-msg">
    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 text-sm mb-5">
    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
    {{ session('error') }}
</div>
@endif

{{-- Main Grid: left 2 (queue boards) | right 1 (check-in panel) --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- LEFT: Papan Antrean Per Barber --}}
    <div class="xl:col-span-2 space-y-4">

        <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
            Papan Antrean
        </h2>

        @if(!$selectedBranch)
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <p class="font-semibold text-gray-500">Pilih cabang di atas untuk melihat antrean.</p>
        </div>

        @elseif($barbers->isEmpty())
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"/></svg>
            </div>
            <p class="font-semibold text-gray-500">Tidak ada barber aktif di cabang ini.</p>
            <a href="{{ route('admin.barbers.create') }}" class="mt-3 inline-block text-sm text-gray-900 hover:underline">+ Tambah Barber</a>
        </div>

        @else
        <div class="grid sm:grid-cols-2 gap-4" id="barber-boards">
            @foreach($barbers as $barber)
            @php
                $activeQ   = $barber->queues->where('status', 'called')->first();
                $pendingQs = $barber->queues->whereIn('status', ['active', 'pending']);
            @endphp
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" data-barber="{{ $barber->id }}">
                {{-- Barber Header --}}
                <div class="px-5 py-4 bg-gradient-to-r from-gray-900 to-gray-700 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-800 to-slate-700 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($barber->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-white font-bold truncate">{{ $barber->name }}</p>
                        @if($barber->specialty)<p class="text-gray-400 text-xs truncate">{{ $barber->specialty }}</p>@endif
                    </div>
                    <div class="ml-auto text-right flex-shrink-0">
                        <span class="text-xs text-gray-400">Antrean</span>
                        <p class="text-white font-bold text-lg">{{ $barber->queues->count() }}</p>
                    </div>
                </div>
                {{-- Currently Serving --}}
                <div class="px-5 py-4 border-b border-gray-100">
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-3">Sedang Dilayani</p>
                    @if($activeQ)
                    <div class="bg-gray-100 border border-gray-300 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <p class="font-black text-gray-700 text-xl font-mono">{{ $activeQ->queue_number }}</p>
                            <p class="text-gray-700 text-sm font-medium">{{ $activeQ->customer_name }}</p>
                            <p class="text-gray-700 text-xs">{{ $activeQ->service->name }}</p>
                        </div>
                        <div class="flex flex-col gap-2">
                            <form method="POST" action="{{ route('admin.queues.complete', $activeQ) }}">
                                @csrf
                                <button type="submit" class="w-full bg-gray-900 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-gray-800 transition-colors flex items-center justify-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Selesai
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.queues.skip', $activeQ) }}">
                                @csrf
                                <button type="submit" class="w-full bg-gray-200 text-gray-800 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-gray-300 border border-gray-300 transition-colors flex items-center justify-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Lewati
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <svg class="w-8 h-8 text-gray-200 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <p class="text-xs text-gray-400">Belum ada yang dilayani</p>
                    </div>
                    @endif
                </div>
                {{-- Queue List --}}
                <div class="px-5 py-4">
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-3">Antrean Menunggu ({{ $pendingQs->count() }})</p>
                    @forelse($pendingQs->take(5) as $q)
                    <div class="flex items-center gap-3 py-2.5 border-b border-gray-50 last:border-0">
                        <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-700 text-xs font-bold rounded-lg font-mono flex-shrink-0">{{ $q->queue_number }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $q->customer_name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $q->service->name }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            @if($q->status === 'active' && !$activeQ)
                            <form method="POST" action="{{ route('admin.queues.call', $q) }}">@csrf
                                <button type="submit" class="bg-gradient-to-r from-gray-900 to-slate-800 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:opacity-90 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    Panggil
                                </button>
                            </form>
                            @elseif($q->status === 'active')
                            <span class="text-xs font-semibold bg-gray-200 text-gray-500 px-2 py-1 rounded-lg">Hadir</span>
                            @else
                            <span class="text-xs font-semibold bg-gray-50 border border-gray-200 text-gray-600 px-2 py-1 rounded-lg">Menunggu</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-300 text-sm py-3">Tidak ada antrean menunggu</p>
                    @endforelse
                    @if($pendingQs->count() > 5)
                    <p class="text-center text-xs text-gray-400 mt-2">+{{ $pendingQs->count() - 5 }} antrean lagi</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>{{-- /left --}}

    {{-- RIGHT: Panel Check-in --}}
    <div class="xl:col-span-1 space-y-4">

        <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            Loket Check-in
        </h2>

        {{-- QR Code Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100">
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-2">QR Cabang</p>
                <div class="flex flex-wrap gap-2" id="branch-tabs">
                    @foreach($branches as $i => $branch)
                    <button onclick="switchBranch('{{ $branch->id }}', '{{ addslashes($branch->name) }}', '{{ route('customer.checkin.scan', $branch->id) }}')"
                            id="tab-{{ $branch->id }}"
                            class="branch-tab px-3 py-1.5 rounded-lg text-xs font-semibold transition-all
                                   {{ $i === 0 ? 'bg-gradient-to-r from-gray-900 to-slate-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        {{ $branch->name }}
                    </button>
                    @endforeach
                </div>
            </div>
            <div class="p-5 flex flex-col items-center text-center">
                <p class="text-xs text-gray-400 mb-1" id="branch-name">{{ $branches->first()->name ?? '-' }}</p>
                <p class="text-xs text-gray-400 mb-4">Minta customer scan QR ini</p>
                <div class="relative bg-white p-3 rounded-2xl shadow-lg border-4 border-gray-900 mb-4">
                    <div id="qr-canvas" class="w-44 h-44"></div>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-9 h-9 rounded-xl bg-gray-900 flex items-center justify-center shadow">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 w-full">
                    <button onclick="refreshQR()" class="flex-1 flex items-center justify-center gap-1.5 bg-gray-100 text-gray-600 text-xs font-semibold py-2 rounded-xl hover:bg-gray-200 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Refresh
                    </button>
                    <button onclick="printQR()" class="flex-1 flex items-center justify-center gap-1.5 bg-gradient-to-r from-gray-900 to-slate-800 text-white text-xs font-semibold py-2 rounded-xl hover:opacity-90 transition-opacity">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print
                    </button>
                </div>
            </div>
        </div>

        {{-- Manual Input --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">Input Manual</h3>
                    <p class="text-xs text-gray-400">Cari dengan nomor tiket</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.checkin.search') }}" class="space-y-3">
                @csrf
                <input type="text" name="queue_number" value="{{ old('queue_number') }}" placeholder="cth: Q0005"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-mono font-bold uppercase tracking-widest focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400 @error('queue_number') border-red-400 @enderror">
                @error('queue_number')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                <select name="branch_id" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400">
                    <option value="">— Semua Cabang —</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full bg-gradient-to-r from-gray-900 to-slate-800 text-white font-semibold py-2.5 rounded-xl hover:opacity-90 text-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cari Antrean
                </button>
            </form>
        </div>

        {{-- Tervalidasi Hari Ini --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 mb-3 flex items-center justify-between">
                <span class="flex items-center gap-1.5 text-sm">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Tervalidasi Hari Ini
                </span>
                <span class="text-xs bg-gray-100 text-gray-700 font-semibold px-2 py-0.5 rounded-full">
                    {{ \App\Models\Queue::whereDate('created_at', today())->whereNotNull('checked_in_at')->count() }}
                </span>
            </h3>
            @forelse($recent as $q)
            <div class="flex items-center gap-3 py-2.5 border-b border-gray-50 last:border-0">
                <span class="font-mono font-bold text-xs text-gray-700 w-14 flex-shrink-0">{{ $q->queue_number }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $q->customer_name }}</p>
                    <p class="text-xs text-gray-400">{{ $q->checked_in_at->format('H:i') }} · {{ $q->branch->name }}</p>
                </div>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full badge badge-{{ $q->status }} flex-shrink-0">{{ $q->status_label }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">Belum ada yang tervalidasi hari ini.</p>
            @endforelse
        </div>

    </div>{{-- /right --}}

</div>{{-- /main grid --}}

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
let currentUrl = '{{ route('customer.checkin.scan', $branches->first()->id ?? 1) }}';
let currentBranchName = '{{ addslashes($branches->first()->name ?? '') }}';

function generateQR(url) {
    document.getElementById('qr-canvas').innerHTML = '';
    new QRCode(document.getElementById('qr-canvas'), {
        text: url, width: 176, height: 176,
        colorDark: '#111827', colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M,
    });
}
function switchBranch(branchId, branchName, url) {
    currentUrl = url; currentBranchName = branchName;
    document.getElementById('branch-name').textContent = branchName;
    document.querySelectorAll('.branch-tab').forEach(btn => {
        btn.className = btn.className.replace('bg-gradient-to-r from-gray-900 to-slate-800 text-white','').replace('bg-gray-100 text-gray-600 hover:bg-gray-200','').trim();
        btn.classList.add('bg-gray-100','text-gray-600','hover:bg-gray-200');
    });
    const t = document.getElementById('tab-' + branchId);
    if(t){ t.classList.remove('bg-gray-100','text-gray-600','hover:bg-gray-200'); t.classList.add('bg-gradient-to-r','from-gray-900','to-slate-800','text-white'); }
    generateQR(url);
}
function refreshQR() { generateQR(currentUrl); }
function printQR() {
    const c = document.querySelector('#qr-canvas canvas') || document.querySelector('#qr-canvas img');
    if (!c) { alert('QR belum siap.'); return; }
    const d = c.tagName==='CANVAS' ? c.toDataURL() : c.src;
    const w = window.open('','_blank');
    w.document.write(`<!DOCTYPE html><html><head><title>QR - ${currentBranchName}</title>
    <style>body{margin:0;display:flex;align-items:center;justify-content:center;min-height:100vh;font-family:sans-serif}
    .card{text-align:center;padding:40px;border:3px solid #111;border-radius:20px;max-width:320px}
    h1{font-size:1.5rem;font-weight:900;margin:0 0 4px}p{color:#666;font-size:.85rem;margin:0 0 20px}
    img{width:220px;height:220px;display:block;margin:0 auto 20px}.hint{font-size:.75rem;color:#999;margin-top:16px}</style>
    </head><body><div class="card"><h1>HOLIC Barbershop</h1><p>${currentBranchName}</p>
    <img src="${d}" alt="QR"><strong>Scan untuk Check-in</strong>
    <p class="hint">Pastikan sudah login sebelum scan</p></div>
    <script>window.onload=()=>window.print()<\/script></body></html>`);
    w.document.close();
}
document.addEventListener('DOMContentLoaded', () => generateQR(currentUrl));
setInterval(() => { window.location.reload(); }, 15000);
setTimeout(() => {
    const f = document.getElementById('flash-msg');
    if(f) f.style.transition='opacity 0.5s', f.style.opacity='0', setTimeout(()=>f.remove(),500);
}, 4000);
</script>
@endpush
@endsection