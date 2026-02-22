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

// ------------------------------------------------------------
// Fonts à précharger
// Ajouter ici chaque variante utilisée dans le projet.
// ------------------------------------------------------------
const LORIS_PRELOAD_FONTS = [
    '/assets/fonts/playfair-display/playfair-display-700.woff2',
    // '/assets/fonts/inter/inter-400.woff2',
    // '/assets/fonts/inter/inter-500.woff2',
];

/**
 * Injecte les balises <link rel="preload"> pour les fonts locales.
 * Priorité 1 pour être au plus haut dans le <head>.
 */
function loris_preload_fonts(): void
{
    $base = get_template_directory_uri();

    foreach (LORIS_PRELOAD_FONTS as $font_path) {
        printf(
            '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
            esc_url($base . $font_path)
        );
    }
}
add_action('wp_head', 'loris_preload_fonts', 1);


/**
 * Feuille de style principale, versionnée via filemtime().
 */
function loris_enqueue_styles(): void
{
    wp_enqueue_style(
        'loris-portfolio-style',
        get_stylesheet_uri(),
        [],
        filemtime(get_stylesheet_directory() . '/style.css')
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
 *
 * En production, on peut conditionner le chargement par template :
 *   if (is_singular('project')) { ... }
 */
function loris_enqueue_scripts(): void
{
    $js_dir = get_template_directory();
    $js_uri = get_template_directory_uri();

    wp_enqueue_script(
        'loris-theme-toggle',
        $js_uri . '/assets/js/theme-toggle.js',
        [],
        filemtime($js_dir . '/assets/js/theme-toggle.js'),
        true
    );
    wp_script_add_data('loris-theme-toggle', 'strategy', 'defer');

    // Exemple de chargement conditionnel pour de futurs scripts :
    // if (is_singular('project')) {
    //     wp_enqueue_script('loris-project', $js_uri . '/assets/js/project.js', [], filemtime(...), true);
    //     wp_script_add_data('loris-project', 'strategy', 'defer');
    // }
}
add_action('wp_enqueue_scripts', 'loris_enqueue_scripts');


// ------------------------------------------------------------
// Porte ouverte : block assets granulaires
//
// À activer si tu veux charger un CSS uniquement quand
// un bloc spécifique est présent sur la page,
// sans pour autant réactiver les Global Styles.
//
// add_action('enqueue_block_assets', function () {
//     if (has_block('core/image')) {
//         wp_enqueue_style('loris-block-image', get_template_directory_uri() . '/assets/css/blocks/image.css');
//     }
// });
// ------------------------------------------------------------