<?php

/**
 * Nettoyage WordPress
 *
 * - Suppression des emojis
 * - Nettoyage du <head> (liens inutiles, generator, version WP)
 * - Suppression des resource hints inutiles
 */

defined('ABSPATH') || exit;

// ------------------------------------------------------------
// Emojis
// ------------------------------------------------------------
function loris_disable_emojis(): void
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'loris_disable_emojis');


// ------------------------------------------------------------
// Liens inutiles dans le <head>
// ------------------------------------------------------------
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

// Masquer la version WordPress dans toutes les balises <meta>
add_filter('the_generator', '__return_empty_string');


// ------------------------------------------------------------
// Supprimer le ?ver= de la version WordPress sur les assets (optionnel)
//
// Désactivé par défaut: la suppression de ?ver peut dégrader le cache-busting
// après une mise à jour WordPress (navigateurs/CDN gardent un asset périmé).
// Activer seulement si tu maîtrises le cache côté serveur/CDN:
//   define('LORIS_REMOVE_WP_ASSET_VERSION', true);
//
// Cible uniquement la version WP pour ne pas casser le cache-busting des plugins tiers.
// ------------------------------------------------------------
function loris_remove_wp_version_from_assets(string $src): string
{
    if (str_contains($src, 'ver=' . get_bloginfo('version'))) {
        $src = remove_query_arg('ver', $src);
    }

    return $src;
}
if (defined('LORIS_REMOVE_WP_ASSET_VERSION')) {
    add_filter('style_loader_src', 'loris_remove_wp_version_from_assets', 9999);
    add_filter('script_loader_src', 'loris_remove_wp_version_from_assets', 9999);
}


// ------------------------------------------------------------
// Resource hints
// WordPress injecte par défaut des dns-prefetch vers
// s.w.org et fonts.googleapis.com. On ne touche pas à
// dns-prefetch en général, mais on supprime les prefetch
// vers des domaines qu'on n'utilise pas.
// ------------------------------------------------------------
function loris_clean_resource_hints(array $hints, string $relation_type): array
{
    if ('dns-prefetch' === $relation_type) {
        // Supprimer le prefetch vers l'API WordPress (inutile en front)
        $hints = array_filter($hints, function ($hint) {
            $url = is_array($hint) ? ($hint['href'] ?? '') : $hint;
            return ! str_contains($url, 'api.w.org');
        });
    }

    return $hints;
}
add_filter('wp_resource_hints', 'loris_clean_resource_hints', 10, 2);
