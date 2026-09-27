@extends('layouts.app')

@section('title', 'Diagnosa Notifikasi')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('customer.dashboard') }}" class="hover:text-gray-900">Dashboard</a>
        <span>/</span><span class="text-gray-900 font-medium">Diagnosa Notifikasi</span>
    </nav>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h1 class="font-bold text-gray-900 mb-1">Diagnosa Notifikasi HP</h1>
        <p class="text-xs text-gray-500 mb-4">Buka halaman ini di <strong>Chrome HP</strong>, lalu tekan tombol di bawah. Kirim hasilnya (screenshot/teks) ke admin.</p>
        <button id="diag-btn" class="w-full py-3 rounded-xl bg-gray-900 text-white text-sm font-bold">Jalankan Diagnosa</button>
        <pre id="diag-out" class="mt-4 text-[11px] leading-relaxed bg-gray-950 text-green-300 rounded-xl p-4 whitespace-pre-wrap break-all min-h-[200px]">Belum dijalankan.</pre>
    </div>
</div>

<script>
document.getElementById('diag-btn').addEventListener('click', async () => {
    const out = document.getElementById('diag-out');
    const L = [];
    const log = (k, v) => L.push(k + ': ' + v);
    try {
        log('userAgent', navigator.userAgent);
        log('secureContext', window.isSecureContext);
        log('serviceWorker', ('serviceWorker' in navigator));
        log('PushManager', ('PushManager' in window));
        log('Notification.permission', ('Notification' in window) ? Notification.permission : 'TIDAK-ADA');
        if ('serviceWorker' in navigator) {
            const reg = await navigator.serviceWorker.ready;
            log('sw.scope', reg.scope);
            log('sw.active', !!reg.active);
            const sub = await reg.pushManager.getSubscription();
            log('pushSubscription', sub ? 'ADA' : 'TIDAK-ADA');
            if (sub) {
                const j = sub.toJSON();
                log('endpoint', j.endpoint.slice(0, 80) + '...');
                // Cek ke server: apakah endpoint ini terdaftar?
                const csrf = document.querySelector('meta[name="csrf-token"]').content;
                const res = await fetch('{{ route('customer.push.check') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ endpoint: j.endpoint }),
                });
                log('serverTauEndpoint', res.ok ? await res.text() : 'HTTP ' + res.status);
            }
        }
        // Izin baterai/penghemat data tidak bisa dibaca web — diingatkan manual
        L.push('');
        L.push('CATATAN: pastikan Chrome HP > Setelan situs > Notifikasi = Izinkan,');
        L.push('dan HP tidak membatasi background data / battery optimization untuk Chrome.');
    } catch (e) {
        L.push('ERROR: ' + (e && e.message ? e.message : e));
    }
    out.textContent = L.join('\n');
});
</script>
@endsection
