<?php
/**
 * The template for displaying the footer
 *
 * @package WordPress
 * @subpackage nathaliemota
 */
?>
	<footer id="footer">
		<?php 
			// Affichage du menu footer déclaré dans functions.php
			wp_nav_menu(array('theme_location' => 'footer-menu')); 
		?>		
		
	</footer>

	<!-- Lance la popup contact -->
	<?php 
        get_template_part ('templates_part/modal/contact-modal'); 		
    ?>	

<?php wp_footer(); ?>

</body>
</html>
