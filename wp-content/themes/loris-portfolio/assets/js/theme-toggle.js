(function () {
    const toggle = document.getElementById('theme-toggle');
    if (!toggle) return;

    const root = document.documentElement;

    function applyTheme(theme) {
        root.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        updateIcon(theme);
        updateAria(theme);
    }

    function updateIcon(theme) {
        toggle.textContent = theme === 'dark' ? '🌙' : '☀️';
    }

    function updateAria(theme) {
        toggle.setAttribute('aria-pressed', theme === 'dark');
    }

    const savedTheme = localStorage.getItem('theme');
    const initialTheme =
        savedTheme || root.getAttribute('data-theme') || 'light';

    applyTheme(initialTheme);

    toggle.addEventListener('click', () => {
        const current = root.getAttribute('data-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        applyTheme(next);
    });
})();