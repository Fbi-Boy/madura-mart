import Alpine from 'alpinejs';
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

    if (!themeToggle) {
        return;
    }

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
            <path d="m4.93 19.07 1.42-1.42"></path>
            <path d="m17.65 6.35 1.42-1.42"></path>
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
});