<?php
// Gestion des filtres d'affichage des photos en page d'accueil (front-page)

// Récupération des catégories et formats
$categorie_id = isset($_POST['categorie_id']) ? intval($_POST['categorie_id']) : 0;
$format_id = isset($_POST['format_id']) ? intval($_POST['format_id']) : 0;

// Récupération des termes pour les catégories et des formats dans WordPress
$categorie_acf = get_terms('categorie', array('hide_empty' => false)); //permet de récupérer même les catégories et formats sans contenu (termes vides)
$format_acf = get_terms('format', array('hide_empty' => false));


// Débogage : affichez les catégories récupérées
//if (is_wp_error($categorie_acf)) {
//    var_dump($categorie_acf); // Affiche l'erreur
//} else {
//    var_dump($categorie_acf); // Affiche les termes récupérés
//}

?>

<!-- Début du conteneur pour la zone de filtres -->
<div class="filter-area ">
    <form class="flexrow" method="post">
        <div class="filterleft  flexrow">
            <div id="filtre-categorie" class="select-filter flexcolumn">
                <span class="categorie_id-down dashicons dashicons-arrow-down-alt2 select-close"></span>
                <label for="categorie_id"></label>
                <select class="option-filter" name="categorie_id" id="categorie_id">
                    <option value="" disabled selected hidden>CATÉGORIES</option>
                    <option id="categorie_0" value=""></option>
                    <?php
                    if (!empty($categorie_acf) && !is_wp_error($categorie_acf)) :
                        foreach ($categorie_acf as $term) :
                            $selected = ($term->term_id == $categorie_id) ? 'selected' : '';
                            ?>
                            <option id="categorie_<?php echo esc_attr($term->term_id); ?>" value="<?php echo esc_attr($term->term_id); ?>" <?php echo $selected; ?>>
                                <?php echo esc_html($term->name); ?>
                            </option>
                        <?php
                        endforeach;
                    else :
                        echo '<option value="">Aucune catégorie disponible</option>';
                    endif;
                    ?>
                </select>
            </div>

            <div id="filtre-format" class="select-filter flexcolumn">
                <span class="format_id-down dashicons dashicons-arrow-down-alt2 select-close"></span>
                <label for="format_id"></label>
                <select class="option-filter" name="format_id" id="format_id">
                    <option value="" disabled selected hidden>FORMATS</option>
                    <option id="format_0" value=""></option>
                    <?php
                    if (!empty($format_acf) && !is_wp_error($format_acf)) :
                        foreach ($format_acf as $term) :
                            $selected = ($term->term_id == $format_id) ? 'selected' : '';
                            ?>
                            <option id="format_<?php echo esc_attr($term->term_id); ?>" value="<?php echo esc_attr($term->term_id); ?>" <?php echo $selected; ?>>
                                <?php echo esc_html($term->name); ?>
                            </option>
                        <?php
                        endforeach;
                    else :
                        echo '<option value="">Aucun format disponible</option>';
                    endif;
                    ?>
                </select>
            </div>
        </div>

        <div class="filterright  flexrow">
            <div id="filtre-date" class="select-filter flexcolumn">
                <span class="date-down dashicons dashicons-arrow-down-alt2 select-close"></span>
                <label for="date"></label>
                <select class="option-filter" name="date" id="date">
                    <option value="" disabled selected hidden>TRIER PAR</option>
                    <option value=""></option>
                    <option value="desc">Nouveauté</option>
                    <option value="asc">Les plus anciens</option>
                </select>
            </div>
        </div>
    </form>
</div>
