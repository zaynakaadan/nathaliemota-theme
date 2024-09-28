<?php
// Charger les styles du thème
function nathaliemota_enqueue_styles() {
    wp_enqueue_style( 'nathaliemota-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'nathaliemota_enqueue_styles' );

// Charger les scripts JS du thème
function nathaliemota_enqueue_scripts() {
    wp_enqueue_script( 'nathaliemota-modal-script', get_template_directory_uri() . '/js/scripts.js', array(), null, true );
}
add_action( 'wp_enqueue_scripts', 'nathaliemota_enqueue_scripts' );

// Support pour les Menus
function nathaliemota_setup() {
    register_nav_menus(array(
        'main-menu' => __('Main Menu', 'nathaliemota'),
    ));
}
add_action('after_setup_theme', 'nathaliemota_setup');
?>
