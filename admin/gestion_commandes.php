<?php 
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

// Redirection si l'utilisateur n'est pas admin (saisie de l'url dans la barre d'adresse)
if( !user_is_admin() ) {
    header('location: ../connexion.php');
}

// Suppression commande
if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_commande'])) {
    $suppression = $pdo->prepare("DELETE FROM commande WHERE id_commande = :id_commande");
    $suppression->bindParam(':id_commande', $_GET['id_commande'], PDO::PARAM_STR);
    $suppression->execute();
}


// Récupération en bdd des données des tables membre, produit, salle
$liste_commandes = $pdo->query("SELECT commande.id_commande, membre.id_membre, membre.email, produit.id_produit, produit.date_arrivee, produit.date_depart, salle.categorie, salle.titre, produit.prix, commande.date_enregistrement FROM commande INNER JOIN membre ON commande.id_membre = membre.id_membre INNER JOIN produit ON commande.id_produit = produit.id_produit INNER JOIN salle ON salle.id_salle = produit.id_salle");


//------------------------------------------------------------------
// Les affichages dans la page commencent depuis la ligne suivante :
//------------------------------------------------------------------

include '../inc/header.inc.php';
include '../inc/nav.inc.php';
?>


    <main class="container-fluid">
        <div class="bg-light mt-4 p-2">
            <h3 class="p-2">Gestion des commandes</h3>
        </div>

        <?php echo $msg . '<br>'; // variable destinée à afficher des messages utilisateur  ?>

        <!-- Affichage de la liste des membres -->
        <div class="row">
            <div class="col-12 mt-5">
                <table class="table table-bordered text-center">
                    <tr class="bg-dark text-white">
                        <th>Id commande</th>
                        <th>Id membre</th>
                        <th>Id produit</th>
                        <th>Prix</th>
                        <th>Date enregistrement</th>
                        <th>Suppr</th>
                    </tr>

                    <?php 
                        while($ligne = $liste_commandes->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr class="text-center align-middle">';
                            echo '<td>' . $ligne['id_commande'] . '</td>';
                            echo '<td>' . $ligne['id_membre'] . ' - ' . $ligne['email'] . '</td>';
                            echo '<td>' . $ligne['id_produit'] . ' - ' . $ligne['categorie'] . ' ' . $ligne['titre'] . '<br>' . date("d/m", strtotime($ligne['date_arrivee'])) . ' au ' . date("d/m/y", strtotime($ligne['date_depart'])) . '</td>';
                            echo '<td>' . $ligne['prix'] . '</td>';
                            echo '<td>' . $ligne['date_enregistrement'] . '</td>';
                            echo '<td class="text-center align-middle"><a href="?action=supprimer&id_commande=' . $ligne['id_commande'] . '" title="supprimer" class="btn btn-danger confirm_delete"><i class="far fa-trash-alt"></i></a></td>';
                            echo '</tr>';                           
                        }
    
                    ?>

                </table>
            </div>
        </div>
    </main>







<br><br><br>

<?php 
include '../inc/footer.inc.php';