<?php
/**
 * The front-page : ACCUEIL 
 * @package WordPress
 * @subpackage nathaliemota-theme
 */

get_header();
?>
<div id="front-page"> 
    <section id="content">    
      <!-- Chargement du hero -->
      <?php get_template_part( 'templates_part/header/hero' ); ?>

      <!-- Chargement des filtres -->
      <?php get_template_part( 'templates_part/post/photo-filter' ); ?>
       
      <?php  
      // Initialisation des variables pour les filtres de requettes Query
        $categorie_id = "";
        $format_id = "";
        $order = "";
        $orderby = "date";
         
 
        // Initialisation du filtre d'affichage des posts
        $paged =  get_query_var( 'paged' )  ? get_query_var( 'paged' ) : 1;
        // Récupérer la taxonomie actuelle
        $term = get_queried_object();
        $term_id  = get_acf_custom_field_value('ID', $term);


        //$categorie_id  =  get_post_meta( get_the_ID(), 'categorie', true );
        // $format_id  =  get_post_meta( get_the_ID(), 'format', true );
        // $order = "";
        // $orderby = "date";
         $paged = get_query_var('paged') ? get_query_var('paged') : 1;


        // Arguments de la requête personnalisée
        $custom_args = array(
            'post_type' => 'photo',
            'posts_per_page' => 8, // Valeur par défaut
            'order' => $order ?: 'DESC', // Par défaut : 'DESC' 
            'orderby' =>  $orderby, // 'date' , 'meta_value_num', rand
            'paged' => 1,
            'meta_query' => array_filter(array(
                'relation' => 'AND', 
                !empty($categorie_id) ? array(
                    'key' => 'categorie',
                    'compare' => 'LIKE', 
                    'value' => $categorie_id,
                ) : null,
                !empty($format_id) ? array(
                    'key' => 'format',
                    'compare' => 'LIKE',
                    'value' => $format_id,
                ) : null
            )),
            'nopaging' => false,
        );            
            //Crée une instance de requête WP_Query basée sur les critères placés dans la variables $args
            $query = new WP_Query( $custom_args ); 
            $max_pages = $query->max_num_pages;
            
            // Debug : afficher le contenu de la requête pour vérifier les résultats
            //echo '<pre>';
            //print_r($query);
            //echo '</pre>';

            // Création du filtre pour la lightbox pour créer un tableau 
            // avec la liste de toutes les photos correspondantes aux filtres
            $custom_args2 = array_replace($custom_args, array( 'posts_per_page' => -1, 'nopaging' => true,));
            $total_posts = get_posts( $custom_args2 );
            $nb_total_posts = count($total_posts);          
                      
            ?>
            <!-- On vérifie si le résultat de la requête contient des articles -->
            <?php if($query->have_posts()) : ?>
                <article class="publication-list container-news flexrow">
                    <!-- Mise à disposition de JS du tableau contenant toutes les données de la requette et le nombre -->
                    <form> 
                    <?php
                    //echo '<pre>';
                       // print_r($total_posts);
                       // echo '</pre>';
                        
                // Supposons que $total_posts contient un tableau d'objets WP_Post
                $post_ids = array_map(function ($post) {
                return $post->ID; // Extraire uniquement les IDs
                }, $total_posts);

                // Convertir le tableau d'IDs en JSON
                $post_ids_json = json_encode($post_ids);

                    ?>
                        <input type="hidden" name="total_posts" id="total_posts" value="<?php echo esc_attr($post_ids_json); ?>">     
                        <input type="hidden" name="max_pages" id="max_pages" value="<?php echo esc_attr($max_pages); ?>">
                        <input type="hidden" name="nb_total_posts" id="nb_total_posts" value="<?php  echo $nb_total_posts; ?>">
                    </form> 
                    
                    <!-- On parcourt chacun des articles résultant de la requête -->
                    <?php while($query->have_posts()) : $query->the_post();
                             // var_dump($query->the_post());
                          get_template_part('templates_part/post/publication');
                    
                        endwhile; 
                    ?>
                </article>
                <div class="lightbox hidden" id="lightbox">    
                    <button class="lightbox__close" title="Refermer cet agrandissement">Fermer</button>
                    <div class="lightbox__container">
                        <div class="lightbox__loader hidden"></div>
                        <div class="lightbox__container_info flexcolumn" id="lightbox__container_info"> 
                            <div class="lightbox__container_content flexcolumn" id="lightbox__container_content"></div> 
                            <div class="lightbox__nav lightbox__next-container">  
                                <button class="lightbox__next" aria-label="Voir la photo suivante" title="Photo suivante"></button>
                                <span class="lightbox__textnex">Suivante</span>
                            </div>
                            <div class="lightbox__nav lightbox__prev-container">
                                <button class="lightbox__prev" aria-label="Voir la photo précente" title="Photo précédente"></button>
                                <span class="lightbox__textprev">Précédente</span>   
                            </div>                  
                        </div>
                    </div> 
                </div>
            <?php else : ?>
                <p>Désolé. Aucun article ne correspond à cette demande.</p>          
            
            <?php endif; ?>
        
        <?php
        // On réinitialise à la requête principale
        // wp_reset_query(); 
        wp_reset_postdata();       
        ?>
        
        <div id="pagination">
            <!-- afficher le système de pagination (s’il existe de nombreux articles) -->
            <!-- <h3>Articles suivants</h3> -->
            <!-- Variables qui vont pourvoir être récupérées par JavaScript -->
            <form>
                <input type="hidden" name="orderby" id="orderby" value="<?php echo $orderby; ?>">
                <input type="hidden" name="order" id="order" value="<?php echo $order; ?>">
                <input type="hidden" name="posts_per_page" id="posts_per_page" value="<?php echo get_option( 'posts_per_page'); ?>">
                <input type="hidden" name="currentPage" id="currentPage" value="<?php  echo $paged; ?>">
                <input type="hidden" name="ajaxurl" id="ajaxurl" value="<?php echo admin_url( 'admin-ajax.php' ); ?>">
                <!-- c’est un jeton de sécurité, pour s’assurer que la requête provient bien de ce site, et pas d’un autre -->
                <input type="hidden" name="nonce" id="nonce" value="<?php echo wp_create_nonce( 'nathalie_mota_nonce' ); ?>" > 
                <!-- On cache le bouton s'il n'y a pas plus d'1 page -->
                <?php if ($max_pages > 1): ?>
                  <div class="container">
                    <button class="btn_load-more" id="load-more">Charger plus</button>
                  </div>  
                <?php endif ?>
            </form>                 
        </div>       
    </section>   

</div>
<?php get_footer(); ?>
