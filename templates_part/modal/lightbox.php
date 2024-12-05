<?php
/**
 * Modal lightbox
 *
 * @package WordPress
 * @subpackage nathaliemota-theme
 */

 // Récupérer la taxonomie actuelle
$term = get_queried_object();
$term_id = get_acf_custom_field_value('ID', $term);

$categorie = get_acf_custom_field_value('name', get_field('categorie'));

$reference = get_field('reference');
?>
<?php the_post_thumbnail('lightbox'); ?>

<!-- Conteneur pour aligner reference et categorie -->
<div class="reference-category-container">
    <p class="info-title"><?php echo esc_html($reference); ?></p>
    <p class="info-tax"><?php the_terms($post->ID, 'categorie', ''); ?></p>
</div>



