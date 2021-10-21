// Confirmation de la suppression des pages gestion salles, produits, membres
let listBouton = document.getElementsByClassName('confirm_delete');

if(listBouton.length) {
    for(let i = 0; i < listBouton.length; i++) {
        listBouton[i].addEventListener('click', function (e) {
            let choix = confirm('Etes-vous sûr de vouloir supprimer ?'); // choix = true si confirm / false si annul supp

            if(choix == false) {
                e.preventDefault();
            }
        });
    }
}

// Confirmation de la reservation fiche produit
let resaBouton = document.getElementsByClassName('confirm_resa');

if(resaBouton.length) {
    for(let i = 0; i < resaBouton.length; i++) {
        resaBouton[i].addEventListener('click', function (e) {
            let choix = confirm('Confirmez-vous votre réservation pour ce produit ?'); // choix = true si confirm / false si annul supp

            if(choix == false) {
                e.preventDefault();
            } else { window.alert('Votre réservation est bien enregistrée');}
        });
    }
}

