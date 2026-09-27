{{-- Penggerak komponen filter-dropdown: buka/tutup menu, pilih opsi → submit form / redirect --}}
<script>
(function () {
    document.querySelectorAll('[data-dd-btn]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var id = btn.getAttribute('data-dd-btn');
            var menu = document.querySelector('[data-dd-menu="' + id + '"]');
            if (!menu) return;
            var wasHidden = menu.classList.contains('hidden');
            document.querySelectorAll('[data-dd-menu]').forEach(function (m) { m.classList.add('hidden'); });
            if (wasHidden) menu.classList.remove('hidden');
        });
    });
    document.addEventListener('click', function () {
        document.querySelectorAll('[data-dd-menu]').forEach(function (m) { m.classList.add('hidden'); });
    });
    document.querySelectorAll('[data-dd-val]').forEach(function (opt) {
        opt.addEventListener('click', function () {
            var id = opt.getAttribute('data-dd-target');
            var menu = document.querySelector('[data-dd-menu="' + id + '"]');
            var val = opt.getAttribute('data-dd-val');
            if (menu && menu.getAttribute('data-dd-redirect') === '1') {
                if (val) window.location.href = val;
                return;
            }
            var input = document.getElementById('dd-input-' + id);
            if (input) input.value = val;
            // Update label tombol tanpa reload
            var labelEl = document.querySelector('[data-dd-label="' + id + '"]');
            if (labelEl) labelEl.textContent = opt.textContent.trim();
            document.querySelectorAll('[data-dd-menu="' + id + '"] [data-dd-val]').forEach(function (b) {
                var active = b.getAttribute('data-dd-val') === val;
                b.classList.toggle('bg-gray-900', active);
                b.classList.toggle('text-white', active);
                b.classList.toggle('font-semibold', active);
                b.classList.toggle('text-gray-700', !active);
            });
            // Callback kustom halaman (mode noreload), atau submit form
            if (menu && menu.getAttribute('data-dd-noreload') === '1') {
                var cb = window['dd-change-' + id];
                if (typeof cb === 'function') cb(val);
                return;
            }
            var formId = menu ? menu.getAttribute('data-dd-form') : 'history-form';
            var formEl = document.getElementById(formId || 'history-form');
            if (formEl) formEl.submit();
        });
    });
})();
</script>
