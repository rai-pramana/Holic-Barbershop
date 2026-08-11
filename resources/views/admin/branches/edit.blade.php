@extends('layouts.admin')

@section('title', 'Edit Cabang')
@section('page-title', 'Edit Cabang: ' . $branch->name)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.branches.update', $branch) }}">
            @csrf @method('PUT')

            <div class="grid sm:grid-cols-2 gap-4 mb-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Cabang *</label>
                    <input type="text" name="name" value="{{ old('name', $branch->name) }}" required
                           class="w-full border @error('name') border-red-400 @else border-gray-300 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-amber-400">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat *</label>
                    <textarea name="address" rows="2" required
                              class="w-full border @error('address') border-red-400 @else border-gray-300 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-amber-400 resize-none">{{ old('address', $branch->address) }}</textarea>
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kota</label>
                    <input type="text" name="city" value="{{ old('city', $branch->city) }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-amber-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $branch->phone) }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-amber-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jam Buka *</label>
                    <input type="time" name="open_time" value="{{ old('open_time', $branch->open_time) }}" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-amber-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jam Tutup *</label>
                    <input type="time" name="close_time" value="{{ old('close_time', $branch->close_time) }}" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-amber-400">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-amber-400 resize-none">{{ old('description', $branch->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kode Antrean <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-500 font-mono font-bold text-sm">Q</span>
                        <input type="text" name="queue_prefix" value="{{ old('queue_prefix', $branch->queue_prefix) }}" required
                               maxlength="3"
                               class="w-24 border @error('queue_prefix') border-red-400 @else border-gray-300 @enderror rounded-xl px-4 py-2.5 text-sm font-mono font-bold focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-amber-400 uppercase">
                        <span class="text-xs text-gray-400">Tiket: <strong>Q{{ $branch->queue_prefix }}001</strong>, <strong>Q{{ $branch->queue_prefix }}002</strong>...</span>
                    </div>
                    @error('queue_prefix') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-400 mt-1">⚠️ Ubah kode hanya jika belum ada antrean aktif hari ini.</p>
                </div>

                <div class="sm:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $branch->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-gray-300 text-gray-900 focus:ring-amber-400">
                        <span class="text-sm font-medium text-gray-700">Cabang Aktif</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.branches.index') }}"
                   class="flex-1 text-center bg-gray-100 text-gray-700 font-semibold py-2.5 rounded-xl hover:bg-gray-200 transition-colors text-sm">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-gradient-to-r from-gray-900 to-slate-800 text-white font-semibold py-2.5 rounded-xl hover:opacity-90 transition-opacity text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
