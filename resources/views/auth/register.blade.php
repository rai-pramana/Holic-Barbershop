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
                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-lg ring-1 ring-white/20 flex-shrink-0 overflow-hidden">
                    <img src="/images/holic-logo.png" alt="HOLIC" class="w-12 h-12 object-cover">
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
                           class="w-full bg-gray-800/60 border @error('name') border-red-500 @else border-white/10 @enderror rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-500 transition-colors"
                           placeholder="Nama Anda">
                    @error('name')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full bg-gray-800/60 border @error('email') border-red-500 @else border-white/10 @enderror rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-500 transition-colors"
                           placeholder="email@contoh.com">
                    @error('email')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">Nomor HP <span class="text-gray-600 font-normal">(opsional)</span></label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                           class="w-full bg-gray-800/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-500 transition-colors"
                           placeholder="08xxxxxxxxxx">
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full bg-gray-800/60 border @error('password') border-red-500 @else border-white/10 @enderror rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-500 transition-colors"
                           placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full bg-gray-800/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-500 transition-colors"
                           placeholder="Ulangi password Anda">
                </div>

                <button type="submit"
                        class="w-full bg-white text-gray-900 font-bold py-3.5 rounded-xl hover:bg-gray-100 active:scale-[0.98] transition-all shadow-lg">
                    Buat Akun
                </button>
            </form>

            <p class="text-center text-gray-400 text-sm mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-white hover:text-gray-300 font-semibold underline underline-offset-2">Masuk di sini</a>
            </p>
        </div>
    </div>
</body>
</html>
