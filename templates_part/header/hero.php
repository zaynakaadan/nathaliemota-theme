<?php
/**
 * Hero du header (inclus depuis templates_part)
 *
 * @package WordPress
 * @subpackage nathaliemota theme
 */
?>

<!-- Wrap Slider Area -->
<div class="hero-area">
    <div class="hero-thumbnail">
        <!-- Initialisation de la requête pour afficher une image aléatoire -->
        <?php   
            $custom_args = array( 
                'post_type' => 'photo',
                'orderby'   => 'rand',
                'posts_per_page' => 1,
            );
            $query_hero = new WP_Query( $custom_args );            
        ?>
        <!-- Récupération d'un post photo aléatoire -->
        <?php while($query_hero->have_posts()) : ?>
            <?php $query_hero->the_post();?> 

            <!-- Vérification de la présence d'une image mise en avant -->
            <?php if(has_post_thumbnail()) : ?>
                <a href="<?php the_permalink(); ?>" alt="<?php the_title(); ?>">
                    <!-- Affichage de l'image mise en avant avec la taille personnalisée "hero" -->
                    <?php the_post_thumbnail('hero'); ?>
                </a>
            <?php endif; ?>                  
                    
        <?php endwhile; ?>            
        <h1 class="title-hero">Photographe event</h1>
    </div>  
</div>
<?php
    // Réinitialisation de la requête principale
    wp_reset_postdata();       
?>