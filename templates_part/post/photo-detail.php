<?php 
//echo ('photo-detail.php');
    // Vérifier si ACF est activé
    if ( !function_exists('get_field')) return;

    // Récupérer les champs ACF associés à la photo
    $reference = get_field('reference');
    //var_dump(get_field('reference'));
    $categorie = get_field('categorie');
    $format = get_field('format');
    $type = get_field('type');
    $annee = get_field('date_prise_vue');
?>

<article class="container__photo flexcolumn">
    <div class="photo__info flexrow">
        <div class="photo__info--description flexcolumn"> <!-- Bloc 1 : Infos photo -->
            <h1><?php the_title(); ?></h1>
            <ul class="flexcolumn">
                <li class="reference">Référence : 
                    <?php echo $reference ? $reference : 'Inconnue'; ?>
                </li>
                <li>Catégorie : 
                    <?php echo $categorie ? $categorie : 'Inconnue'; ?>
                </li>
                <li>Format : 
                    <?php echo $format ? $format : 'Inconnu'; ?>
                </li>
                <li>Type : 
                    <?php echo $type ? $type : 'Inconnu'; ?>
                </li>
                <li>Année : 
                    <?php echo $annee ? $annee : 'Inconnue'; ?>
                </li>
            </ul>
        </div>
        <div class="photo__info--image flexcolumn"> <!-- Bloc 2 : Photo -->
            <div class="container--image brightness">
                <!-- permet d’afficher l’image mise en avant -->
                <?php the_post_thumbnail('medium_large'); ?>            
                <span class="openLightbox"></span>
            </div>
            <form>
                <input type="hidden" name="postid" class="postid" value="<?php the_id(); ?>">
                <button class="openLightbox" title="Afficher la photo en plein écran" alt="Afficher la photo en plein écran"
                    data-postid="<?php echo get_the_id(); ?>"       
                    data-arrow="false"
                    data-nonce="<?php echo wp_create_nonce('nathalie_mota_lightbox'); ?>"
                    data-action="nathalie_mota_lightbox"
                    data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>"
                >
                </button>
            </form>                     
    </div>
</article>