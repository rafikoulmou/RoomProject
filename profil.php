<?php 
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

// Restriction et redirection si l'utilisateur n'est pas connecté
if( !user_is_connected() ) {
    header('location:connexion.php');
}

// Récupération de la civilité pour déterminer sexe (homme/femme)
if($_SESSION['membre']['civilite'] == 'm') {
    $sexe = 'homme';
} else {
   $sexe = 'femme';
}

// Récupération du statut
if($_SESSION['membre']['statut'] == 2) {
    $statut = 'vous êtes administrateur';
} else {
    $statut = 'vous êtes membre';
}

$membre= $_SESSION['membre']['id_membre'];

// Récupération en bdd des données des tables membre, produit, salle
$liste_commandes = $pdo->query("SELECT * FROM commande INNER JOIN membre ON commande.id_membre = membre.id_membre INNER JOIN produit ON commande.id_produit = produit.id_produit INNER JOIN salle ON salle.id_salle = produit.id_salle WHERE commande.id_membre = $membre ");

//------------------------------------------------------------------
// Les affichages dans la page commencent depuis la ligne suivante :
//------------------------------------------------------------------
include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>


    <main class="container-fluid px-4">
        <div class="bg-light mt-4 p-2">
            <h3 class="pt-2"><i class="far fa-address-card"></i> Profil</h3>
        </div>

        <?php echo $msg . '<br>'; // variable destinée à afficher des messages utilisateur  ?>
        

        <div class="row">
            <div class="col-sm-6 mt-2">

                <ul class="list-group">
                    <li class="list-group-item bg-dark text-white" aria-current="true">Vos informations</li>
                    
                    <li class="list-group-item d-flex justify-content-between"><span><b>N° : </b><?php echo $_SESSION['membre']['id_membre']; ?></span><i class="fas fa-user"></i></li>

                    <li class="list-group-item d-flex justify-content-between"><span><b>Pseudo : </b><?php echo $_SESSION['membre']['pseudo']; ?></span><i class="fas fa-ghost"></i></li>

                    <li class="list-group-item d-flex justify-content-between"><span><b>Sexe : </b><?php echo $sexe; ?></span><i class="fas fa-venus-mars"></i></li>

                    <li class="list-group-item d-flex justify-content-between"><span><b>Nom : </b><?php echo $_SESSION['membre']['nom']; ?></span><i class="fas fa-signature"></i></li>

                    <li class="list-group-item d-flex justify-content-between"><span><b>Prénom : </b><?php echo $_SESSION['membre']['prenom']; ?></span><i class="fas fa-signature"></i></li>

                    <li class="list-group-item d-flex justify-content-between"><span><b>Email : </b><?php echo $_SESSION['membre']['email']; ?></span><i class="far fa-envelope"></i></li>

                    <li class="list-group-item d-flex justify-content-between"><span><b>Statut : </b><?php echo $statut; ?></span><i class="fas fa-user-tag"></i></li>
                    
                </ul>
            </div>
            <div class="col-sm-6 mt-2">
                <img src="assets/img/profil.jpg" alt="une image de profil" class="w-100 img-thumbnail">
            </div>
        </div>

        <div class="row">
            <h3 class="mt-4">Historique de vos commandes</h3>
            <div class="col-12 mt-1">
                <table class="table table-bordered text-center">
                    <tr class="bg-dark text-white">
                        <th>Id commande</th>
                        <th>Id membre</th>
                        <th>Id produit</th>
                        <th>Prix</th>
                        <th>Date enregistrement</th>
                    </tr>

                    <?php 
                        while($ligne = $liste_commandes->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
                            echo '<td>' . $ligne['id_commande'] . '</td>';
                            echo '<td>' . $ligne['id_membre'] . ' - ' . $ligne['email'] . '</td>';
                            echo '<td>' . $ligne['id_produit'] . ' - ' . $ligne['categorie'] . ' ' . $ligne['titre'] . '<br>' . date("d/m", strtotime($ligne['date_arrivee'])) . ' au ' . date("d/m/y", strtotime($ligne['date_depart'])) . '</td>';
                            echo '<td>' . $ligne['prix'] . '</td>';
                            echo '<td>' . $ligne['date_enregistrement'] . '</td>';
                            echo '</tr>';                           
                        }
    
                    ?>

                </table>
            </div>            
        </div>

    </main>







<br><br><br><br>

<?php 
include 'inc/footer.inc.php';