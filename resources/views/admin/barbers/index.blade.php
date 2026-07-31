@extends('layouts.admin')

@section('title', 'Kelola Barber')
@section('page-title', 'Barber')
@section('page-subtitle', 'Kelola semua barber HOLIC Barbershop')

@section('page-actions')
<a href="{{ route('admin.barbers.create') }}"
   class="bg-gradient-to-r from-pink-500 to-purple-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:opacity-90 transition-opacity flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Tambah Barber
</a>
@endsection

@section('content')
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($barbers as $barber)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                {{ substr($barber->name, 0, 1) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-bold text-gray-900 truncate">{{ $barber->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $barber->phone ?? "-" }}</p>
                @if($barber->specialty)
                    <p class="text-xs text-pink-600 font-medium mt-1">{{ $barber->specialty }}</p>
                @endif
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100 space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Cabang</span>
                <span class="font-medium text-gray-800 text-right max-w-[60%] truncate">{{ $barber->branch->name }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Status</span>
                <span class="font-medium {{ $barber->is_available ? 'text-green-600' : 'text-red-500' }}">
                    {{ $barber->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                </span>
            </div>
        </div>

        <div class="flex gap-2 mt-4">
            <a href="{{ route('admin.barbers.edit', $barber) }}"
               class="flex-1 text-center text-sm font-medium bg-gray-100 text-gray-700 py-2 rounded-xl hover:bg-gray-200 transition-colors">
                Edit
            </a>
            <form method="POST" action="{{ route('admin.barbers.destroy', $barber) }}"
                  onsubmit="return confirm('Hapus barber {{ $barber->name }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-sm font-medium bg-red-50 text-red-600 py-2 px-3 rounded-xl hover:bg-red-100 transition-colors">
                    Hapus
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-3 bg-white rounded-2xl border border-gray-100 p-12 text-center text-gray-400">
        Belum ada barber. <a href="{{ route('admin.barbers.create') }}" class="text-pink-600 font-medium">Tambah sekarang</a>.
    </div>
    @endforelse
</div>

@if($barbers->hasPages())
<div class="mt-6">{{ $barbers->links() }}</div>
@endif
@endsection
