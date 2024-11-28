<?php
/**
 * Modal publication
 *
 * @package WordPress
 * @subpackage nathaliemota-theme
 */
?>

<?php 
    // Vérification de la présence d'une vignette (post thumbnail)
    if(has_post_thumbnail()) : ?>                        

<?php
    // Récupérer la taxonomie ACF actuelle
    $term = get_queried_object(); 
   // var_dump($term); // Affiche l'objet de taxonomie actuel pour déboguer
                                             
    $term_id  = get_acf_custom_field_value('ID', $term);
    //var_dump($term_id); // Affiche l'ID pour vérifier
    // Récupération du nom de la catégorie 
    $categorie  = get_acf_custom_field_value('name', get_field('categorie')); 
    //var_dump($categorie); // Affiche le nom de la catégorie pour déboguer

    // Récupérer la référence
    $reference = get_field('reference'); 
?>

<!-- Génération du nombre de photo en fonction de l'option dans WordPress -->
<div class="news-info brightness">
    <h2 class="info-title"><?php echo esc_html($reference); ?></h2>
    <p class="info-tax"><?php the_terms( $post->ID, 'categorie', '' ); ?></p>
    <a href="<?php the_permalink() ?>" aria-label="Voir le détail de la photo <?php echo esc_attr($reference); ?>" alt="<?php echo esc_attr($reference); ?>" title="Voir le détail de la photo"><span class="detail-photo"></span></a>                            
    <?php the_post_thumbnail(); ?>
    <p><?php the_terms( $post->ID, 'categorie', '' ); ?></p>
    <form>
        <input type="hidden" name="postid" class="postid" value="<?php echo get_the_id(); ?>">                       
        <a class="openLightbox" title="Afficher la photo en plein écran" alt="Afficher la photo en plein écran"
            data-postid="<?php echo get_the_id(); ?>"    
            data-arrow="true" 
        >
        </a>
    </form>
</div> 

<?php endif; ?> 
