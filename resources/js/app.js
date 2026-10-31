import Alpine from 'alpinejs';
import './bootstrap';
import {
    createIcons,
    Download,
    Activity,
    CalendarDays,
    SlidersHorizontal
} from 'lucide';

window.Alpine = Alpine;

Alpine.start();

// ============================================
// ICONS
// ============================================

document.addEventListener('DOMContentLoaded', () => {
    createIcons({
        icons: {
            Download,
            Activity,
            CalendarDays,
            SlidersHorizontal,
        },
    });

    initializeTheme();
    initializeActiveMenu();
    initializeProductAjax();
    initializeRealtime();
});

// ============================================
// THEME
// ============================================

function initializeTheme() {
    const themeToggle = document.getElementById('themeToggle');

    if (!themeToggle) return;

    const sunIcon = `
        <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="1.9"
             stroke-linecap="round" stroke-linejoin="round">
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
        <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="1.9"
             stroke-linecap="round" stroke-linejoin="round">
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

// ============================================
// ACTIVE MENU
// ============================================

function initializeActiveMenu() {
    document.querySelectorAll('.menu-link').forEach((link) => {
        if (link.pathname === window.location.pathname) {
            link.classList.add('active');
        }
    });
}

// ============================================
// PRODUCT AJAX FORM
// ============================================

function initializeProductAjax() {
    const form = document.querySelector(
        '[data-ajax-form="product-create"]'
    );

    if (!form) return;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!form.reportValidity()) return;

        const submitButton = form.querySelector(
            'button[type="submit"]'
        );

        const originalButtonContent = submitButton?.innerHTML;

        clearProductValidationErrors(form);

        if (submitButton) {
            submitButton.disabled = true;

            submitButton.classList.add(
                'opacity-70',
                'cursor-not-allowed'
            );

            submitButton.innerHTML = `
                <svg class="w-4 h-4 animate-spin"
                     viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="9"
                            stroke="currentColor"
                            stroke-width="3"
                            opacity=".25"></circle>
                    <path d="M21 12a9 9 0 0 0-9-9"
                          stroke="currentColor"
                          stroke-width="3"
                          stroke-linecap="round"></path>
                </svg>
                Menyimpan...
            `;
        }

        try {
            const headers = {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            };

            const socketId = window.Echo?.socketId?.();

            if (socketId) {
                headers['X-Socket-ID'] = socketId;
            }

            const response = await fetch(form.action, {
                method: 'POST',
                headers,
                body: new FormData(form),
                credentials: 'same-origin',
            });

            const result = await response.json().catch(() => ({}));

            // Validasi Laravel.
            if (response.status === 422) {
                showProductValidationErrors(form, result.errors || {});

                const firstError = Object.values(result.errors || {})
                    .flat()[0];

                showAppToast({
                    type: 'success',
                    title: 'Produk berhasil disimpan!',
                    message: `${result.data?.name || 'Produk baru'} berhasil ditambahkan.`,
                });

                // Tunggu toast terlihat, kemudian kembali ke daftar produk.
                window.setTimeout(() => {
                    window.location.href = result.redirect;
                }, 1200);

                return;
            }

            if (!response.ok) {
                throw new Error(
                    result.message || 'Produk gagal disimpan.'
                );
            }

            // Bersihkan form setelah berhasil.
            form.reset();

            clearProductValidationErrors(form);

            showAppToast({
                type: 'success',
                title: 'Produk berhasil disimpan!',
                message: `${result.data?.name || 'Produk baru'} sudah ditambahkan ke katalog Madura Mart.`,
            });

        } catch (error) {
            console.error('Product create error:', error);

            showAppToast({
                type: 'error',
                title: 'Gagal menyimpan produk',
                message: error.message || 'Periksa koneksi dan coba kembali.',
            });

        } finally {
            if (submitButton) {
                submitButton.disabled = false;

                submitButton.classList.remove(
                    'opacity-70',
                    'cursor-not-allowed'
                );

                submitButton.innerHTML = originalButtonContent;
            }
        }
    });
}

// ============================================
// PRODUCT VALIDATION
// ============================================

function clearProductValidationErrors(form) {
    form.querySelectorAll('.ajax-field-error').forEach((element) => {
        element.remove();
    });

    form.querySelectorAll('[aria-invalid="true"]').forEach((element) => {
        element.removeAttribute('aria-invalid');

        element.classList.remove(
            'border-red-400',
            'focus:border-red-400'
        );
    });
}

function showProductValidationErrors(form, errors) {
    Object.entries(errors).forEach(([field, messages]) => {
        const input = Array.from(form.elements).find(
            (element) => element.name === field
        );

        if (!input) return;

        input.setAttribute('aria-invalid', 'true');

        input.classList.add('border-red-400');

        const error = document.createElement('p');

        error.className =
            'ajax-field-error mt-1.5 text-[10px] font-medium text-red-500';

        error.textContent = Array.isArray(messages)
            ? messages[0]
            : messages;

        const anchor = input.closest('label') || input;

        anchor.insertAdjacentElement('afterend', error);
    });
}

// ============================================
// REALTIME ECHO
// ============================================

function initializeRealtime() {
    const userId = window.authUserId;
    const userRole = window.authUserRole;

    if (!userId || !window.Echo) return;

    // Notifikasi transaksi yang sudah ada.
    window.Echo.private(`user.${userId}`)
        .listen('.order.placed', (event) => {
            showRealtimeNotification(event);
        });

    // Notifikasi produk khusus Super Admin.
    if (userRole === 'super-admin') {
        window.Echo.private('super-admin.products')
            .listen('.product.created', (event) => {
                showProductRealtimeNotification(event);
            });
    }
}

// ============================================
// PRODUCT REALTIME NOTIFICATION
// ============================================

function showProductRealtimeNotification(event) {
    showAppToast({
        type: 'info',
        title: 'Produk baru ditambahkan',
        message: `${event.product_name} ditambahkan oleh ${event.created_by}.`,
    });
}

// ============================================
// ORDER REALTIME NOTIFICATION
// ============================================

function showRealtimeNotification(event) {
    showAppToast({
        type: 'success',
        title: 'Transaksi berhasil',
        message: `${event.invoice || `Transaksi #${event.order_id}`} · ${formatRupiah(event.total)} · Kasir: ${event.user_name || '-'}`,
    });
}

// ============================================
// GLOBAL TOAST NOTIFICATION
// ============================================

window.showAppToast = showAppToast;

function showAppToast({
    type = 'success',
    title = 'Berhasil',
    message = '',
} = {}) {
    const container = getToastContainer();

    const themes = {
        success: {
            icon: `
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="m5 12 4 4L19 6"></path>
                </svg>`,
            iconClass: 'bg-lime-100 text-lime-700 dark:bg-lime-400/15 dark:text-lime-400',
            accent: 'bg-lime-400',
        },

        error: {
            icon: `
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 9v4"></path>
                    <path d="M12 17h.01"></path>
                    <circle cx="12" cy="12" r="10"></circle>
                </svg>`,
            iconClass: 'bg-red-100 text-red-600 dark:bg-red-400/15 dark:text-red-400',
            accent: 'bg-red-500',
        },

        info: {
            icon: `
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 16v-4"></path>
                    <path d="M12 8h.01"></path>
                    <circle cx="12" cy="12" r="10"></circle>
                </svg>`,
            iconClass: 'bg-blue-100 text-blue-600 dark:bg-blue-400/15 dark:text-blue-400',
            accent: 'bg-blue-500',
        },
    };

    const theme = themes[type] || themes.success;

    const toast = document.createElement('div');

    toast.className = `
        pointer-events-auto relative overflow-hidden
        w-full rounded-2xl
        border border-black/[0.06] dark:border-white/[0.08]
        bg-white dark:bg-[#202023]
        shadow-[0_16px_50px_rgba(0,0,0,0.14)]
        opacity-0 translate-x-5
        transition-all duration-300 ease-out
    `;

    const content = document.createElement('div');

    content.className = 'flex items-start gap-3 p-4';

    const icon = document.createElement('div');

    icon.className = `
        flex h-10 w-10 shrink-0 items-center justify-center
        rounded-xl ${theme.iconClass}
    `;

    icon.innerHTML = theme.icon;

    const iconSvg = icon.querySelector('svg');

    if (iconSvg) {
        iconSvg.classList.add('h-5', 'w-5');
    }

    const textContent = document.createElement('div');

    textContent.className = 'min-w-0 flex-1 pt-0.5';

    const heading = document.createElement('p');

    heading.className =
        'text-[13px] font-bold text-[#171719] dark:text-white';

    heading.textContent = title;

    const description = document.createElement('p');

    description.className =
        'mt-1 text-[11px] leading-relaxed text-black/55 dark:text-white/55';

    description.textContent = message;

    textContent.append(heading, description);

    const closeButton = document.createElement('button');

    closeButton.type = 'button';

    closeButton.className =
        'shrink-0 rounded-lg p-1 text-black/35 hover:bg-black/5 hover:text-black dark:text-white/40 dark:hover:bg-white/10 dark:hover:text-white transition';

    closeButton.setAttribute('aria-label', 'Tutup notifikasi');

    closeButton.innerHTML = `
        <svg class="h-4 w-4" viewBox="0 0 24 24"
             fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round">
            <path d="m18 6-12 12"></path>
            <path d="m6 6 12 12"></path>
        </svg>`;

    content.append(icon, textContent, closeButton);

    const progress = document.createElement('div');

    progress.className = 'h-[3px] w-full bg-black/[0.04] dark:bg-white/[0.05]';

    const progressBar = document.createElement('div');

    progressBar.className =
        `h-full w-full origin-left ${theme.accent}`;

    progressBar.style.transform = 'scaleX(1)';
    progressBar.style.transition = 'transform 5s linear';

    progress.appendChild(progressBar);

    toast.append(content, progress);

    container.prepend(toast);

    requestAnimationFrame(() => {
        toast.classList.remove('opacity-0', 'translate-x-5');
        toast.classList.add('opacity-100', 'translate-x-0');

        progressBar.style.transform = 'scaleX(0)';
    });

    let timeout;

    const removeToast = () => {
        window.clearTimeout(timeout);

        toast.classList.remove('opacity-100', 'translate-x-0');
        toast.classList.add('opacity-0', 'translate-x-5');

        window.setTimeout(() => {
            toast.remove();

            if (container.childElementCount === 0) {
                container.remove();
            }
        }, 300);
    };

    closeButton.addEventListener('click', removeToast);

    timeout = window.setTimeout(removeToast, 5000);
}

// ============================================
// TOAST CONTAINER
// ============================================

function getToastContainer() {
    let container = document.getElementById('app-toast-container');

    if (!container) {
        container = document.createElement('div');

        container.id = 'app-toast-container';

        container.className = `
            pointer-events-none fixed right-5 top-5 z-[99999]
            flex w-[min(390px,calc(100vw-2.5rem))]
            flex-col gap-3
        `;

        container.setAttribute('aria-live', 'polite');

        container.setAttribute('aria-atomic', 'false');

        document.body.appendChild(container);
    }

    return container;
}

// ============================================
// FORMAT RUPIAH
// ============================================

function formatRupiah(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value) || 0);
}
