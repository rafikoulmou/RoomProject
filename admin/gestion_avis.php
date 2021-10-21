<?php 
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

// Redirection si l'utilisateur n'est pas admin (saisie de l'url dans la barre d'adresse)
if( !user_is_admin() ) {
    header('location: ../connexion.php');
}


// Suppression avis
if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_avis'])) {
    $suppression = $pdo->prepare("DELETE FROM avis WHERE id_avis = :id_avis");
    $suppression->bindParam(':id_avis', $_GET['id_avis'], PDO::PARAM_STR);
    $suppression->execute();
}

// Récupération en bdd des données des tables membre, produit, salle
$liste_avis = $pdo->query("SELECT avis.id_avis, membre.id_membre, membre.email, salle.id_salle, salle.categorie, salle.titre, avis.commentaire, avis.note, avis.date_enregistrement FROM avis INNER JOIN membre ON avis.id_membre = membre.id_membre INNER JOIN salle ON avis.id_salle = salle.id_salle");


//------------------------------------------------------------------
// Les affichages dans la page commencent depuis la ligne suivante :
//------------------------------------------------------------------
include '../inc/header.inc.php';
include '../inc/nav.inc.php';
?>


    <main class="container-fluid">
        <div class="bg-light mt-4 p-2">
            <h3 class="p-2">Gestion des avis</h3>
        </div>

        <?php echo $msg . '<br>'; // variable destinée à afficher des messages utilisateur  ?>

        <!-- Affichage de la liste des membres -->
        <div class="row">
            <div class="col-12 mt-5">
                <table class="table table-bordered text-center">
                    <tr class="bg-dark text-white">
                        <th>Id avis</th>
                        <th>Id membre</th>
                        <th>Id salle</th>
                        <th>Commentaire</th>
                        <th>Note</th>
                        <th>Date enregistrement</th>
                        <th>Suppr</th>
                    </tr>

                    <?php 
                        while($ligne = $liste_avis->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
                            echo '<td>' . $ligne['id_avis'] . '</td>';
                            echo '<td>' . $ligne['id_membre'] . ' - ' . $ligne['email'] . '</td>';
                            echo '<td>' . $ligne['id_salle'] . ' - ' . $ligne['categorie'] . ' ' . $ligne['titre'] . '</td>';
                            echo '<td>' . $ligne['commentaire'] . '</td>';
                            echo '<td>' . $ligne['note'] . '</td>';
                            echo '<td>' . $ligne['date_enregistrement'] . '</td>';
                            echo '<td class="text-center align-middle"><a href="?action=supprimer&id_avis=' . $ligne['id_avis'] . '" title="supprimer" class="btn btn-danger confirm_delete"><i class="far fa-trash-alt"></i></a></td>';
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