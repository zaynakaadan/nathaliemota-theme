<?php 
   // [post_type] => post
   // [post_type] => photo

   // Initialisation de varaibles pour le filtres d'affichage des photos
   $order = "";       // Variable utilisée pour déterminer l'ordre de tri (par défaut, elle est vide)   
   $orderby = "date"; // Définit le critère de tri des articles, ici par date.

   // On place les critères de la requête dans un Array
   $term = get_queried_object(); //Récupère l'objet actuel de la requête, qui peut être une catégorie ou une autre taxonomie.
   $publication = get_queried_object_id(); //Récupère l'identifiant de l'article actuellement affiché.

   // Récupération du n° de la catégorie pour filtrage
   $categorie_id  =  get_post_meta( get_the_ID(), 'categorie', true );  //Récupère l'ID de la catégorie associée à l'article courant. 
   $format_id  =  get_post_meta( get_the_ID(), 'format', true );
   $categorie_name  = get_acf_custom_field_value('name', get_field('categorie')); //Récupère le nom de la catégorie via un champ ACF (Advanced Custom Fields).
     
   // Creation du filtre pour afficher les photos 
   $custom_args = array(
      'post_type' => 'photo',
      'posts_per_page' => 2,
      'order' => $order,
      'orderby'   => $orderby,
      'meta_key' => 'categorie',
      'meta_value' => $categorie_id,
      'compare'   => 'LIKE', // NOT LIKE Exclut la publication actuelle de la requête.
      'post__not_in' => array($publication),
      'nopaging' => false,
   );
   
   // Créer une instance de WP_Query pour exécuter la requête avec les arguments spécifiés.
   $query = new WP_Query( $custom_args );

   // Création d'une Seconde Requête pour le Total des Photos
   // avec la liste de toutes les photos correspondant aux filtres
   $custom_args2 = array_replace($custom_args, array( 'posts_per_page' => -1, 'nopaging' => true,)); // Remplace les arguments de $custom_args pour récupérer tous les posts correspondants sans pagination.
   $total_posts = get_posts( $custom_args2 ); // Récupère tous les posts qui correspondent aux arguments.
   $nb_total_posts = count($total_posts); // Compte le nombre total de posts récupérés.
            
   //echo $query->found_posts . " articles trouvés"; 
   $max_pages = $query->max_num_pages;
  
?>
 <!-- Vérifie si le résultat de la requête contient des articles -->
  <?php if($query->have_posts()) : ?>
   <article class="container-common flexrow">
   <?php
   //echo '<pre>';
                        //print_r($total_posts);
                        //echo '</pre>';
                        ?>
<!-- On parcourt chacun des articles résultant de la requête -->
       <?php while($query->have_posts()) :  $query->the_post(); ?>           
            <?php
               // Récupérer la taxonomie ACF actuelle
               $term = get_queried_object();                                              
               $term_id  = get_acf_custom_field_value('ID', $term);
               // Récupération du nom de la catégorie 
               $categorie  = get_acf_custom_field_value('name', get_field('categorie')); 
            ?>
           <!-- Affichage des Détails de l'Article -->
           <div class="news-info brightness">
               <?php if(has_post_thumbnail()) : ?>
                  <div class="thumbnail">
                     <h3 class="info-title"><?php echo get_field('reference'); ?></h3>
                     <p class="info-tax"><?php echo $categorie; ?></p>
                     <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" alt="<?php the_title(); ?>" aria-label="<?php the_title(); ?>"><span class="detail-photo"></span></a>                            
                     <?php the_post_thumbnail('desktop-home'); ?>                     
                     <form>
                        <input type="hidden" name="postid" class="postid" value="<?php the_id(); ?>">
                        <button class="openLightbox" title="Afficher la photo en plein écran" alt="Afficher la photo en plein écran"
                              data-postid="<?php echo get_the_id(); ?>"            
                              data-arrow="true">                       
                        </button>
                     </form>
                  </div>   
                  <?php endif; ?>
            </div>
       <?php endwhile; ?>
   </article>
   <!-- Lightbox pour Afficher les Photos -->
   <div class="lightbox hidden" id="lightbox">    
      <button class="lightbox__close" title="Refermer le plein écran">Fermer</button>
      <div class="lightbox__container">               
         <div class="lightbox__loader hidden"></div>
         <div class="lightbox__container_info flexcolumn" id="lightbox__container_info"> 
            <div id="lightbox__container_content" class="lightbox__container_content flexcolumn" ></div>   
               <button class="lightbox__next" aria-label="Voir la photo suivante" title="Photo suivante"></button>
               <button class="lightbox__prev" aria-label="Voir la photo précédente" title="Photo précédente"></button>                     
            </div>        
         </div>
      </div> 
   </div>
<?php else : ?>
   <p>Désolé, aucun article ne correspond à cette requête</p>  
<?php endif; 
   // On réinitialise à la requête principale
  wp_reset_query();
?>

<!-- Variables qui vont pourvoir être récupérées par JavaScript -->
<form>
<?php
// Supposons que 'total_posts' soit un tableau de données que vous souhaitez transmettre au JS
$total_posts = get_posts(); 

// Vérifier si total_posts n'est pas vide
if (!empty($total_posts)) {
    $json_total_posts = json_encode($total_posts);
} else {
    $json_total_posts = '[]'; // Envoi d'un tableau vide en cas de problème
}
?>
   <input type="hidden" name="total_posts" id="total_posts" value="<?php echo esc_attr($json_total_posts); ?>">
   <input type="hidden" name="nb_total_posts" id="nb_total_posts" value="">
   <input type="hidden" name="categorie_id" id="categorie_id" value="">
   <input type="hidden" name="format_id" id="format_id" value="">
   <input type="hidden" name="orderby" id="orderby" value="">
   <input type="hidden" name="order" id="order" value="">
   <input type="hidden" name="max_pages" id="max_pages" value="">
   <input type="hidden" name="ajaxurl" id="ajaxurl" value="<?php echo admin_url( 'admin-ajax.php' ); ?>">
  <!-- c’est un jeton de sécurité, pour s’assurer que la requête provient bien de ce site, et pas d’un autre -->
   <input type="hidden" name="nonce" id="nonce" value="<?php echo wp_create_nonce('nathalie_mota_nonce'); ?>" /> 
</form>  


