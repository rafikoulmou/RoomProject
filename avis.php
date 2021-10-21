<?php 
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

// Restriction et redirection si l'utilisateur n'est pas connecté
if( !user_is_connected() ) {
    header('location:connexion.php');
}



// Controle sur disponibilité des données
if( isset($_POST['commentaire']) && isset($_POST['note']) && !empty($_GET['id_produit']) && !empty($_GET['id_salle']) && !empty($_GET['titre'])) {

    $commentaire = $_POST['commentaire'];
    $note = $_POST['note'];

    $enregistrement_avis = $pdo->prepare("INSERT INTO avis (id_membre, id_salle, commentaire, note, date_enregistrement) VALUES (:id_membre, :id_salle, :commentaire, :note, NOW() )");
    $enregistrement_avis->bindParam(':id_membre', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
    $enregistrement_avis->bindParam(':id_salle', $_GET['id_salle'], PDO::PARAM_STR);
    $enregistrement_avis->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
    $enregistrement_avis->bindParam(':note', $note, PDO::PARAM_STR);
    $enregistrement_avis->execute();
    
    
    if (empty($_POST['commentaire'])) {
        $msg .= '<div class="alert alert-warning mt-3">Attention, merci de saisir un commentaire !</div>';
    
    } else {
        $msg .= '<div class="alert alert-success mt-3">Merci pour votre commentaire ! </div>';
    }
}






//------------------------------------------------------------------
// Les affichages dans la page commencent depuis la ligne suivante :
//------------------------------------------------------------------
include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>


    <main class="container-fluid">
        <div class="bg-light mt-4 p-2">
            <h3 class="p-2">Avis</h3>
        </div>

        <?php echo $msg . '<br>'; // variable destinée à afficher des messages utilisateur  ?>

        <div class="row">
            <div class="col-sm-12 col-md-8 mx-auto mt-4">
                <form method="post" action="" class="border border-1 p-3">
                    <div class="row">
                        <div class="col-sm-8 mx-auto">
                                <label for="commentaire" class="form-label">Votre commentaire</label>
                                <textarea class="form-control" id="commentaire" name="commentaire" rows="8" value=""></textarea>
                        </div>

                        <div class="col-auto me-auto">
                            <label for="note" class="form-label">Votre note</label>
                            <select class="form-control" name="note" id="note">
                                <?php
                                    // boucle pour afficher les notes de 0 à 5
                                    for($i = 5; $i >= 0; $i--) {
                                        echo '<option>' . $i . '</option>';
                                    }
                                ?>
                            </select>                            
                        </div>
                    </div>

                    <div class="col-auto mx-auto mt-4 text-center">
                            <input type="submit" class="btn btn-outline-dark w-25" id="envoyer" name="envoyer">
                    </div>

                </form>
            </div>
        </div>
    </main>







<br><br><br><br><br><br>

<?php 
include 'inc/footer.inc.php';