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


// A deporter dans un autre fichier

console.log(
    "%c👋 Salut !",
    "font-size:22px;font-weight:bold;color:#0077B6;"
);

console.log(
    "%cCurieux(se) ? Le code est fait maison.\nPas de builder, pas de thème premium.\nJuste du propre.",
    "font-size:14px;color:#161616;"
);