<?php 
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

//Restriction d'acces si l'utilisateur est connecté, on le renvoie vers profil.php
if(user_is_connected()) {
    header('location:profil.php');
}

// Vérification de l'existence des infos du formulaire
// Enregistrement de l'inscription

// Initialisation (à vide) des variables pour conservation des saisies du formulaire
$pseudo = '';
$mdp = '';
$civilite = '';
$nom = '';
$prenom = '';
$email = '';

if( isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['civilite']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) ) {

    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);
    $civilite = trim($_POST['civilite']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);


    // Controles
    $erreur = false; // flag pour les erreurs


    // Taille du pseudo
    if( iconv_strlen($pseudo) < 4 || iconv_strlen($pseudo) > 14 ) {
        $msg .= '<div class="alert alert-danger mt-3">Attention,<br>le pseudo doit comporter entre 4 et 14 caractères !</div>';
        $erreur = true;
    }

    // Format du pseudo
    $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $pseudo);

    if(!$verif_caractere) { 
        $msg .= '<div class="alert alert-danger mt-3">Attention,<br>le pseudo ne peut contenir que : A-Z 0-9 _ . - !</div>';
        $erreur = true;
    }

    // Unicité du pseudo
    $verif_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $verif_pseudo->bindParam('pseudo', $pseudo, PDO::PARAM_STR);
    $verif_pseudo->execute();

    if($verif_pseudo->rowCount() > 0 ) { // si on recup non vide (il y a des lignes)-> pseudo existe deja (sinon requete renvoie vide)
        $msg .= '<div class="alert alert-danger mt-3">Attention,<br>le pseudo est deja pris !</div>';
        $erreur = true;
    }

    // Format du mail
    if(filter_var($email, FILTER_VALIDATE_EMAIL) == false ) {
        $msg .= '<div class="alert alert-danger mt-3">Attention,<br>le format de l\'email n\'est pas correct !</div>';
        $erreur = true;
    }

    // Saisie de tous les champs
    if(empty($pseudo) || empty($mdp) || empty($nom) || empty($prenom) || empty($prenom) || empty($email)) {
        $msg .= '<div class="alert alert-danger mt-3">Attention,<br>tous les champs sont obligatoires !</div>';
        $erreur = true;
    }


    // Si pas d'eerreur, on lance l'enregistrement en bdd
    if($erreur == false) {

        // cryptage du mdp
        $mdp = password_hash($mdp, PASSWORD_DEFAULT);

        // Pour le statut :
        // 1 => membre
        // 2 => admin
        $inscription = $pdo->prepare("INSERT INTO membre (pseudo, mdp, civilite, nom, prenom, email, date_enregistrement, statut) VALUES (:pseudo, :mdp, :civilite, :nom, :prenom, :email, NOW(), 1)");
        $inscription->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $inscription->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $inscription->bindParam(':civilite', $civilite, PDO::PARAM_STR);
        $inscription->bindParam(':nom', $nom, PDO::PARAM_STR);
        $inscription->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $inscription->bindParam(':email', $email, PDO::PARAM_STR);
        $inscription->execute();

        // on redirige vers connexion
        header('location:connexion.php');

        // Envoi du mail de confirmation d'inscription
        $sujet = 'Bienvenue chez Room, ' . $prenom . ' !';
        $msg_bienvenue = welcometext;
        $expediteur = 'From: room@info.fr';
        mail($email, $sujet, $msg_bienvenue, $expediteur);

    }


}


//------------------------------------------------------------------
// Les affichages dans la page commencent depuis la ligne suivante :
//------------------------------------------------------------------
include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>


    <main class="container-fluid px-4">
        <div class="bg-light mt-4 p-2">
            <h3 class="p-2">Inscription</h3>
        </div>

        <?php echo $msg . '<br>'; // variable destinée à afficher des messages utilisateur  ?>

        <!-- Affichage du formulaire -->
        <div class="row">
            <div class="col-12 mt-2">
                <div class="col-lg-6 col-md-8 mx-auto py-4">
                    <form method="post" action="" class="border p-3">
                        <div class="mb-3">
                            <label for="pseudo" class="form-label">Pseudo</label>
                            <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo $pseudo; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="mdp" class="form-label">Mot de passe</label>
                            <input type="text" class="form-control" id="mdp" name="mdp">
                        </div>
                        <div class="mb-3">
                            <label for="civilite" class="form-label">Civilité</label>
                            <select class="form-control" id="civilite" name="civilite">
                                <option value="m">Monsieur</option>
                                <option value="f" <?php if($civilite == 'f') { echo 'selected'; } ?> >Madame</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $nom; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $prenom; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                        </div>
                        <div class="py-2 text-center">
                            <input type="submit" class="btn btn-outline-dark w-50" id="inscription" name="inscription" value="Inscription">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>








<?php 
include 'inc/footer.inc.php';