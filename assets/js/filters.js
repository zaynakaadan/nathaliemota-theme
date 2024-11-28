// Script pour gérer les filtres d'affichage en page d'accueil (front-page)
console.log("Script filtres en ajax lancé !!!");

/**
 * Variables récupérées / renvoyées
 *
 * nonce : un jeton de sécurité pour éviter les attaques CSRF.
 * ajaxurl : l'URL de l'endpoint AJAX de WordPress.
 * categorie_id et format_id : IDs des catégories et formats sélectionnés.
 * order : ordre de tri Croissant (ASC) ou Décroissant (DEC).
 * orderby : critère de tri (actuellement par date).
 * currentPage :  numéro de la page affichée.
 * max_pages : page maximum en fonction des filtres.
 * nb_total_posts : nombre total de photos à afficher.
 *
 */

document.addEventListener("DOMContentLoaded", function () {
  const body = document.querySelector("body");
  const allDashicons = document.querySelectorAll(".dashicons");
  const allSelect = document.querySelectorAll("select");
  const message = "<p>Désolé. Aucun article ne correspond à cette demande.</p>";

  // Initialisation des variables des filtres au premier affichage du site
  let categorie_id = "";
  let format_id = "";
  let order = "";
  let currentPage = 1;
  let max_pages = 1;
  let nb_total_posts = 0; // Initialiser nb_total_posts 
  let selectId = "";

  // Réinitialiser les valeurs des filtres
  const resetFilters = () => {
    if (document.getElementById("categorie_id")) {
      document.getElementById("categorie_id").value = "";
    }
    if (document.getElementById("format_id")) {
      document.getElementById("format_id").value = "";
    }
    if (document.getElementById("date")) {
      document.getElementById("date").value = "";
    }
  };

  resetFilters();
  //console.log("Vérification de l'élément max_pages :", document.getElementById("max_pages"));
  //console.log("Vérification de l'élément nb_total_posts :", document.getElementById("nb_total_posts"));

  document.getElementById("currentPage").value = 1;

  // Gestion des changements de filtres
  (function ($) {
    $(document).ready(function () {
      $(".option-filter").change(function (e) {
        // Empêcher l'envoi classique du formulaire
        e.preventDefault();

        // Récupération du jeton de sécurité et de l'adresse de la page pour pointer Ajax
        const nonce = $("#nonce").val();
        const ajaxurl = $("#ajaxurl").val();

        if (document.getElementById("max_pages") !== null) {
          max_pages = document.getElementById("max_pages").value;
        }
        
        // Récupération des valeurs sélectionnées
        let targetName = e.target.name;
        let targetValue = e.target.value;

        // Réaffectation de la valeur dans la variable correspondante
        switch (targetName) {
          case "categorie_id":
            categorie_id = Number(targetValue);
            break;
          case "format_id":
            format_id = Number(targetValue);
            break;
          case "date":
            order = targetValue;
            break;
        }

        let orderby = "date";
        console.log({
            nonce,
            ajaxurl,
            categorie_id,
            format_id,
            orderby,
            order,
        });
        $(".publication-list").addClass("loading"); // Ajout d'un indicateur de chargement
        
        // Génération du nouvel affichage
        $.ajax({
          type: "POST",
          url: ajaxurl,
          dataType: "json", // Change dataType from 'html' to 'json'
          data: {
            action: "nathalie_mota_load",
            nonce: nonce,
            paged: 1,
            categorie_id: categorie_id,
            format_id: format_id,
            orderby: orderby,
            order: order,
          },
          success: function (res) {
            console.log("Réponse complète :", res);
            // Si on utilise wp_send_json_success, accéde aux données via res.data
            let data = res.data || res;

            console.log("HTML retourné :", data.html);
            //console.log("Nombre total de posts :", data.nb_total_posts);
            //console.log("Nombre maximal de pages :", data.max_pages);

             // Vérifier si la réponse contient du contenu HTML
            if (data.html) {
            $(".publication-list").empty().append(data.html);
            } else {
            // Afficher un message d'erreur si aucun contenu HTML n'est retourné
            $(".publication-list").empty().append(message);
            }
          
            console.log("max_pages:", data.max_pages);
            console.log("nb_total_posts:", data.nb_total_posts);

            max_pages = data.max_pages || 1;
            nb_total_posts = data.nb_total_posts || 0;

            // Log pour vérifier si plusieurs posts sont effectivement retournés
            console.log("Nombre total de posts :", nb_total_posts);
            console.log("HTML retourné :", data.html);
  
          // Cacher ou afficher le bouton "load-more"
            $("#load-more").toggleClass("hidden", currentPage >= max_pages || nb_total_posts === 0);
          
            // Ajouter un message s'il n'y a pas de posts
            if (nb_total_posts === 0) {
              $(".publication-list").append(message);
            }
          
            document.getElementById("currentPage").value = 1; // Réinitialisation de la page
          },
          error: function (xhr, status, error) {
            //console.error("Erreur lors de la requête AJAX :");
            //console.error("Statut :", status);
            //console.error("Erreur :", error);
            //console.error("Réponse du serveur :", xhr.responseText);
    $(".publication-list").append("<p>Une erreur est survenue. Impossible de charger les données.</p>");
          }
        });
      });
    });
  })(jQuery);

  // Réinitialisation des flèches des select si on clique en dehors
  body.addEventListener("click", (e) => {
    if (e.target.tagName.toLowerCase() !== "select") {
        initArrow();
    }
  });

  // Fonction pour rechercher un mot dans une variable
  function findWord(word, str) {
    return RegExp("\\b" + word + "\\b").test(str);
  }

  // Réinitialisation de l'affichage des flèches sur les select
  const initArrow = () => {
    console.log("Initialisation des flèches");
    allDashicons.forEach((dashicons) => {
      dashicons.classList.add("select-close");
      dashicons.classList.remove("select-open");
    });
  };

  // Passer de la flèche qui descend à la flèche qui monte et inversement
  const arrow = (arg) => {
    allDashicons.forEach((dashicons) => {
      if (findWord(arg, dashicons.className)) {
        if (findWord("select-close", dashicons.className)) {
          dashicons.classList.remove("select-close");
          dashicons.classList.add("select-open");
        } else {
          dashicons.classList.add("select-close");
          dashicons.classList.remove("select-open");
        }
      }
    });
  };

  // Détection du click sur un select et modification de la flèche correspondante
  allSelect.forEach((select) => {
    select.addEventListener("click", (e) => {
      e.preventDefault();
      if (select.id !== selectId) {
        initArrow();
      }
      selectId = select.id;
      arrow(selectId);
    });
  });
});
