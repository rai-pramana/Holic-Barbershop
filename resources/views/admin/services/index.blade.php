@extends('layouts.admin')

@section('title', 'Kelola Layanan')
@section('page-title', 'Layanan')
@section('page-subtitle', 'Kelola semua layanan barbershop per cabang')

@section('page-actions')
<a href="{{ route('admin.services.create') }}"
   class="bg-gradient-to-r from-pink-500 to-purple-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:opacity-90 transition-opacity flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Tambah Layanan
</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Nama Layanan</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Cabang</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Durasi</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Harga</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($services as $service)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900">{{ $service->name }}</p>
                        @if($service->description)
                            <p class="text-xs text-gray-500 mt-0.5 max-w-xs truncate">{{ $service->description }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $service->branch->name }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 text-sm text-gray-700 bg-gray-100 px-2.5 py-1 rounded-full">
                            <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $service->formatted_duration }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $service->formatted_price }}</td>
                    <td class="px-6 py-4">
                        @if($service->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.services.edit', $service) }}" class="text-gray-600 hover:text-gray-900 text-xs font-medium">Edit</a>
                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}"
                                  onsubmit="return confirm('Hapus layanan {{ $service->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        Belum ada layanan. <a href="{{ route('admin.services.create') }}" class="text-pink-600 font-medium">Tambah sekarang</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($services->hasPages())
    <div class="p-4 border-t border-gray-100">{{ $services->links() }}</div>
    @endif
</div>
@endsection
