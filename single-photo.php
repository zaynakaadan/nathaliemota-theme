<?php
/**
 * The single : ATRICLE PHOTO 
 *
 * @package WordPress
 * @subpackage nathaliemota theme
 */

	get_header();
?>

<?php
if( have_posts() ) : while( have_posts() ) : the_post(); ?>
	<section class="photo_detail">
		<?php get_template_part ( 'templates_part/post/photo-detail'); ?>
		
        <?php endwhile; endif; ?>

<?php get_footer();?>
