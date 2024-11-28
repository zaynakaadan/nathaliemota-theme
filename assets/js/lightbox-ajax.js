// Script pour la gestion de la Lightbox sur toutes les photos en dehors de la page d'accueil

/**
 * Variables récupérées / renvoyées
 *
 * nonce : jeton de sécurité
 * ajaxurl : adresse URL de la fonction Ajax dans WP
 *
 * total_posts : tableau de toutes les données des photos correspondantes aux filtres
 * nb_total_posts : nombres de photos à afficher
 * photo_id : indentifiant de la photo à afficher
 *
 */

document.addEventListener("DOMContentLoaded", function () {
    // console.log("Script lightbox lancé !");
  
     
    // Récupération du tableau de toutes les photos selon les filtres
    let total_posts = "";
    if (document.getElementById("total_posts") !== null) {
      total_posts = document.getElementById("total_posts").value;
  
      // Supression du début "Array (" et de la fin ")" pour n'avoir que les données du tableau d'origine
      total_posts = total_posts.slice(8, total_posts.length - 3); // Nettoyage du format de tableau PHP
    }
  
    let nb_total_posts = 1;
    if (document.getElementById("nb_total_posts") !== null) {
      nb_total_posts = document.getElementById("nb_total_posts").value;
    }
  
    // Intialisation des données pour le filtrage
    let regex1 = /[(]/g;
    let regex2 = /[)]/g;
  
    let arrayInitial = total_posts;
    let arrayFinish = new Array();
    let data = new Array();
  
    recupArrayPhp();
  
    let idPhoto = null;
    let idValue = 10;
    let arrow = "";
  
    function recupArrayPhp() {
      // Récupérarion des données qui sont en texte et transfert dans un tableau javascript
  
      // Parcour des données pour en extraire les éléments de chaque photo
      // et les regroupper dans un seul élément commun
      for (let pas = 0; pas < nb_total_posts; pas++) {
      let  start = arrayInitial.search(regex1) + 2;
      let  end = arrayInitial.search(regex2);
      let  next = end + 1;
  
        // On extrait les informations de la photo et on les regroupe
        arrayFinish.push(arrayInitial.slice(`${start}`, `${end}`));
  
        // On retire ces éléments pour le filtrage suivant
        arrayInitial = arrayInitial.slice(`${next}`, -1);
      }
    }
  
    // Récupérération de la position de la photo dans le tableau
    function recupIdData(arg) {
      // On parcour le tableau à la recherche de l'identifiant de la photo
      for (let i = 0; i < nb_total_posts; i++) {
        data = arrayFinish[i].split("\n");
        let position = data[0].search("ID") + 7;
        if (data[0].slice(`${position}`) == arg) {
          idValue = i;
        }
      }
    }
  
    // Récupérération de l'identifiant de la photo en fonction de notre position dans la tableau
    function recupIdPhoto(arg) {
      data = arrayFinish[arg].split("\n");
      let position = data[0].search("ID") + 7;
      idPhoto = data[0].slice(`${position}`);
    }
  
    (function ($) {
      $(document).ready(function () {
        // Gestion de la pagination de la lightbox
        $(".openLightbox").click(function (e) {
          e.preventDefault();
  
          // L'URL qui réceptionne les requêtes Ajax dans l'attribut "action" de <form>
          const ajaxurl = $(this).data("ajaxurl");
  
          // Récupération de la variable si on la reçoit
          // si non initialisation par défaut à true
  
          arrow = "true";
          if (!$(this).data("arrow")) {
            arrow = $(this).data("arrow");
          }
  
          if (!$(this).data("postid")) {
            console.log(
              "Identifiant manquant. Récupération du premier de la liste"
            );
            recupIdPhoto(0);
          } else {
            idPhoto = $(this).data("postid");
          }
          recupIdData(idPhoto);
          // console.log("photo n° " + idValue + " de la liste - id Photo: " +  idPhoto);
  
          $(".lightbox").removeClass("hidden");
  
          // On s'assure de le container est vide avant de charger le code
          $("#lightbox__container_content").empty();
          $.changePhoto();
        });
  
        // Affichage de la photo prédécente
        $(".lightbox__prev").click(function (e) {
          e.preventDefault();
          idPhotoNext = idPhoto;
          console.log("Photo précédente");
          if (idValue > 0) {
            idValue--;
          } else {
            idValue = nb_total_posts - 1; // Revient à la dernière photo si on est à la première
          }
          recupIdPhoto(idValue);
          console.log("id: " + idValue + " - id Photo: " + idPhoto);
          $.changePhoto();
        });
  
        // Affichage de la photo suivante
        $(".lightbox__next").click(function (e) {
          e.preventDefault();
          idPhotoNext = idPhoto;
          console.log("Photo suivante");
          if (idValue < nb_total_posts - 1) {
            idValue++;
          } else {
            idValue = 0; // Retour à la première photo
          }
          console.log("id: " + idValue + " - id Photo: " + idPhoto);
          recupIdPhoto(idValue);
          $.changePhoto();
        });
  
        // Refermer la lightbox au click sur la croix
        $(".lightbox__close").click(function (e) {
          e.preventDefault();
          $.close();
        });
  
        /**
         * Récupération des évenments au clavier
         * @param {KeyboardEvent} e     */
  
        $("body").keyup(function (e) {
          e.preventDefault();
          // Refermer la lightbox en faisant echap au clavier
          if (e.key === "Escape") {
            $.close();
          }
        });
  
        // Affichage de la photo et des informations demandées
        $.changePhoto = function () {
          // Récupération la valeur du jeton de sécurité
          const nonce = $("#nonce").val();
  
          // Récupération de l'adresse de la page	pour pointer Ajax
          const ajaxurl = $("#ajaxurl").val();
  
          // On affiche une image de chargement
          $(".lightbox__loader").removeClass("hidden");
          // On cache tout le reste en attendant le réponse
          $("#lightbox__container_content").addClass("hidden");
          $(".lightbox__next").addClass("hidden");
          $(".lightbox__prev").addClass("hidden");
  
          $.ajax({
            type: "POST",
            url: ajaxurl,
            dataType: "json", // <-- Change dataType from 'html' to 'json'
            data: {
              action: 'nathalie_mota_lightbox',
              nonce: nonce,
              photo_id: idPhoto,
            },
            success: function (res) {
              if (res.success) {
                const data = res.data;
                
                // Injecter l'image
                const imageHtml = `<img src="${data.thumbnail}" alt="${data.title}" class="lightbox__image">`;
                const titleHtml = `<h4 class="photo-title">${data.title}</h4>`;
                const categoryHtml = `<p class="photo-category">${data.categories}</p>`;
                //const yearHtml = `<p class="photo-year">${data.year}</p>`;
                
                // Insérer l'image et les informations dans la lightbox
                $("#lightbox__container_content").html(imageHtml + titleHtml + categoryHtml ).removeClass("hidden");
                $(".lightbox__loader").addClass("hidden");
          
                // Afficher les flèches si nécessaire
                if (arrow && nb_total_posts > 1) {
                  $(".lightbox__next, .lightbox__prev").removeClass("hidden");
                }
              } else {
                console.error("Erreur : " + res.data.message);
              }
            },
          error: function (xhr) {
            console.error("Erreur AJAX:", xhr.responseText);
          },
        });
      };
  
  
        // On referme la lightbox au click sur la croix
        $.close = function () {
          $(".lightbox").addClass("hidden");
        };
      });
    })(jQuery);
  });
  