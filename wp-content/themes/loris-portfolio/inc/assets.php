<?php

/**
 * Gestion des assets
 *
 * - Preload des fonts locales (WOFF2)
 * - Chargement CSS versionné automatiquement
 * - Chargement JS avec stratégie defer
 * - Initialisation du thème clair/sombre (anti-flash)
 */

defined('ABSPATH') || exit;

/**
 * Version d'asset robuste (évite les warnings filemtime en déploiement partiel).
 */
function loris_asset_version(string $relative_path): string
{
    $relative_path = ltrim($relative_path, '/');
    $full_path     = get_template_directory() . '/' . $relative_path;

    if (file_exists($full_path)) {
        $mtime = filemtime($full_path);
        if ($mtime !== false) {
            return (string) $mtime;
        }
    }

    return (string) wp_get_theme()->get('Version');
}

/**
 * Feuille de style principale, versionnée via filemtime().
 */
function loris_enqueue_styles(): void
{
    wp_enqueue_style(
        'loris-portfolio-style',
        get_stylesheet_uri(),
        [],
        loris_asset_version('style.css')
    );
}
add_action('wp_enqueue_scripts', 'loris_enqueue_styles');


/**
 * Script d'initialisation du thème clair/sombre.
 * Injecté en <head> (position "before") pour éviter le flash de couleur.
 *
 * Aucun fichier externe : le script est inline pour être synchrone.
 */
function loris_enqueue_color_scheme_init(): void
{
    wp_register_script('loris-theme-init', false, [], null, false);
    wp_enqueue_script('loris-theme-init');

    wp_add_inline_script(
        'loris-theme-init',
        "(function () {
            try {
                var stored     = localStorage.getItem('theme');
                var preferDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                var theme      = stored ? stored : (preferDark ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {}
        })();",
        'before'
    );
}
add_action('wp_enqueue_scripts', 'loris_enqueue_color_scheme_init', 0);


/**
 * Scripts JS d'interaction.
 * Chargés en footer avec stratégie "defer" (non bloquants).
 */
function loris_enqueue_scripts(): void
{
    $js_uri = get_template_directory_uri();

    wp_enqueue_script(
        'loris-theme-toggle',
        $js_uri . '/assets/js/theme-toggle.js',
        [],
        loris_asset_version('assets/js/theme-toggle.js'),
        true
    );
    wp_script_add_data('loris-theme-toggle', 'strategy', 'defer');
}
add_action('wp_enqueue_scripts', 'loris_enqueue_scripts');
