document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('contactModal');
    var btn = document.getElementById('contactBtn');
    var span = document.getElementsByClassName('close')[0];

    // Ouvrir la modale quand l'utilisateur clique sur le bouton
    btn.onclick = function() {
        modal.style.display = 'block';
    }

    // Fermer la modale quand l'utilisateur clique sur la croix
    span.onclick = function() {
        modal.style.display = 'none';
    }

    // Fermer la modale quand l'utilisateur clique à l'extérieur de la modale
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
});
