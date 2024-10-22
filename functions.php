<?php
// Charger les styles du thème
function nathaliemota_enqueue_styles() {
    wp_enqueue_style( 'nathaliemota-contact-style', get_stylesheet_directory_uri() . '/assets/css/contact.css', array(), filemtime(get_stylesheet_directory() . '/assets/css/contact.css') ); 
    wp_enqueue_style( 'nathaliemota-single-photo-style', get_stylesheet_directory_uri() . '/assets/css/single-photo.css', filemtime(get_stylesheet_directory() . '/assets/css/single-photo.css'));
    wp_enqueue_style( 'nathaliemota-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'nathaliemota_enqueue_styles' );

// Charger les scripts JS du thème
function nathaliemota_enqueue_scripts() {
    // Charger jQuery
    wp_enqueue_script('jquery');

    // Charger le script personnalisé
    wp_enqueue_script( 'nathaliemota-modal-script', get_template_directory_uri() . '/assets/js/scripts.js',  array('jquery'), filemtime(get_stylesheet_directory() . '/assets/js/scripts.js'), true );
}
add_action( 'wp_enqueue_scripts', 'nathaliemota_enqueue_scripts' );

// Fonction pour enregistrer les emplacements de menus
function nathaliemota_register_menus() {
    register_nav_menus(array(
        'primary-menu' => __('Menu Principal'),
        'footer-menu' => __('Menu du Pied de Page')
    ));
}
add_action('after_setup_theme', 'nathaliemota_register_menus');


// Ajout d'un lien "Contact" personnalisé au menu
function add_contact_menu_item($items, $args) {
    if ($args->theme_location == 'primary-menu') { 
        $items .= '<li id="menu-item-52" class="contact menu-item menu-item-type-custom menu-item-object-custom menu-item-52"><a title="contact_btn_navbar" href="#">Contact</a></li>';
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'add_contact_menu_item', 10, 2);



?>

