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
		
        <div class="photo__contact flexrow"> <!-- Bloc 3 : Interactions -->
			<p>Cette photo vous intéresse ? <a href="#contact-form" class="btn contact">Contact</a></p>
			<div class="site__navigation flexrow">				
				<div class="site__navigation__prev">
				<?php
					$prev_post = get_previous_post();							
					if($prev_post) {
						$prev_title = strip_tags(str_replace('"', '', $prev_post->post_title));
						$prev_post_id = $prev_post->ID;
                        // Création du Lien vers le Post Précédent
						echo '<a rel="prev" href="' . get_permalink($prev_post_id) . '" title="' . $prev_title. '" class="previous_post">';
						if (has_post_thumbnail($prev_post_id)){
							?>
							<div>
								<?php echo get_the_post_thumbnail($prev_post_id, array(81,71));?></div>
							<?php
							}
							else{
								echo '<img src="'. get_stylesheet_directory_uri() .'/assets/img/no-image.jpeg" alt="Pas de photo" width="71px" ><br>';
							}							
							echo '<img src="'. get_stylesheet_directory_uri() .'/assets/img/precedent.png" alt="Photo précédente" ></a>';
					    }
			            ?>
		        </div>
                <div class="site__navigation__next">
					<?php
						$next_post = get_next_post();
						if($next_post) {
							$next_title = strip_tags(str_replace('"', '', $next_post->post_title));
							$next_post_id = $next_post->ID;
                            // Création du Lien vers le Post Suivant
							echo  '<a rel="next" href="' . get_permalink($next_post_id) . '" title="' . $next_title. '" class="next_post">';
							if (has_post_thumbnail($next_post_id)){
							?>
								<div><?php echo get_the_post_thumbnail($next_post_id, array(81,71));?></div>
							<?php
							}
							else{
								echo '<img src="'. get_stylesheet_directory_uri() .'/assets/img/no-image.jpeg" alt="Pas de photo" width="71px" ><br>';
							}							
							echo '<img src="'. get_stylesheet_directory_uri() .'/assets/img/suivant.png" alt="Photo suivante" ></a>';
						}
					?>
					
				</div>
    </section>            
<?php endwhile; endif; ?>

<?php get_footer();?>
