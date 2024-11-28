<?php
// Charger les styles du thème
function nathaliemota_enqueue_styles() {
    wp_enqueue_style( 'nathaliemota-contact-style', get_stylesheet_directory_uri() . '/assets/css/contact.css', array(), filemtime(get_stylesheet_directory() . '/assets/css/contact.css') ); 
    wp_enqueue_style( 'nathaliemota-single-photo-style', get_stylesheet_directory_uri() . '/assets/css/single-photo.css', filemtime(get_stylesheet_directory() . '/assets/css/single-photo.css'));
    wp_enqueue_style( 'nathaliemota-style', get_stylesheet_uri(), array(), '1.0' );
    wp_enqueue_style( 'nathaliemota-lightbox-style', get_stylesheet_directory_uri() . '/assets/css/lightbox.css', array(), filemtime(get_stylesheet_directory() . '/assets/css/lightbox.css') ); 
}
add_action( 'wp_enqueue_scripts', 'nathaliemota_enqueue_styles' );

// Charger les scripts JS du thème
function nathaliemota_enqueue_scripts() {
    // Charger jQuery
    wp_enqueue_script('jquery');

    // Charger le script personnalisé
    wp_enqueue_script( 'nathaliemota-modal-script', get_template_directory_uri() . '/assets/js/scripts.js',  array('jquery'), filemtime(get_stylesheet_directory() . '/assets/js/scripts.js'), true );
    // Envoi de 'ajaxurl' et 'nonce' dans le JavaScript
    wp_localize_script('nathaliemota-modal-script', 'ajax_object', array(
        'ajaxurl' => admin_url('admin-ajax.php'), // Définit l'URL de l'endpoint AJAX de WordPress
        'nonce'   => wp_create_nonce('nathalie_mota_nonce'), // Crée un nonce de sécurité
    ));

    // Script JS chargé pour tout le monde sauf avec front_page 
    if (!is_front_page()) {
        wp_enqueue_script( 'nathaliemota-scripts-lightbox-ajax', get_theme_file_uri( '/assets/js/lightbox-ajax.js' ), array('jquery'), filemtime(get_stylesheet_directory() . '/assets/js/lightbox-ajax.js'), true );
    };

   
    
    // Script JS disponnibles chargé uniquement avec front_page 
    if (is_front_page()) {
        wp_enqueue_script( 'nathaliemota-scripts-filters', get_theme_file_uri( '/assets/js/filters.js' ), array('jquery'), filemtime(get_stylesheet_directory() . '/assets/js/filters.js'), true );   
        wp_enqueue_script( 'nathaliemota-scripts-publication-ajax', get_theme_file_uri( '/assets/js/publication-ajax.js' ), array('jquery'), filemtime(get_stylesheet_directory() . '/assets/js/publication-ajax.js'), true );
        wp_enqueue_script( 'nathaliemota-scripts-lightbox-ajax', get_theme_file_uri( '/assets/js/lightbox-front-page-ajax.js' ), array('jquery'), filemtime(get_stylesheet_directory() . '/assets/js/lightbox-front-page-ajax.js'), true );
    };   

    // activer les Dashicons sur le front-end 
    wp_enqueue_style ( 'dashicons' );

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



function create_custom_taxonomies() {
    register_taxonomy('categorie', 'photo', array(
        'label' => 'Catégories ',
        'rewrite' => array('slug' => 'categorie'),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'query_var' => true,
    ));

    register_taxonomy('format', 'photo', array(
        'label' => 'Formats',
        'rewrite' => array('slug' => 'format'),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
    ));
}
add_action('init', 'create_custom_taxonomies');




// Ajouter la prise en charge des images mises en avant
add_theme_support( 'post-thumbnails' );

// permet de définir la taille des images mises en avant 
// set_post_thumbnail_size(largeur, hauteur max, true = on adapte l'image aux dimensions)
set_post_thumbnail_size( 600, 0, false );

// Définir d'autres tailles d'images : 
// les options de base WP : 
//      'thumbnail': 150 x 150 hard cropped 
//      'medium' : 300 x 300 max height 300px
//      'medium_large' : resolution (768 x 0 infinite height)
//      'large' : 1024 x 1024 max height 1024px
//      'full' : original size uploaded
add_image_size( 'hero', 1450, 960, true );
add_image_size( 'desktop-home', 600, 520, true );
add_image_size( 'lightbox', 1300, 900, true );


// Récupération de la valeur d'un champs personnalisé ACF
// $variable = nom de la variable dont on veut récupérer la valeur
// $field = nom du champs personnalisés
function get_acf_custom_field_value( $variable,  $field ) {
    // Initialisation de la valeur à retourner
    $return = "";
    // Vérifie si le champ est un tableau (cas où le champ ACF renvoie un tableau)
    if (is_array($field)) {
        foreach ($field as $key => $value) {
            // Si la clé correspond à la variable spécifiée, on assigne la valeur à la variable $return
            if ($key === $variable) {
                $return = $value;
                break; // On sort de la boucle après avoir trouvé la clé correspondante
            }
        }        
    }
    // Si le champ n'est pas un tableau mais contient une valeur 
    elseif (!empty($field)) {
        $return = $field;
    } 
    // Si le champ est vide ou n'est pas un tableau
    else {
        error_log("Le champ '$field' est vide ou n'est pas un tableau.");
    }
    return $return;  // Retourne la valeur récupérée
}


  

// Partie pour gerer le padding de l'affichage des photos  
include get_template_directory() . '/includes/ajax.php';
?>

