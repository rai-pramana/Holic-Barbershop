@extends('layouts.admin')

@section('title', 'Tambah Layanan')
@section('page-title', 'Tambah Layanan Baru')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.services.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Cabang *</label>
                    <select name="branch_id" required
                            class="w-full border @error('branch_id') border-red-400 @else border-gray-300 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400">
                        <option value="">Pilih Cabang</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Layanan *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border @error('name') border-red-400 @else border-gray-300 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="2"
                              class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400 resize-none">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi (menit) *</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 30) }}" min="5" max="480" required
                               class="w-full border @error('duration_minutes') border-red-400 @else border-gray-300 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400">
                        @error('duration_minutes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga (Rp) *</label>
                        <input type="number" name="price" value="{{ old('price', 0) }}" min="0" required
                               class="w-full border @error('price') border-red-400 @else border-gray-300 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400">
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked
                               class="w-5 h-5 rounded border-gray-300 text-pink-500 focus:ring-pink-400">
                        <span class="text-sm font-medium text-gray-700">Layanan Aktif</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 mt-6 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.services.index') }}"
                   class="flex-1 text-center bg-gray-100 text-gray-700 font-semibold py-2.5 rounded-xl hover:bg-gray-200 transition-colors text-sm">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-semibold py-2.5 rounded-xl hover:opacity-90 transition-opacity text-sm">
                    Simpan Layanan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
