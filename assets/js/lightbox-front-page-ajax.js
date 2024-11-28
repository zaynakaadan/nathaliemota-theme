// Script pour la gestion de la Lightbox sur toutes les photos uniqueùent sur la page d'acceuil

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
  recupData();
  recupIdPhoto();

  // Fonction pour récupérer les données nécessaires
  function recupData() {
    //console.log("Élément trouvé :", document.getElementById("total_posts"));
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
                //console.log("Liste des IDs des posts :", totalPosts);
      }

    } catch (error) {
      console.error("Erreur lors de l'analyse JSON de total_posts :", error);
    }
  } else {
    console.error("L'élément #total_posts est introuvable.");
  }

    // Initialisation des autres variables
    nb_total_posts = document.getElementById("nb_total_posts") ? parseInt(document.getElementById("nb_total_posts").value, 10) : nb_total_posts;
    //console.log("nb_total_posts après récupération :", nb_total_posts);
    posts_per_page = document.getElementById("posts_per_page") ? parseInt(document.getElementById("posts_per_page").value, 10) : 1;
  }

  // Fonction pour gérer la récupération de la photo à afficher
  function recupIdPhoto(arg) {
    console.log("nb_total_posts au moment de recupIdPhoto:", nb_total_posts);
    //console.log("Argument fourni (arg):", arg);

    // Vérification si arg est défini et dans les limites
    if (typeof arg === "undefined" || arg < 0 || arg >= nb_total_posts) {
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
      $(".publication-list").click(function (e) {
        e.preventDefault();        

        // Redirection vers la page détail si clic sur .detail-photo
        if (e.target.className === "detail-photo") {
          window.location.href = e.target.parentElement.getAttribute("href");
        }

        // Vérifie si totalPosts est valide et non vide
        if (Array.isArray(totalPosts) && totalPosts.length > 0) {
          // Forcer la conversion de totalPosts en nombres pour comparer les IDs
          totalPosts = totalPosts.map(id => parseInt(id, 10));          
          //console.log("totalPosts après conversion :", totalPosts); // Affiche totalPosts après conversion

        // Détecter un clic sur un élément avec la classe openLightbox  
        if (e.target.className === "openLightbox") {       
          const postID = parseInt($(e.target).data("postid"), 10); // Récupérer l'ID
          //console.log("ID extrait de data-postid :", postID);// Affiche l'ID extrait

        // S'assurer que l'ID récupéré est bien un nombre valide
        if (isNaN(postID)) {
          console.error("postID invalide :", postID);
            return;
          }          

        if (!Array.isArray(totalPosts) || totalPosts.length === 0) {
            console.error("Erreur : totalPosts est vide.");
            return;
          }

          // Trouver la position de l'ID de la photo dans le tableau totalPosts
          const indexInTotalPosts = totalPosts.indexOf(postID); // Trouver l'index correspondant
          // Vérifier que idPhoto est dans les limites de le tableau totalsPosrs avant de l'utiliser
          if (indexInTotalPosts === -1) {
            console.error("L'ID de la photo est hors des limites :", postID);
            //console.log("Liste actuelle des IDs dans totalPosts :", totalPosts);
            return; // Ne pas ouvrir la lightbox si l'ID est invalide
          }
          idPhoto = indexInTotalPosts; // Mettre à jour idPhoto avec l'index
          recupIdPhoto(idPhoto); // Valider l'ID
          $(".lightbox").removeClass("hidden");
          $("#lightbox__container_content").empty();
          $.changePhoto();
        }
      } else {
        console.error("Erreur : totalPosts n'est pas un tableau valide ou est vide.");
      }
      });

      $(".lightbox__prev").click(function (e) {
        e.preventDefault();
        console.log("Avant navigation : idPhoto =", idPhoto, "nb_total_posts =", nb_total_posts);
        idPhoto = (idPhoto - 1 + nb_total_posts) % nb_total_posts;  // Limiter à la taille du nombre de posts
        console.log("Après navigation arrière : idPhoto =", idPhoto, "postID =", totalPosts[idPhoto]);
        recupIdPhoto(idPhoto);
        $.changePhoto();
      });

      $(".lightbox__next").click(function (e) {
        e.preventDefault();
        console.log("Avant navigation : idPhoto =", idPhoto, "nb_total_posts =", nb_total_posts);
        idPhoto = (idPhoto + 1) % nb_total_posts;  // Limiter à la taille du nombre de posts
        console.log("Après navigation avant : idPhoto =", idPhoto, "postID =", totalPosts[idPhoto]);
        recupIdPhoto(idPhoto);
        $.changePhoto();
      });

      $(".lightbox__close").click(function (e) {
        e.preventDefault();
        $.close();
      });

      $("body").keyup(function (e) {
        if (e.key === "Escape") {
          $.close();
        }
      });

      $.changePhoto = function () {
        console.log("Début de $.changePhoto() : idPhoto =", idPhoto, "nb_total_posts =", nb_total_posts);
        const nonce = $("#nonce").val();
        const ajaxurl = $("#ajaxurl").val();

        $(".lightbox__loader").removeClass("hidden");
        $("#lightbox__container_content").addClass("hidden");
        $(".lightbox__next").addClass("hidden");
        $(".lightbox__prev").addClass("hidden");

        $.ajax({
          type: "POST",
          url: ajaxurl,
          dataType: "json",
          data: {
            action: "nathalie_mota_lightbox",
            nonce: nonce,
            photo_id: totalPosts[idPhoto],
          },
          success: function (res) {
            if (res.success) {
              console.log("Réponse AJAX réussie : ", res.data);
              const data = res.data;
              const imageHtml = `<img src="${data.thumbnail}" alt="${data.title}" class="lightbox__image">`;
              const titleHtml = `<h4 class="photo-title">${data.title}</h4>`;
              const categoryHtml = `<p class="photo-category">${data.categories}</p>`;

              $("#lightbox__container_content").html(imageHtml + titleHtml + categoryHtml).removeClass("hidden");
              $(".lightbox__loader").addClass("hidden");

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

      $.close = function () {
        $(".lightbox").addClass("hidden");
      };
    });
  })(jQuery);
});
