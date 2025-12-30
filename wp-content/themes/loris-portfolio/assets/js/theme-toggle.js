(function () {
    const toggle = document.getElementById('theme-toggle');
    if (!toggle) return;

    const root = document.documentElement;

    function updateIcon(theme) {
        toggle.textContent = theme === 'dark' ? '🌙' : '☀️';
    }

    function updateAria(theme) {
        toggle.setAttribute('aria-pressed', theme === 'dark');
    }

    const currentTheme = root.getAttribute('data-theme') || 'light';
    updateIcon(currentTheme);
    updateAria(currentTheme);

    toggle.addEventListener('click', () => {
        const next =
            root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';

        root.setAttribute('data-theme', next);
        localStorage.setItem('theme', next);
        updateIcon(next);
        updateAria(next);
    });
})();
