<?php

/**
 * Sécurité
 *
 * - Désactivation XML-RPC
 * - Désactivation des pings
 * - Headers HTTP de sécurité (en prod uniquement)
 */

defined('ABSPATH') || exit;

// ------------------------------------------------------------
// XML-RPC — désactivé (vecteur d'attaque brute force)
// Compléter avec un bloc dans .htaccess :
//   <Files "xmlrpc.php">
//     Order Deny,Allow
//     Deny from all
//   </Files>
// ------------------------------------------------------------
add_filter('xmlrpc_enabled', '__return_false');
add_filter('pings_open', '__return_false', 9999);

// ------------------------------------------------------------
// Headers HTTP de sécurité
// Injectés uniquement en production pour ne pas gêner le debug.
// Pour une sécurité maximale, préférer les définir au niveau
// serveur (Nginx / Apache) plutôt qu'en PHP.
// ------------------------------------------------------------
function loris_security_headers(): void
{
    if (wp_get_environment_type() !== 'production') {
        return;
    }

    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    header_remove('X-Powered-By');
}
add_action('send_headers', 'loris_security_headers');
