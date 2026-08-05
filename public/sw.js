/**
 * HOLIC Barbershop — Service Worker
 * Handles background push notifications
 */

const CACHE_NAME = 'holic-v1';

// ─── Push Event ──────────────────────────────────────────────────────────────
self.addEventListener('push', function (event) {
    if (!event.data) return;

    let data = {};
    try {
        data = event.data.json();
    } catch (e) {
        data = { title: 'HOLIC Barbershop', body: event.data.text() };
    }

    const title   = data.title  || 'HOLIC Barbershop';
    const options = {
        body:            data.body  || 'Ada pembaruan antrean Anda.',
        icon:            data.icon  || '/icons/icon-192.png',
        badge:           data.badge || '/icons/badge-72.png',
        tag:             data.tag   || 'queue-notification',
        renotify:        data.renotify ?? true,
        requireInteraction: true,   // Notification stays until user interacts
        vibrate:         [200, 100, 200, 100, 400],
        data:            data.data  || {},
        actions: [
            { action: 'open',    title: 'Lihat Status' },
            { action: 'dismiss', title: 'Tutup' },
        ],
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

// ─── Notification Click ───────────────────────────────────────────────────────
self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    if (event.action === 'dismiss') return;

    // Open or focus the status page
    const targetUrl = event.notification.data?.url || '/customer/dashboard';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
            // If a tab with the URL is already open, focus it
            for (const client of clientList) {
                if (client.url === targetUrl && 'focus' in client) {
                    return client.focus();
                }
            }
            // Otherwise open a new tab
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});

// ─── Install / Activate ───────────────────────────────────────────────────────
self.addEventListener('install',  () => self.skipWaiting());
self.addEventListener('activate', (e) => e.waitUntil(clients.claim()));
