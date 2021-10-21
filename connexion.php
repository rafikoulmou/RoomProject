<?php 
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

// Deconnexion utilisateur
if( isset($_GET['action']) && ($_GET['action']) == 'deconnexion' ) {
    session_destroy();
}

//Restriction d'acces si l'utilisateur est connecté, on le renvoie vers profil.php
if(user_is_connected()) {
    header('location:profil.php');
}

// Si le formulaire a été validé
if( isset($_POST['pseudo']) && isset($_POST['mdp']) ) {
    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);

    // on déclenche une requete de récupération basée sur le pseudo
    $connexion = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $connexion->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $connexion->execute();

    if($connexion->rowCount() > 0) {
        // pseudo ok
        // on vérifie ensuite le mdp
        $infos = $connexion->fetch(PDO::FETCH_ASSOC);
        if(password_verify($mdp, $infos['mdp'])) {
            // mdp ok
            // On place dans la $_SESSION (ouverte dans init.inc.php) les infos utilisateurs
            $_SESSION['membre'] = array();
            $_SESSION['membre']['id_membre'] = $infos['id_membre'];
            $_SESSION['membre']['pseudo'] = $infos['pseudo'];
            $_SESSION['membre']['civilite'] = $infos['civilite'];
            $_SESSION['membre']['nom'] = $infos['nom'];
            $_SESSION['membre']['prenom'] = $infos['prenom'];
            $_SESSION['membre']['email'] = $infos['email'];
            $_SESSION['membre']['statut'] = $infos['statut'];
            $_SESSION['membre']['date_enregistrement'] = $infos['date_enregistrement'];           

            // maintenant que l'utilisateur est connecté, on peut diriger vers profil
            header('location:profil.php');

        } else {
            // mdp nok
            $msg .= '<div class="alert alert-danger mt-3">Attention,<br>Erreur sur le pseudo et/ou le mot de passe</div>';
        }

    } else {    
        // pseudo nok
        $msg .= '<div class="alert alert-danger mt-3">Attention,<br>Erreur sur le pseudo et/ou le mot de passe</div>';
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
            <h3 class="p-2">Connexion</h3>
        </div>

        <?php echo $msg . '<br>'; // variable destinée à afficher des messages utilisateur  ?>

        <!-- Affichage du formulaire -->
        <div class="row">
            <div class="col-lg-4 col-md-6 mx-auto py-4">
                <form method="post" action="" class="border p-3">
                    <div class="mb-3">
                        <label for="pseudo" class="form-label">Pseudo</label>
                        <input type="text" class="form-control" id="pseudo" name="pseudo" value="">
                    </div>
                    <div class="mb-3">
                        <label for="mdp" class="form-label">Mot de passe</label>
                        <input type="text" class="form-control" id="mdp" name="mdp">
                    </div>

                    <input type="submit" class="btn btn-outline-dark w-100" id="connexion" name="connexion" value="Connexion">
                </form>
            </div>
            
        </div>
        
        <br>

        <div class="row">
            <div class="col-auto mx-auto">
                <p><a href="<?php echo URL; ?>inscription.php">Pas encore inscrit ?</a></p>
            </div>
        </div>
    </main>



<br><br><br><br>


<?php 
include 'inc/footer.inc.php';