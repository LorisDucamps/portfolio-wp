<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Preload critical fonts only -->
    <link rel="preload"
        href="<?php echo get_template_directory_uri(); ?>/assets/fonts/poppins/poppins-400.woff2"
        as="font"
        type="font/woff2"
        crossorigin>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>