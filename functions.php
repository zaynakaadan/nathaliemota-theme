<?php
function nathaliemota_enqueue_styles() {
    wp_enqueue_style( 'nathaliemota-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'nathaliemota_enqueue_styles' );
?>