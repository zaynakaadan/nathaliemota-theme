// Gestion de l'affichage des photos supplémentaires en page d'accueil
// en fonction de la valeur des filters

/**
 * Variables récupérées / renvoyées
 *
 * nonce : jeton de sécurité
 * ajaxurl : adresse URL de la fonction Ajax dans WP
 *
 * categorie_id : n° de la catégorie demandée ou vide si on ne filter pas par catégorie
 * format_id : n° du format demandé ou vide si on ne filtre pas par format
 * order : ordre de tri Croissant (ASC) ou Décroissant (DEC)
 * orderby : actuellement on trie par la date mais on pourrait éventuellement avoir un autre critère
 * posts_per_page : nombre de photos à afficher en même temps
 * currentPage : page affichée au moment de l'utilisation du script
 * max_pages : page maximum en fonction des filters
 *
 */

document.addEventListener("DOMContentLoaded", function () {
    // Récupération des variables de PHP  
    (function ($) {
      $(document).ready(function () {
        let currentPage = 1; // Valeur par défaut
  
        // Gestion de la pagination des photos en page d'accueil
        $("#load-more").click(function (e) {
          e.preventDefault(); // Empêche le comportement par défaut du bouton
  
          // L'URL qui réceptionne les requêtes Ajax dans l'attribut "action" de <form>
          // Récupération du jeton de sécurité
          const nonce = $("#nonce").val();
  
          // Récupération de l'adresse de la page	pour pointer Ajax
          const ajaxurl = $("#ajaxurl").val();
          
           // Vérification si l'élément currentPage existe et récupération de sa valeur
           const currentPageElem = document.getElementById("currentPage");
           if (currentPageElem) {
               currentPage = parseInt(currentPageElem.value, 10) || 1;  // Valeur par défaut si null ou NaN
           } else {
              console.warn("L'élément #currentPage n'a pas été trouvé !");
              }

          // Récupération des valeurs des variables du filtre au moment du click
          const categorie_id = document.getElementById("categorie_id") ? document.getElementById("categorie_id").value : '';
          const format_id = document.getElementById("format_id") ? document.getElementById("format_id").value : '';
          let order = document.getElementById("date") ? document.getElementById("date").value : 'DESC';
          let orderby = "date";
  
          const max_pages = document.getElementById("max_pages").value;
  
          // currentPage + 1, pour pouvoir charger la page suivante
          currentPage++;
          if (currentPageElem) {
            currentPageElem.value = currentPage;
        }
  
          if (currentPage >= max_pages) {
            $("#load-more").addClass("hidden");
          } else {
            $("#load-more").removeClass("hidden");
          }
  
          $.ajax({
            type: "POST",
            url: ajaxurl,
            dataType: "json", // <-- Change dataType from 'html' to 'json'
            data: {
              action: "nathalie_mota_load",
              nonce: nonce,
              paged: currentPage,
              categorie_id: categorie_id,
              format_id: format_id,
              orderby: orderby,
              order: order,
            },
  
            success: function (res) {
              console.log("Réponse AJAX reçue :", res);
              if (res.success) {
                if (res.data.html) {
                    $(".publication-list").append(res.data.html);
                }
                
                // Mise à jour de la page actuelle
                currentPage++;
                if (currentPageElem) {
                  currentPageElem.value = currentPage;
              }
              // Gestion de la visibilité du bouton "load more"
                if (currentPage >= res.data.max_pages) {
                  $("#load-more").hide();
                } else {
                  $("#load-more").show();
                } 
            } else {
                console.error("Erreur dans la réponse AJAX");
            }
            },
          });
        });
      });
    })(jQuery);
  });
  