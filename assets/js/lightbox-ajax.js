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
  let nb_total_posts = 1;
  let posts_per_page = 1;
  let idPhoto = null;
  let arrow = true;
  let totalPosts = [];
  let idValue = 10;
  recupData();
  recupIdPhoto();
  
   // Fonction pour récupérer les données nécessaires
   function recupData() {
    console.log("Élément trouvé :", document.getElementById("total_posts"));
    const totalPostsElement = document.getElementById("total_posts");
    
    if (totalPostsElement !== null) {
      const totalPostsString = totalPostsElement.value;
      try {
        const parsedTotalPosts = JSON.parse(totalPostsString);
         // Vérification si totalPosts est un tableau valide
      if (!Array.isArray(parsedTotalPosts) || parsedTotalPosts.length === 0) {
        console.error("Erreur : total_posts est vide ou invalide.");
      } else {
        totalPosts = parsedTotalPosts; // Mise à jour de totalPosts
                nb_total_posts = totalPosts.length; // Mise à jour du nombre total de posts
                console.log("Liste des IDs des posts :", totalPosts);
      }

    } catch (error) {
      console.error("Erreur lors de l'analyse JSON de total_posts :", error);
    }
  } else {
    console.error("L'élément #total_posts est introuvable.");
  }

    // Initialisation des autres variables
    nb_total_posts = document.getElementById("nb_total_posts") ? parseInt(document.getElementById("nb_total_posts").value, 10) : nb_total_posts;
    console.log("nb_total_posts après récupération :", nb_total_posts);
    posts_per_page = document.getElementById("posts_per_page") ? parseInt(document.getElementById("posts_per_page").value, 10) : 1;
  }


  function recupIdPhoto(arg) {
    console.log("nb_total_posts au moment de recupIdPhoto:", nb_total_posts);
    //console.log("Argument fourni (arg):", arg);

    // Vérification si arg est défini et dans les limites
    if (typeof arg !== "number" || isNaN(arg) || arg < 0 || arg >= nb_total_posts) {
        //console.error("Erreur : l'argument est undefined ou hors des limites.");
        return;// Si l'indice est invalide, ne pas procéder
    }

    // Vérification d'idPhoto
    if (typeof idPhoto === "undefined" || idPhoto < 0 || idPhoto >= nb_total_posts) {
        console.error("ID de photo invalide :", idPhoto);
        return;
    }
    // Si tout est valide, on utilise l'indice directement pour l'ID
    idPhoto = arg; // Pas besoin de `indexOf` si `arg` est déjà l'indice valide
    console.log("ID de la photo à afficher :", idPhoto);
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
        recupIdPhoto(idPhoto);
         console.log("photo n° " + idValue + " de la liste - id Photo: " +  idPhoto);

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
          idValue = nb_total_posts - 1;
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
          idValue = 0;
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
        console.log("Début de $.changePhoto() : idPhoto =", idPhoto, "nb_total_posts =", nb_total_posts);
        // Récupération du jeton de sécurité
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
            action: "nathalie_mota_lightbox",
            nonce: nonce,
            photo_id: idPhoto,// Passer l'ID de la photo actuelle
          },
          success: function (res) {
            if (res.success) {
              console.log("Réponse AJAX réussie : ", res.data);
              const data = res.data;
              const imageHtml = `<img src="${data.thumbnail}" alt="${data.title}" class="lightbox__image">`;
              const titleHtml = `<h4 class="photo-title">${data.title}</h4>`;
              //const categoryHtml = `<p class="photo-category">${data.categories}</p>`;

              // Mettre à jour le contenu de la lightbox avec la nouvelle photo
              $("#lightbox__container_content").html(imageHtml + titleHtml ).removeClass("hidden");
              $(".lightbox__loader").addClass("hidden");
      
           // Si nb_total_posts > 1, on affiche les flèches
        if (nb_total_posts > 1) {
          $(".lightbox__next").removeClass("hidden");
          $(".lightbox__prev").removeClass("hidden");
        }
          } else {
            console.error("Erreur : " + res.data.message);
          }
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
