<?php
/**
 * The single : ATRICLE PHOTO 
 *
 * @package WordPress
 * @subpackage nathaliemota-theme
 */

	get_header();
?>

<?php
if( have_posts() ) : while( have_posts() ) : the_post(); ?>
	<section class="photo_detail">
		<?php get_template_part ( 'templates_part/post/photo-detail'); ?>
		<?php
// Supposons que 'total_posts' soit un tableau de données que vous souhaitez transmettre au JS
$total_posts = get_posts(); 
$nb_total_posts = count($total_posts);
 
// Supposons que $total_posts contient un tableau d'objets WP_Post
$post_ids = array_map(function ($post) {
	return $post->ID; // Extraire uniquement les IDs
	}, $total_posts);

	// Convertir le tableau d'IDs en JSON
	$post_ids_json = json_encode($post_ids);
?>
<input type="hidden" id="total_posts" value='<?php echo esc_attr($post_ids_json); ?>'>
<input type="hidden" name="max_pages" id="max_pages" value="<?php echo esc_attr($max_pages); ?>">
<input type="hidden" name="nb_total_posts" id="nb_total_posts" value="<?php  echo $nb_total_posts; ?>">

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
			</div>
		</div>
		<div class="photo__others flexcolumn">
			<h2>Vous aimerez aussi</h2>		
			<div class="photo__others--images flexrow">
				<?php 
					get_template_part ( 'templates_part/post/photo-common');
				 ?>
			</div>
		</div>		
    </section>            
<?php endwhile; endif; ?>
<div class="lightbox hidden" id="lightbox">    
                    <button class="lightbox__close" title="Refermer cet agrandissement">Fermer</button>
                    <div class="lightbox__container">
                        <div class="lightbox__loader hidden"></div>
                        <div class="lightbox__container_info flexcolumn" id="lightbox__container_info"> 
                            <div class="lightbox__container_content flexcolumn" id="lightbox__container_content"></div> 
                            <div class="lightbox__nav lightbox__next-container">  
                                <button class="lightbox__next" aria-label="Voir la photo suivante" title="Photo suivante"></button>
                                
                            </div>
                            <div class="lightbox__nav lightbox__prev-container">
                                <button class="lightbox__prev" aria-label="Voir la photo précente" title="Photo précédente"></button>
                                   
                            </div>                  
                        </div>
                    </div> 
                </div>
<?php get_footer();?>
