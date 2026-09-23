const themeToggle = document.getElementById('themeToggle');

function updateThemeIcon() {
    if (!themeToggle) {
        return;
    }

    const isDark = document.documentElement.classList.contains('dark');

    themeToggle.textContent = isDark ? '☀' : '☾';
    themeToggle.setAttribute(
        'aria-label',
        isDark ? 'Gunakan Light Mode' : 'Gunakan Dark Mode'
    );
}

if (themeToggle) {
    themeToggle.addEventListener('click', () => {
        const isDark = document.documentElement.classList.toggle('dark');

        localStorage.setItem('theme', isDark ? 'dark' : 'light');

        updateThemeIcon();
    });

    updateThemeIcon();
}

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
