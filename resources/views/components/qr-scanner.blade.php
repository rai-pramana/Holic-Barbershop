{{-- Modal scanner QR check-in + logika kamera (dipakai dashboard & status customer) --}}
<div id="qr-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b border-gray-100">
            <div>
                <h3 class="font-bold text-gray-900">Scan QR Check-in</h3>
                <p class="text-xs text-gray-400 mt-0.5">Arahkan kamera ke QR di loket barbershop</p>
            </div>
            <button onclick="closeQrScanner()" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-4">
            <div id="qr-reader" class="rounded-xl overflow-hidden"></div>
            <p id="qr-status" class="text-center text-sm text-gray-500 mt-3">Menginisialisasi kamera...</p>
            {{-- Fallback: coba lagi + upload foto QR (muncul saat kamera gagal) --}}
            <div id="qr-fallback" class="hidden mt-3 space-y-2">
                <button id="qr-retry" type="button" onclick="retryQrCamera()"
                        class="hidden w-full py-2.5 rounded-xl bg-gray-900 text-white text-sm font-bold hover:bg-gray-800 transition-colors">
                    🔄 Coba Lagi
                </button>
                <label class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-50 transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Upload Foto QR
                    <input type="file" accept="image/*" class="hidden" onchange="handleQrFile(this)">
                </label>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let html5QrCode = null;

function openQrScanner() {
    const modal = document.getElementById('qr-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Kamera browser hanya diizinkan pada HTTPS atau localhost.
    if (!window.isSecureContext) {
        document.getElementById('qr-status').innerHTML =
            '⚠️ Browser memblokir kamera karena koneksi tidak aman.<br>' +
            'Buka aplikasi via <strong>https://</strong> atau <strong>http://localhost:8000</strong>, ' +
            'atau gunakan <strong>Upload Foto QR</strong> di bawah.';
        showQrFallback();
        return;
    }

    document.getElementById('qr-status').textContent = 'Menginisialisasi kamera...';
    hideQrFallback();

    html5QrCode = new Html5Qrcode('qr-reader');

    const config = { fps: 10, qrbox: { width: 240, height: 240 } };

    html5QrCode.start(
        { facingMode: 'environment' },
        config,
        (decodedText) => {
            if (html5QrCode) html5QrCode.stop().catch(() => {});
            handleQrDecoded(decodedText);
        },
        (errorMessage) => {
            // Ignore scan errors — normal during scanning
        }
    ).then(() => {
        document.getElementById('qr-status').textContent = 'Scan QR yang ada di loket barbershop';
    }).catch((err) => {
        const name = (err && err.name) || '';
        let msg;
        if (name === 'NotAllowedError') {
            msg = '⚠️ Izin kamera ditolak. Ketuk ikon 🔒/🎥 di address bar → izinkan kamera → tekan Coba Lagi.';
        } else if (name === 'NotFoundError' || name === 'OverconstrainedError') {
            msg = '⚠️ Tidak ada kamera yang ditemukan di perangkat ini. Gunakan Upload Foto QR di bawah.';
        } else if (name === 'NotReadableError') {
            msg = '⚠️ Kamera sedang dipakai aplikasi lain (Zoom/Meet/dll). Tutup aplikasi itu → Coba Lagi.';
        } else if (name === 'NotSupportedError') {
            msg = '⚠️ Browser tidak mendukung akses kamera. Gunakan Chrome/Edge/Safari terbaru atau Upload Foto QR.';
        } else {
            msg = '⚠️ Kamera tidak dapat dibuka (' + (err && err.message ? err.message : err) + '). Coba lagi atau gunakan Upload Foto QR.';
        }
        document.getElementById('qr-status').innerHTML = msg;
        showQrFallback();
    });
}

// --- Fallback: scan dari file foto + tombol coba lagi ---
function showQrFallback() {
    document.getElementById('qr-fallback').classList.remove('hidden');
    document.getElementById('qr-retry').classList.remove('hidden');
}
function hideQrFallback() {
    document.getElementById('qr-fallback').classList.add('hidden');
    document.getElementById('qr-retry').classList.add('hidden');
}
function retryQrCamera() {
    document.getElementById('qr-retry').classList.add('hidden');
    openQrScanner();
}

function handleQrFile(input) {
    const file = input.files && input.files[0];
    if (!file) return;
    document.getElementById('qr-status').textContent = 'Membaca foto QR...';
    const reader = new Html5Qrcode('qr-reader');
    reader.scanFile(file, true).then(handleQrDecoded, () => {
        document.getElementById('qr-status').textContent = '⚠️ QR tidak terbaca dari foto. Pastikan foto jelas dan coba lagi.';
    });
    input.value = '';
}

function handleQrDecoded(decodedText) {
    document.getElementById('qr-status').textContent = '✅ QR berhasil dibaca, mengalihkan...';
    // Only follow URLs from our domain
    try {
        const url = new URL(decodedText);
        if (url.host === window.location.host) {
            window.location.href = decodedText;
        } else {
            document.getElementById('qr-status').textContent = '⚠️ QR tidak valid untuk aplikasi ini.';
        }
    } catch(e) {
        document.getElementById('qr-status').textContent = '⚠️ Format QR tidak dikenali.';
    }
}

function closeQrScanner() {
    const modal = document.getElementById('qr-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    if (html5QrCode) {
        html5QrCode.stop().catch(() => {});
        html5QrCode = null;
    }
}

// Close modal if clicking backdrop
document.getElementById('qr-modal').addEventListener('click', function(e) {
    if (e.target === this) closeQrScanner();
});
</script>
@endpush
