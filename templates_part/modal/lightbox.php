<?php
/**
 * Modal lightbox
 *
 * @package WordPress
 * @subpackage nathaliemota-theme
 */

 // Récupérer la taxonomie actuelle
 $term = get_queried_object();
 $term_id  = get_acf_custom_field_value('ID', $term);

 $categorie  = get_acf_custom_field_value('name', get_field('categorie'));

?>
<?php the_post_thumbnail('lightbox'); ?>
<h4 class="photo-title photo-title-<?php the_id(); ?>"><?php the_title(); ?></h4>
<div class="lightbox__info flexrow">
     <p class="photo-category-<?php the_id(); ?>"><?php echo $categorie; ?></p>
    
</div> 



