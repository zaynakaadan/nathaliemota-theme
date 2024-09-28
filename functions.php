<?php
function nathaliemota_enqueue_styles() {
    wp_enqueue_style( 'nathaliemota-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'nathaliemota_enqueue_styles' );

// le Support pour les Menus
function nathaliemota_setup() {
    register_nav_menus(array(
        'main-menu' => __('Main Menu', 'nathaliemota'),
    ));
}
add_action('after_setup_theme', 'nathaliemota_setup');

?>