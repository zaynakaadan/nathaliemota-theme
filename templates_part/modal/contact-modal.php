<?php
/**
 * Modal contact
 *
 * @package WordPress
 * @subpackage nathaliemota theme
 */
?>

<!-- contact-modal.php -->
<div class="popup-overlay hidden">
	<div class="popup-contact">
		<div class="popup-title__container">			
			<div class="popup-title"></div>
			<div class="popup-title"></div>
		</div>
		<div class="popup-informations">	
			<?php 
				echo do_shortcode('[contact-form-7 id="2f720ae" title="Formulaire de contact"]');
			?>
		</div>	
	</div>
</div>