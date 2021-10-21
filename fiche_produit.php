<?php 
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

// Réservation confirmée
if(isset($_GET['action']) && $_GET['action'] == 'reserver' && !empty($_GET['id_produit'])) {
    $maj_produit = $pdo->prepare("UPDATE produit SET etat='réservé' WHERE id_produit = :id_produit");
    $maj_produit->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
    $maj_produit->execute();

    $maj_commande = $pdo->prepare("INSERT INTO commande (id_membre, id_produit, date_enregistrement) VALUES (:id_membre, :id_produit, NOW() )");
    $maj_commande->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
    $maj_commande->bindParam(':id_membre', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
    $maj_commande->execute();

    header('location:index.php'); // Redirection vers accueil après la réservation
}

// Récupération de l'id_produit pour afficher les détails
if( isset($_GET['id_produit']) ) {
    $recup_produit = $pdo->prepare("SELECT * FROM produit INNER JOIN salle ON produit.id_salle = salle.id_salle WHERE id_produit = :id_produit");
    $recup_produit->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
    $recup_produit->execute();

    // on verifie si on a recupéré un produit (c-ad qu'on a une ligne)
    if($recup_produit->rowCount() < 1) {
        //on redirige vers index
        header('location:index.php');//si le user affiche la page mais supp la fin de l'url 
    }

} else {
    header('location:index.php'); // si id_produit n'existe pas () 
}

// Fetch pour récupérer les détails du produit
$infos_produit = $recup_produit->fetch(PDO::FETCH_ASSOC);


// Récupération des avis de la salle pour affichage des avis en bas de page
$liste_avis = $pdo->query(" SELECT avis.id_membre, avis.id_salle, avis.commentaire, membre.pseudo, avis.date_enregistrement, avis.note FROM avis INNER JOIN membre ON avis.id_membre = membre.id_membre WHERE avis.id_salle = $infos_produit[id_salle] ORDER BY avis.date_enregistrement DESC");


//------------------------------------------------------------------
// Les affichages dans la page commencent depuis la ligne suivante :
//------------------------------------------------------------------
include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

    <main class="container-fluid px-2">

        <!-- Affichage du bandeau -->
        <div class="row">
            <div class="bg-light mt-4 pt-3">
                <div class="d-flex flex-wrap align-items-center">
                    <div class="me-auto">
                        <p class="fs-3"><?php echo ucfirst($infos_produit['categorie']) . ' ' . $infos_produit['titre'] ?></p>
                    </div>
                    <div>
                        <p class="dates_produit"><?php echo 'du ' . date("d/m", strtotime($infos_produit['date_arrivee'])) . ' au ' . date("d/m/y", strtotime($infos_produit['date_depart'])) . ' - Tarif ' . $infos_produit['prix'] . '€' ?></p>
                    </div>
                    <div class="ms-auto">
                        <?php if(user_is_connected()) {
                            // Condition pour cacher le bouton si accès depuis la page gestion_produit et produit deja réservé ou date expirée
                            if($infos_produit['etat'] == 'libre') {
                                if($infos_produit['date_arrivee'] < date('Y-m-d H:i:s')){
                                    echo '<strong>Date expirée</strong>';
                                } else {
                                    echo '<a href="?action=reserver&id_produit=' . $infos_produit['id_produit'] . '" title="reserver" class="btn btn-warning confirm_resa">Réserver</a>';
                                }
                            } else {
                                    echo '<strong>Déjà réservé</strong>';
                            }
                            

                        } else {
                            echo '<button type="button" class="btn btn-warning mb-3"><strong><a href="connexion.php" class="text-decoration-none text-dark">Se connecter</a></strong></button>';
                        } ?>
                    </div>
                </div>
            </div>
        </div>

        <?php  
        // affichage du lien deposer avis uniquement si user connecté
        if(user_is_connected()) {
            echo '<div class="row">
                    <div class="mt-2 ps-3">
                        <a href="avis.php?id_produit=' . $infos_produit['id_produit'] . '&id_salle=' . $infos_produit['id_salle'] . '&titre=' . $infos_produit['titre'] . '">Déposer un avis</a>
                    </div>
                </div>';
        }
        

        // Affichage des détails du produit
        echo'
        
        <div class="row">
            <div class="col-md-8 text-center my-3">
                <img src="' . URL . 'assets/images_salles/' . $infos_produit['photo'] . '" class="img-fluid" alt="Une image salle : ' . $infos_produit['categorie'] . '">
            </div>
            <div class="col-lg-4">
                <h4 class="fw-bold mt-4">Description</h4>
                <p>' . $infos_produit['description'] . '</p>
                <h4 class="fw-bold">Localisation</h4>
                <iframe src="' . $infos_produit['plan'] . '" class="w-100" height="240" loading="lazy"></iframe>

            </div>
        </div>

        <div class="row ps-2">
            <h4 class="fw-bold">Informations complémentaires</h4>
            <div class="col-md-4">
                <p class="my-0">Arrivée : ' . date("d/m/Y", strtotime($infos_produit['date_arrivee'])) . '- 9h00</p>
                <p>Départ : ' . date("d/m/Y", strtotime($infos_produit['date_depart'])) . '- 19h00</p>
            </div>
            <div class="col-md-3">
                <p class="my-0">Capacité : ' . $infos_produit['capacite'] . ' pers.</p>
                <p>Catégorie : ' . $infos_produit['categorie'] . '</p>
            </div>
            <div class="col-md-5">
                <p class="my-0">Adresse : ' . $infos_produit['adresse'] . ' ' . $infos_produit['cp'] . ' ' . $infos_produit['ville'] .'</p>
                <p>Tarif : ' . $infos_produit['prix'] . ' euros TTC</p>
            </div>
        </div>

        
        <div class="row mt-2 ps-2">
            <h4 class="avis fw-bold">Avis</h4>';
            
            while($avis = $liste_avis->fetch(PDO::FETCH_ASSOC)){               
                echo '<p class="comments"><strong>' . $avis['pseudo'] . '</strong> a dit le ' . date("d/m/Y", strtotime($avis['date_enregistrement'])) . ' : ' . $avis['commentaire'] . ' (note ' .  $avis['note'] . ' / 5)</p>';
            };
        '</div>'
            
        ?>

    </main>

    <br>

<?php 
include 'inc/footer.inc.php';

