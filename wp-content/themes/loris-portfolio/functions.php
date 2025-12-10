<?php
/**
 * Thème : Loris Portfolio
 * Version : 1.0.0
 * Description : Setup + Optimisation + Nettoyage complet WordPress
 */

/**
 * ------------------------------------------------------------
 * 1. Setup du thème
 * ------------------------------------------------------------
 */
function loris_portfolio_theme_setup() {

	// Images mises en avant
	add_theme_support( 'post-thumbnails' );

	// Gestion du <title>
	add_theme_support( 'title-tag' );

	// Menus
	register_nav_menus(
		[
			'main-menu' => __( 'Menu principal', 'loris-portfolio' ),
		]
	);
}
add_action( 'after_setup_theme', 'loris_portfolio_theme_setup' );


/**
 * ------------------------------------------------------------
 * 2. Chargement CSS propre, versionné automatiquement
 * ------------------------------------------------------------
 */
function loris_portfolio_enqueue_assets() {
	wp_enqueue_style(
		'loris-portfolio-style',
		get_stylesheet_uri(),
		[],
		filemtime( get_stylesheet_directory() . '/style.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'loris_portfolio_enqueue_assets' );


/**
 * ------------------------------------------------------------
 * 3. Suppression des emojis WordPress
 * ------------------------------------------------------------
 */
add_action( 'init', function () {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
} );


/**
 * ------------------------------------------------------------
 * 4. Suppression totale de Gutenberg CSS & Global Styles
 *   (supprime :root --wp--preset--*, block-library, inline CSS, etc.)
 * ------------------------------------------------------------
 */

// 4.1 Supprimer les CSS Gutenberg
add_action( 'wp_enqueue_scripts', function () {

	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'classic-theme-styles' );

	// Global Styles (theme.json)
	wp_dequeue_style( 'global-styles' );

}, 20 );

// 4.2 Supprimer le CSS inline généré par theme.json
add_filter( 'wp_get_global_stylesheet', '__return_empty_string' );
add_filter( 'wp_get_global_stylesheet_for_blocks', '__return_empty_string' );

// 4.3 Empêcher l’inclusion des presets CSS dans le head
add_filter( 'wp_theme_json_data_default', function( $data ) {

	// Version vide mais VALIDE (important)
	$empty = [
		'version'  => 2,
		'settings' => [],
		'styles'   => [],
	];

	return new WP_Theme_JSON( $empty, 'default' );
});

// 4.4 Désactiver les duotones SVG injectés dans <body>
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

// 4.5 Désactiver global styles custom CSS
add_filter( 'wp_enqueue_global_styles', '__return_false' );

// 4.6 Empêcher WordPress d'ajouter des styles inline dans les blocks rendus
add_filter( 'render_block', function( $block_content, $block ) {
	return $block_content;
}, 10, 2 );


/**
 * ------------------------------------------------------------
 * 5. Nettoyage du <head>
 * ------------------------------------------------------------
 */
remove_action( 'wp_head', 'rest_output_link_wp_head' );      // API REST
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' ); // oEmbed
remove_action( 'wp_head', 'rsd_link' );                      // RSD XML
remove_action( 'wp_head', 'wlwmanifest_link' );              // Windows Live Writer
