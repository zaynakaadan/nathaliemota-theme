<?php
	get_header();
?>
  <div id="wrap">
      <section id="content">
		<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
			
			<h1><?php the_title(); ?></h1>

			<?php the_content(); ?>

		<?php endwhile; endif; ?>
      </section>
  </div>

<?php get_footer(); ?>