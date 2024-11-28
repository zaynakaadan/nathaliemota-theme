jQuery(document).ready(function($) {
  console.log("Script principal lancé !");

      // Rechercher les éléments
      const contactBtn = $(".contact");
      const popupOverlay = $(".popup-overlay");      

      //console.log("Nombre de boutons contact trouvés : ", contactBtn.length);
      //console.log("Popup overlay disponible : ", popupOverlay.length > 0);

      // Vérification que les éléments existent
      if (contactBtn.length > 0 && popupOverlay.length > 0) {
          // Ouvrir la modale au clic sur les boutons de contact 
          contactBtn.on("click", function(event) {
              event.preventDefault(); // Empêche le comportement par défaut du lien
              console.log("Bouton contact cliqué !");
              // Afficher la popup
              popupOverlay.removeClass("hidden");       
       });
          // Fermeture de la popup contact au clic sur la zone grise
          popupOverlay.on("click", function(e) {
              if ($(e.target).is(popupOverlay)) { // Vérifie si c'est bien la zone grise
                  console.log("Zone grise cliquée !");
                  popupOverlay.addClass("hidden");
              }
          });
      } else {
          console.error("Les éléments .contact ou .popup-overlay ne sont pas disponibles !");
      }

    (function ($) {
        $(document).ready(function () {
          // Gestion de la fermeture et de l'ouverture du menu
          // dans une modale pour la version mobile
          $(".btn-modal").click(function (e) {
            $(".modal__content").toggleClass("animate-modal");
            $(".modal__content").toggleClass("open");
            $(".btn-modal").toggleClass("close");
          });
          // Fermeture du menu lorsqu'on clique sur un lien
          $("a").click(function () {
            
          if ($(".modal__content").hasClass("open")) {
            setTimeout(function() {
              $(".modal__content").removeClass("animate-modal");
              $(".modal__content").removeClass("open");
              $(".btn-modal").removeClass("close");
            }, 200); // 200ms pour correspondre à l'animation
          }
          });
        });
      })(jQuery);
});
