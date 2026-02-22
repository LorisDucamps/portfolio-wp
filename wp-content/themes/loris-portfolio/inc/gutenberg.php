<?php

/**
 * Désactivation Gutenberg & Global Styles
 *
 * Dans un thème portfolio full custom, on n'utilise pas
 * les styles Gutenberg en front. Ce fichier les supprime
 * proprement sans casser l'éditeur en admin.
 */

defined('ABSPATH') || exit;

// ------------------------------------------------------------
// Désactiver les CSS Gutenberg en front uniquement
// (wp_enqueue_scripts ne s'exécute pas en admin)
// ------------------------------------------------------------
function loris_dequeue_block_styles(): void
{
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'loris_dequeue_block_styles', 20);


// ------------------------------------------------------------
// Supprimer le CSS inline généré par theme.json
// ------------------------------------------------------------
add_filter('wp_get_global_stylesheet', '__return_empty_string');
add_filter('wp_get_global_stylesheet_for_blocks', '__return_empty_string');
add_filter('wp_enqueue_global_styles', '__return_false');


// ------------------------------------------------------------
// Vider les presets CSS du theme.json par défaut
// (variables CSS --wp--preset--color--*, --wp--preset--font-size--*, etc.)
//
// On utilise update_with() sur l'objet reçu — instancier
// un nouveau WP_Theme_JSON n'est pas fiable à ce stade.
// ------------------------------------------------------------
function loris_empty_theme_json_presets($theme_json)
{
    $theme_json->update_with([
        'version'  => 2,
        'settings' => [
            'color'      => ['palette' => []],
            'typography' => ['fontSizes' => []],
        ],
    ]);

    return $theme_json;
}
add_filter('wp_theme_json_data_default', 'loris_empty_theme_json_presets');


// ------------------------------------------------------------
// Désactiver les filtres SVG duotone injectés dans <body>
// ------------------------------------------------------------
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
