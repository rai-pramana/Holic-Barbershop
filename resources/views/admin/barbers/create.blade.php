@extends('layouts.admin')

@section('title', 'Tambah Barber')
@section('page-title', 'Tambah Barber Baru')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.barbers.store') }}">
            @csrf

            <div class="grid sm:grid-cols-2 gap-4 mb-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Barber *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border @error('name') border-red-400 @else border-gray-300 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Cabang *</label>
                    <select name="branch_id" required
                            class="w-full border @error('branch_id') border-red-400 @else border-gray-300 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400">
                        <option value="">— Pilih Cabang —</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Keahlian / Spesialisasi</label>
                    <input type="text" name="specialty" value="{{ old('specialty') }}"
                           placeholder="cth: Skin Fade, Classic Cut"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Bio / Deskripsi</label>
                    <textarea name="bio" rows="3"
                              class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400 resize-none">{{ old('bio') }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', '1') ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-gray-300 text-pink-500 focus:ring-pink-400">
                        <span class="text-sm font-medium text-gray-700">Barber Tersedia (menerima antrean)</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.barbers.index') }}"
                   class="flex-1 text-center bg-gray-100 text-gray-700 font-semibold py-2.5 rounded-xl hover:bg-gray-200 transition-colors text-sm">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-semibold py-2.5 rounded-xl hover:opacity-90 transition-opacity text-sm">
                    Simpan Barber
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
