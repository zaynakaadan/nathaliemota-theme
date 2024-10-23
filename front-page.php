<?php
/**
 * The front-page : ACCUEIL 
 *
 * @package WordPress
 * @subpackage nathaliemota theme
 */

    get_header();
?>
<div id="front-page"> 
      <section id="content">    
        <!-- Chargement du hero -->
        <?php get_template_part( 'templates_part/header/hero' ); ?>
       </section>   

</div>
<?php get_footer(); ?>
