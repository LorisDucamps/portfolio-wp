<?php

/**
 * Thème  : Loris Portfolio
 * Version: 1.0.0
 *
 * Point d'entrée — aucune logique ici, uniquement les includes.
 * Pour modifier un comportement, ouvrir le fichier correspondant dans /inc.
 */

defined('ABSPATH') || exit;

// ------------------------------------------------------------
// Environnement (définir dans wp-config.php pour surcharger)
// ------------------------------------------------------------
if (! defined('WP_ENV')) {
    define('WP_ENV', 'production');
}

// ------------------------------------------------------------
// Modules
// ------------------------------------------------------------
$loris_modules = [
    'inc/setup.php',
    'inc/assets.php',
    'inc/cleanup.php',
    'inc/gutenberg.php',
    'inc/security.php',
];

foreach ($loris_modules as $module) {
    $path = get_template_directory() . '/' . $module;
    if (file_exists($path)) {
        require_once $path;
    }
}
