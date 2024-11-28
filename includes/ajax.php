<?php
/**
 * Complément de fonction.php
 * Fonctions pour AJAX 
 *
 * @package WordPress
 * @subpackage nathaliemota-theme
 */


/**
 * Fonction pour charger les photos avec les filtres
 */  
function nathalie_mota_load() { 
  // Vérification de sécurité
  if( 
       ! isset( $_REQUEST['nonce'] ) || 
        ! wp_verify_nonce( $_REQUEST['nonce'], 'nathalie_mota_nonce' ) 
   ) {
       wp_send_json_error( "Vous n’avez pas l’autorisation d’effectuer cette action.", 403 );
       exit;
   }

   // Paramètres de la requête
   $categorie_id = isset($_REQUEST['categorie_id']) ? $_REQUEST['categorie_id'] : '';
   $format_id = isset($_REQUEST['format_id']) ? $_REQUEST['format_id'] : '';
   $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC'; // Par défaut DESC
   $order_by = isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : 'date'; // Tri par date par défaut
   $paged = isset($_REQUEST['paged']) ? $_REQUEST['paged'] : 1; // Page actuelle

   // Arguments de la requête WP_Query
   $args = array(
     'post_type' => 'photo', // Le custom post type
     'posts_per_page' => 8, // Nombre de photos par page
     'paged' => $paged,
     'orderby' => $order_by,
     'order' => $order,
   );

   // Ajouter des filtres par catégorie et format si sélectionnés
   if ($categorie_id) {
     $args['tax_query'][] = array(
         'taxonomy' => 'categorie',
         'field' => 'id',
         'terms' => $categorie_id,
     );
   }
   if ($format_id) {
     $args['tax_query'][] = array(
         'taxonomy' => 'format',
         'field' => 'id',
         'terms' => $format_id,
     );
   }

   // Exécuter la requête
   $query = new WP_Query($args);

   // Vérification des résultats
   if ($query->have_posts()) {
     // Début du contenu HTML
     $html = '';
     while ($query->have_posts()) {
         $query->the_post();
         $html .= '<div class="news-info brightness">';
         $html .= '<h2 class="info-title">' . get_field('reference') . '</h2>';
         $html .= '<p class="info-tax">' . get_the_term_list(get_the_ID(), 'categorie', '', ', ') . '</p>';
         $html .= '<a href="' . get_the_permalink() . '" aria-label="Voir le détail de la photo" title="Voir le détail de la photo"><span class="detail-photo"></span></a>';
         $html .= '<form>';
         $html .= '<input type="hidden" name="postid" class="postid" value="' . get_the_ID() . '">';
         $html .= '<a class="openLightbox" title="Afficher la photo en plein écran" alt="Afficher la photo en plein écran"';
         $html .= ' data-postid="' . get_the_ID() . '" data-arrow="true">';
         $html .= '</a>';
         $html .= '</form>';
         $html .= get_the_post_thumbnail();
         $html .= '<p>' . get_the_terms(get_the_ID(), 'categorie') . '</p>';
         $html .= '</div>';
     }

     // Retourner la réponse avec HTML et données supplémentaires
     wp_send_json_success(array(
         'html' => $html,
         'max_pages' => $query->max_num_pages,
         'nb_total_posts' => $query->found_posts,
     ));
   } else {
     // Si aucune photo n'est trouvée
     wp_send_json_success(array(
         'html' => '<p>Aucune photo ne correspond à votre recherche.</p>',
         'max_pages' => 1,
         'nb_total_posts' => 0,
     ));
   }

   // Réinitialiser les variables globales de post
   wp_reset_postdata();
}
add_action('wp_ajax_nathalie_mota_load', 'nathalie_mota_load');
add_action('wp_ajax_nopriv_nathalie_mota_load', 'nathalie_mota_load');




/**
*  Récupération des détails d'une photo pour une lightbox
*/ 
function nathalie_mota_lightbox() {
  // Vérification du nonce
  if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'nathalie_mota_nonce')) {
    wp_send_json_error(array('message' => 'Nonce invalide.'));
    wp_die(); // Arrêter l'exécution après l'envoi du message d'erreur
}

// Vérification de l'identifiant de la photo
if (!isset($_POST['photo_id']) || !is_numeric($_POST['photo_id'])) {
    wp_send_json_error(array('message' => "L'identifiant de la photo est manquant ou invalide."), 400);
    exit;
}

// Récupération des données pour le filtre
$photo_id = intval($_POST['photo_id']);
$photo = get_post($photo_id); // Vérifie si l'identifiant correspond à un post existant

// Si la photo n'existe pas ou n'est pas du bon type
if (!$photo || $photo->post_type !== 'photo') {
    wp_send_json_error(array('message' => "Photo introuvable."), 404);
    exit;
}

// Préparation des données de la photo
$data = array(
    'id'          => $photo_id,
    'title'       => get_the_title($photo_id),
    'thumbnail'   => get_the_post_thumbnail_url($photo_id, 'large'),
    'categories'  => get_the_term_list($photo_id, 'categorie', '', ', '),
    //'description' => apply_filters('the_content', $photo->post_content),  
);

// Génération du contenu HTML pour la lightbox
ob_start();
get_template_part('templates_part/modal/lightbox', null, array('photo_id' => $photo_id));
$data['html'] = ob_get_clean();

// Envoi des données via JSON
wp_send_json_success($data);
}

add_action('wp_ajax_nathalie_mota_lightbox', 'nathalie_mota_lightbox');
add_action('wp_ajax_nopriv_nathalie_mota_lightbox', 'nathalie_mota_lightbox');

?>