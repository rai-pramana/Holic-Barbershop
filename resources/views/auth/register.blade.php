<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — HOLIC Barbershop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>* { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gray-950 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/30">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <p class="text-white font-black text-xl">HOLIC</p>
                    <p class="text-gray-400 text-xs">Barbershop</p>
                </div>
            </a>
        </div>

        <div class="bg-gray-900/80 backdrop-blur border border-white/10 rounded-3xl p-8 shadow-2xl">
            <h1 class="text-2xl font-bold text-white mb-1">Buat Akun Baru</h1>
            <p class="text-gray-400 text-sm mb-6">Daftar gratis dan mulai antrean online</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name --}}
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full bg-gray-800/60 border @error('name') border-red-500 @else border-white/10 @enderror rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-amber-500 transition-colors"
                           placeholder="Nama Anda">
                    @error('name')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full bg-gray-800/60 border @error('email') border-red-500 @else border-white/10 @enderror rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-amber-500 transition-colors"
                           placeholder="email@contoh.com">
                    @error('email')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">Nomor HP <span class="text-gray-600 font-normal">(opsional)</span></label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                           class="w-full bg-gray-800/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-amber-500 transition-colors"
                           placeholder="08xxxxxxxxxx">
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full bg-gray-800/60 border @error('password') border-red-500 @else border-white/10 @enderror rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-amber-500 transition-colors"
                           placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full bg-gray-800/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-amber-500 transition-colors"
                           placeholder="Ulangi password Anda">
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-amber-500 to-amber-600 text-white font-semibold py-3.5 rounded-xl hover:opacity-90 transition-opacity shadow-lg shadow-amber-500/25">
                    Buat Akun
                </button>
            </form>

            <p class="text-center text-gray-400 text-sm mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-amber-500 hover:text-amber-400 font-semibold">Masuk di sini</a>
            </p>
        </div>
    </div>
</body>
</html>
