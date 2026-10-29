
import Alpine from 'alpinejs';
import './bootstrap';
import { createIcons, Download, Activity, CalendarDays, SlidersHorizontal } from 'lucide';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    createIcons({
        icons: {
            Download,
            Activity,
            CalendarDays,
            SlidersHorizontal,
        },
    });

    const themeToggle = document.getElementById('themeToggle');

    if (themeToggle) {
        const sunIcon = `
            <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.9" stroke-linecap="round"
                 stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="4"></circle>
                <path d="M12 2v2"></path>
                <path d="M12 20v2"></path>
                <path d="m4.93 4.93 1.42 1.42"></path>
                <path d="m17.65 17.65 1.42 1.42"></path>
                <path d="M2 12h2"></path>
                <path d="M20 12h2"></path>
                <path d="m4.93 19.07-1.42 1.42"></path>
                <path d="m17.65 6.35-1.42-1.42"></path>
            </svg>`;

        const moonIcon = `
            <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.9" stroke-linecap="round"
                 stroke-linejoin="round" aria-hidden="true">
                <path d="M20.5 14.2A8.5 8.5 0 0 1 9.8 3.5
                         8.5 8.5 0 1 0 20.5 14.2Z"></path>
            </svg>`;

        const updateThemeIcon = () => {
            const isDark = document.documentElement.classList.contains('dark');
            themeToggle.innerHTML = isDark ? sunIcon : moonIcon;
            themeToggle.setAttribute(
                'aria-label',
                isDark ? 'Gunakan Light Mode' : 'Gunakan Dark Mode'
            );
        };

        themeToggle.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcon();
        });

        updateThemeIcon();
    }

    document.querySelectorAll('.menu-link').forEach((link) => {
        if (link.pathname === window.location.pathname) {
            link.classList.add('active');
        }
    });

    const userId = window.authUserId;

    if (userId && window.Echo) {
        window.Echo.private(`user.${userId}`)
            .listen('.order.placed', (event) => {
                showRealtimeNotification(event);
            });
    }
});

function showRealtimeNotification(event) {
    let container = document.getElementById('realtime-notifications');

    if (!container) {
        container = document.createElement('div');
        container.id = 'realtime-notifications';
        container.className = 'fixed right-4 top-4 z-[9999] flex w-[min(360px,calc(100vw-2rem))] flex-col gap-3';
        container.setAttribute('aria-live', 'polite');
        document.body.appendChild(container);
    }

    const notification = document.createElement('div');
    notification.className = 'rounded-2xl border border-lime-200 bg-white p-4 shadow-xl dark:border-lime-900 dark:bg-[#1b1b1e]';

    const title = document.createElement('p');
    title.className = 'text-sm font-semibold text-[#171719] dark:text-white';
    title.textContent = 'Transaksi berhasil';

    const message = document.createElement('p');
    message.className = 'mt-1 text-sm text-black/65 dark:text-white/65';
    message.textContent = `${event.invoice || `Transaksi #${event.order_id}`} · ${formatRupiah(event.total)}`;

    const cashier = document.createElement('p');
    cashier.className = 'mt-1 text-xs text-black/45 dark:text-white/45';
    cashier.textContent = `Kasir: ${event.user_name || '-'}`;

    notification.append(title, message, cashier);
    container.prepend(notification);

    window.setTimeout(() => {
        notification.remove();

        if (container.childElementCount === 0) {
            container.remove();
        }
    }, 5000);
}

function formatRupiah(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value) || 0);
}
