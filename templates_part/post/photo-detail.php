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
        <div class="photo__info--description flexcolumn">
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
        <div class="photo__info--image flexcolumn">
            <div class="container--image brightness">
                <!-- permet d’afficher l’image mise en avant -->
                <?php the_post_thumbnail('medium_large'); ?>            

            </div>                     
    </div>
</article>