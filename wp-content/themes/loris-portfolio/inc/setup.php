<?php

/**
 * Setup du thème
 *
 * - Déclaration des theme supports
 * - Enregistrement des menus de navigation
 */

defined('ABSPATH') || exit;

function loris_theme_setup(): void
{
    // Balise <title> gérée par WordPress
    add_theme_support('title-tag');

    // Images mises en avant
    add_theme_support('post-thumbnails');

    // Embeds responsives (iframes YouTube, Vimeo, etc.)
    add_theme_support('responsive-embeds');

    // Logo personnalisable depuis le customizer
    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ]);

    // Permet de charger un editor-style.css dans Gutenberg
    add_theme_support('editor-styles');

    // Balisage HTML5 propre
    add_theme_support('html5', [
        'search-form',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    // Menus de navigation
    register_nav_menus([
        'main-menu' => __('Menu principal', 'loris-portfolio'),
    ]);
}
add_action('after_setup_theme', 'loris_theme_setup');
