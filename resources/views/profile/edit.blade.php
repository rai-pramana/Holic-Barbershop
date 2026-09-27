@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Kelola data akun dan keamanan Anda')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Informasi Profil --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">Informasi Profil</h2>
            <p class="text-sm text-gray-500">Perbarui nama, email, dan nomor telepon Anda</p>
        </div>
        <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full border @error('name') border-red-400 @else border-gray-300 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-transparent"
                       placeholder="Nama lengkap">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full border @error('email') border-red-400 @else border-gray-300 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-transparent"
                       placeholder="email@contoh.com">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Telepon</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                       class="w-full border @error('phone') border-red-400 @else border-gray-300 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-transparent"
                       placeholder="08xxxxxxxxxx">
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-1">
                <button type="submit"
                        class="bg-slate-900 hover:bg-slate-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors">
                    Simpan Perubahan
                </button>
                <span class="text-xs text-gray-400 capitalize">Role: {{ $user->role }}</span>
            </div>
        </form>
    </div>

    {{-- Ubah Password --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">Ubah Password</h2>
            <p class="text-sm text-gray-500">Pastikan password baru cukup kuat dan berbeda dari sebelumnya</p>
        </div>
        <form method="POST" action="{{ route('profile.password') }}" class="p-6 space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5">Password Saat Ini</label>
                <input type="password" id="current_password" name="current_password" required autocomplete="current-password"
                       class="w-full border @error('current_password') border-red-400 @else border-gray-300 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-transparent"
                       placeholder="••••••••">
                @error('current_password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru</label>
                <input type="password" id="password" name="password" required autocomplete="new-password"
                       class="w-full border @error('password') border-red-400 @else border-gray-300 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-transparent"
                       placeholder="Minimal 8 karakter">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-transparent"
                       placeholder="Ulangi password baru">
            </div>

            <div class="pt-1">
                <button type="submit"
                        class="bg-slate-900 hover:bg-slate-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors">
                    Ubah Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
