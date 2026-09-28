{{-- Tombol kirim ulang OTP + countdown 60 detik (dipakai verify-otp & verify-email) --}}
<form method="POST" action="{{ $resendRoute }}">
    @csrf
    <button type="submit" id="resend-btn" class="w-full text-center text-gray-400 text-sm hover:text-gray-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed mt-6" disabled>
        Tidak menerima kode? <span class="font-semibold underline underline-offset-2">Kirim ulang</span>
        <span id="resend-countdown" class="text-gray-500">(<span id="resend-secs">60</span>d)</span>
    </button>
</form>

<script>
(function () {
    var secs = 60, el = document.getElementById('resend-secs'),
        btn = document.getElementById('resend-btn'),
        cd = document.getElementById('resend-countdown');
    var t = setInterval(function () {
        secs--;
        if (secs <= 0) {
            clearInterval(t);
            btn.disabled = false;
            if (cd) cd.style.display = 'none';
        } else if (el) {
            el.textContent = secs;
        }
    }, 1000);
})();
</script>
