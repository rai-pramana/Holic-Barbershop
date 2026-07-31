@extends('layouts.admin')

@section('title', 'Kelola Cabang')
@section('page-title', 'Cabang Barbershop')
@section('page-subtitle', 'Kelola semua cabang HOLIC Barbershop')

@section('page-actions')
<a href="{{ route('admin.branches.create') }}"
   class="bg-gradient-to-r from-pink-500 to-purple-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:opacity-90 transition-opacity flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Tambah Cabang
</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Nama Cabang</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Alamat</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Barber</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Layanan</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Jam Buka</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($branches as $branch)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900">{{ $branch->name }}</p>
                        @if($branch->phone)
                            <p class="text-xs text-gray-500">{{ $branch->phone }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">{{ $branch->address }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">{{ $branch->barbers_count }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">{{ $branch->services_count }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $branch->open_time }} – {{ $branch->close_time }}</td>
                    <td class="px-6 py-4">
                        @if($branch->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.branches.show', $branch) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Lihat</a>
                            <a href="{{ route('admin.branches.edit', $branch) }}" class="text-gray-600 hover:text-gray-900 text-xs font-medium">Edit</a>
                            <form method="POST" action="{{ route('admin.branches.destroy', $branch) }}"
                                  onsubmit="return confirm('Hapus cabang {{ $branch->name }}? Semua data terkait akan ikut terhapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        Belum ada cabang. <a href="{{ route('admin.branches.create') }}" class="text-pink-600 font-medium">Tambah sekarang</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($branches->hasPages())
    <div class="p-4 border-t border-gray-100">
        {{ $branches->links() }}
    </div>
    @endif
</div>
@endsection
