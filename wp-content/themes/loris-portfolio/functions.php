<?php

/**
 * Setup du thème
 */
function loris_portfolio_theme_setup() {
	// Support des images mises en avant
	add_theme_support( 'post-thumbnails' );

	// Support du title tag
	add_theme_support( 'title-tag' );

	// Support des menus
	register_nav_menus(
		[
			'main-menu' => __( 'Menu principal', 'loris-portfolio' ),
		]
	);
}
add_action( 'after_setup_theme', 'loris_portfolio_theme_setup' );

/**
 * Chargement des assets CSS / JS
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

