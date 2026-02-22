<?php

/**
 * Sécurité
 *
 * - Blocage REST API pour les utilisateurs non connectés
 * - Désactivation XML-RPC
 * - Désactivation des pings
 * - Headers HTTP de sécurité (en prod uniquement)
 */

defined('ABSPATH') || exit;

// ------------------------------------------------------------
// REST API — bloquer tous les endpoints aux anonymes.
// Utilise rest_authentication_errors (plus fiable que
// rest_request_before_callbacks qui ne couvre pas tous les cas).
// ------------------------------------------------------------
function loris_restrict_rest_api(\WP_Error|null|true $result): \WP_Error|null|true
{
    if (! is_user_logged_in()) {
        return new WP_Error(
            'rest_forbidden',
            __('Accès interdit.', 'loris-portfolio'),
            ['status' => 403]
        );
    }

    return $result;
}
add_filter('rest_authentication_errors', 'loris_restrict_rest_api');


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
    if (WP_ENV !== 'production') {
        return;
    }

    // Empêche le MIME-type sniffing
    header('X-Content-Type-Options: nosniff');

    // Empêche l'affichage du site dans une iframe (clickjacking)
    header('X-Frame-Options: SAMEORIGIN');

    // Active la protection XSS dans les vieux navigateurs
    header('X-XSS-Protection: 1; mode=block');

    // Masque le serveur (PHP n'envoie pas X-Powered-By)
    header_remove('X-Powered-By');
}
add_action('send_headers', 'loris_security_headers');
