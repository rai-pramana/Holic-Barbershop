@extends('layouts.admin')

@section('title', 'Riwayat Antrean')
@section('page-title', 'Riwayat Antrean')
@section('page-subtitle', 'Periode: ' . $dateLabel)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
.flatpickr-calendar { font-family: 'Plus Jakarta Sans', sans-serif !important; border-radius: 16px !important; box-shadow: 0 20px 60px rgba(0,0,0,0.18) !important; border: 1px solid #f1f5f9 !important; overflow: hidden; }
.flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange { background: #0f172a !important; border-color: transparent !important; color:#fff !important; }
.flatpickr-day.inRange { background: #e2e8f0 !important; border-color: transparent !important; color: #1e293b !important; }
.flatpickr-day:hover { background: #f1f5f9 !important; border-color: #94a3b8 !important; }
.flatpickr-months { background: linear-gradient(135deg,#1e293b,#334155) !important; padding: 8px 0; }
.flatpickr-month { color:#fff !important; fill:#fff !important; }
.flatpickr-current-month { color:#fff !important; font-size: 14px !important; font-weight: 700 !important; }
.flatpickr-monthDropdown-months { background: #1e293b !important; color: #fff !important; border: none !important; font-weight: 600 !important; font-size: 14px !important; appearance: none; -webkit-appearance: none; cursor: pointer; }
.flatpickr-monthDropdown-months option { background: #1e293b; color: #fff; }
.numInputWrapper input { color: #fff !important; background: transparent !important; font-weight: 700 !important; font-size: 14px !important; border: none !important; }
.numInputWrapper span { border-color: rgba(255,255,255,0.3) !important; }
.numInputWrapper span svg { fill: rgba(255,255,255,0.8) !important; }
.flatpickr-prev-month, .flatpickr-next-month { fill: #fff !important; color: #fff !important; }
.flatpickr-prev-month:hover svg, .flatpickr-next-month:hover svg { fill: #ffffff !important; }
.flatpickr-weekday { color: #94a3b8 !important; font-weight: 700; font-size: 10px; }
.flatpickr-day.today { border-color: #334155 !important; }
.flatpickr-day.flatpickr-disabled { color: #cbd5e1 !important; }
</style>
@endpush

@section('content')

{{-- Hidden inputs for date range --}}
<form method="GET" action="{{ route('admin.queues.index') }}" id="history-form">
<input type="hidden" name="date_from" id="h_date_from" value="{{ request('date_from', today()->toDateString()) }}">
<input type="hidden" name="date_to"   id="h_date_to"   value="{{ request('date_to', today()->toDateString()) }}">

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-3 mb-6">
    <div class="flex flex-wrap gap-2 items-end">

        {{-- Branch filter --}}
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Cabang</label>
            <select name="branch_id" id="filter-branch" onchange="onBranchChange(this.value)"
                    class="border border-gray-200 rounded-xl pl-3 pr-8 py-1.5 text-sm text-gray-700 bg-gray-50 outline-none hover:border-gray-300 hover:bg-white cursor-pointer transition-colors">
                <option value="">Semua Cabang</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Status filter --}}
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <select name="status" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-xl pl-3 pr-8 py-1.5 text-sm text-gray-700 bg-gray-50 outline-none hover:border-gray-300 hover:bg-white cursor-pointer transition-colors">
                <option value="">Semua Status</option>
                @foreach(['pending'=>'Menunggu','active'=>'Check-in','called'=>'Dipanggil','completed'=>'Selesai','skipped'=>'Dilewati','expired'=>'Kedaluwarsa'] as $val => $lbl)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>
        </div>

        {{-- Barber filter --}}
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Barber</label>
            <select name="barber_id" id="filter-barber" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-xl pl-3 pr-8 py-1.5 text-sm text-gray-700 bg-gray-50 outline-none hover:border-gray-300 hover:bg-white cursor-pointer transition-colors">
                <option value="">Semua Barber</option>
                @foreach($barbers as $barber)
                    <option value="{{ $barber->id }}"
                            data-branch="{{ $barber->branch_id }}"
                            {{ request('barber_id') == $barber->id ? 'selected' : '' }}>{{ $barber->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Flatpickr range button --}}
        <div class="ml-auto">
            <label class="block text-xs font-medium text-gray-500 mb-1">Periode</label>
            <div class="relative">
                <button type="button" id="h-date-btn"
                        class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 text-sm font-medium text-gray-700 hover:border-gray-300 hover:bg-white transition-all cursor-pointer min-w-[180px]">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span id="h-date-label">{{ $dateLabel }}</span>
                    <svg class="w-3 h-3 text-gray-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <input type="text" id="h-flatpickr" class="absolute opacity-0 pointer-events-none w-0 h-0">
            </div>
        </div>
    </div>
</div>
</form>


<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">No. Antrean</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Customer</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Barber</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Layanan</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Biaya</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Cabang</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Dibuat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($queues as $queue)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-bold text-gray-900 text-sm">{{ $queue->queue_number }}</td>
                    <td class="px-5 py-3 text-sm">
                        <p class="font-medium text-gray-900">{{ $queue->customer_name }}</p>
                        <p class="text-xs text-gray-500">{{ $queue->customer->phone }}</p>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-700">{{ $queue->barber?->name ?? '—' }}</td>
                    <td class="px-5 py-3 text-sm text-gray-700">
                        <p>{{ $queue->service->name }}</p>
                        <p class="text-xs text-gray-500">{{ $queue->service->duration_minutes }} menit</p>
                    </td>
                    <td class="px-5 py-3 text-sm font-medium text-gray-800">{{ $queue->service->formatted_price }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $queue->branch->name }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-{{ $queue->status }}">
                            {{ $queue->status_label }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $queue->created_at->format('H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">Tidak ada antrean ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($queues->hasPages())
    <div class="p-4 border-t border-gray-100">{{ $queues->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
function fmtDisplay(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });
}

const hFp = flatpickr('#h-flatpickr', {
    mode: 'range',
    maxDate: 'today',
    dateFormat: 'Y-m-d',
    locale: { firstDayOfWeek: 1 },
    defaultDate: [
        document.getElementById('h_date_from').value,
        document.getElementById('h_date_to').value
    ],
    onChange(selectedDates) {
        if (selectedDates.length === 0) return;
        // Use local date getters to avoid UTC timezone shift
        const toISO = d => d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-'+String(d.getDate()).padStart(2,'0');
        const from = toISO(selectedDates[0]);
        const to   = selectedDates.length === 2 ? toISO(selectedDates[1]) : from;

        document.getElementById('h_date_from').value = from;
        document.getElementById('h_date_to').value   = to;

        const label = from === to
            ? fmtDisplay(from)
            : fmtDisplay(from) + ' – ' + fmtDisplay(to);
        document.getElementById('h-date-label').textContent = label;

        // Auto-submit when range is complete
        if (selectedDates.length === 2) {
            document.getElementById('history-form').submit();
        }
    },
});

document.getElementById('h-date-btn').addEventListener('click', () => hFp.open());

// --- Cascade: branch -> barber filter ---
function onBranchChange(branchId) {
    const barberSelect = document.getElementById('filter-barber');
    const options = barberSelect.querySelectorAll('option[data-branch]');
    options.forEach(opt => {
        opt.style.display = (!branchId || opt.dataset.branch === branchId) ? '' : 'none';
        // Reset selection if current barber doesn't belong to selected branch
        if (branchId && opt.selected && opt.dataset.branch !== branchId) {
            barberSelect.value = '';
        }
    });
    document.getElementById('history-form').submit();
}
</script>
@endpush
