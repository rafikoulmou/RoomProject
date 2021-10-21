<?php 
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

// Redirection si l'utilisateur n'est pas admin (saisie de l'url dans la barre d'adresse)
if( !user_is_admin() ) {
    header('location: ../connexion.php');
}


// Suppression membre
if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_membre'])) {
    $suppression = $pdo->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
    $suppression->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);
    $suppression->execute();
}


// Enregistrement et modification membre
$id_membre = '';
$pseudo = '';
$mdp = '';
$civilite = '';
$nom = '';
$prenom = '';
$email = '';
$civilite = '';
$statut = '';


// Controle des données
if( isset($_POST['pseudo']) && isset($_POST['mdp']) && ($_POST['civilite']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['civilite']) && isset($_POST['statut']) ) {

    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);
    $civilite = trim($_POST['civilite']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $civilite = trim($_POST['civilite']);
    $statut = trim($_POST['statut']);
    if($statut == 'admin') {
        $statut = 2;
    } else { 
        $statut = 1;
    }

    
    // Pour la modif, recup de l'id
    if( !empty($_POST['id_membre']) ) {
        $id_membre = trim($_POST['id_membre']);
    }

    // déclaration du flag pour les erreurs
    $erreur = false;

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

    if($verif_pseudo->rowCount() > 0 && empty($id_membre)) { // si on recup non vide (il y a des lignes)-> pseudo existe deja (sinon requete renvoie vide)
        $msg .= '<div class="alert alert-danger mt-3">Attention,<br>le pseudo est deja pris !</div>';
        $erreur = true;
    }

    // Format du mail
    if(filter_var($email, FILTER_VALIDATE_EMAIL) == false ) {
        $msg .= '<div class="alert alert-danger mt-3">Attention,<br>le format de l\'email n\'est pas correct !</div>';
        $erreur = true;
    }

    // Rendre les champs obligatoires
    if (empty($pseudo) || empty($mdp) || empty($nom) || empty($prenom) || empty($email) || empty($civilite) || empty($statut) ) {
        $msg .= '<div class="alert alert-danger mt-3">Attention, tous les champs sont  obligatoires.</div>';
        // cas d'erreur
        $erreur = true;
    }    

    // Enregistrement en bdd
    if($erreur == false) {

         // si l'id_membre n'est pas vide -> modif

        if( !empty($id_membre) ) {
            $enregistrement = $pdo->prepare("UPDATE membre SET pseudo = :pseudo, nom = :nom, prenom = :prenom, email = :email, civilite = :civilite, statut = :statut WHERE id_membre = :id_membre");
            $enregistrement->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);

        } else {
            
            // cryptage du mdp
            $mdp = password_hash($mdp, PASSWORD_DEFAULT);
            $enregistrement = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :statut, NOW())");

            $enregistrement->bindParam(':mdp', $mdp, PDO:: PARAM_STR);
            
        }        

        $enregistrement->bindParam(':pseudo', $pseudo, PDO:: PARAM_STR);
        $enregistrement->bindParam(':nom', $nom, PDO:: PARAM_STR);
        $enregistrement->bindParam(':prenom', $prenom, PDO:: PARAM_STR);
        $enregistrement->bindParam(':email', $email, PDO:: PARAM_STR);
        $enregistrement->bindParam(':civilite', $civilite, PDO:: PARAM_STR);
        $enregistrement->bindParam(':statut', $statut, PDO:: PARAM_STR);
        
        $enregistrement->execute();

    }
}
// fin des isset


// Récupération des infos du membre à modifier
if(isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_membre'])) {
    $recup_infos = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
    $recup_infos->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);
    $recup_infos->execute();

    $infos_membre = $recup_infos->fetch(PDO::FETCH_ASSOC);

    $id_membre = $infos_membre['id_membre'];
    $pseudo = $infos_membre['pseudo'];
    $mdp = $infos_membre['mdp'];
    $nom = $infos_membre['nom'];
    $prenom = $infos_membre['prenom'];
    $email = $infos_membre['email'];
    $civilite = $infos_membre['civilite'];
    $statut = $infos_membre['statut'];
    $date_enregistrement = $infos_membre['date_enregistrement'];
}


// Récupération en bdd des données de la table membre pour affichage de la liste des membres 
$liste_membres = $pdo->query("SELECT * FROM membre ORDER BY id_membre");


//------------------------------------------------------------------
// Les affichages dans la page commencent depuis la ligne suivante :
//------------------------------------------------------------------

include '../inc/header.inc.php';
include '../inc/nav.inc.php';
?>


    <main class="container-fluid px-4">
        <div class="bg-light mt-4 p-2">
            <h3 class="p-2">Gestion des membres</h3>
        </div>

        <?php echo $msg . '<br>'; // variable destinée à afficher des messages utilisateur  ?>

        <!-- Affichage du formulaire -->
        <div class="row">
            <div class="col-12 mt-2">
                <form method="post" action="" class="border p-3">

                    <input type="hidden" name="id_membre" value="<?php echo $id_membre ?>">

                    <div class="row">

                        <div class="col-lg-6 mx-auto">
                            <div class="mb-3">
                                <label for="pseudo" class="form-label">Pseudo</label>
                                <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo $pseudo; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="mdp" class="form-label">Mot de passe</label>
                                <input type="text" class="form-control" id="mdp" name="mdp" value="<?php echo $mdp; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="statut" class="form-label">Statut</label>
                                <select class="form-control w-25" id="statut" name="statut">
                                    <option value="admin">Admin</option>
                                    <option value="membre" <?php if($statut == '1') { echo 'selected'; } ?> >Membre</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 mx-auto">
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
                                <input type="text" class="form-control mb-4" id="prenom" name="prenom" value="<?php echo $prenom; ?>">
                            </div>
                            <div class="mb-3 text-center">
                                <input type="submit" class="btn btn-outline-dark w-50 mt-4" id="enregistrement" name="enregistrement" value="Enregistrement">                           
                                <button type="button" class="btn btn-outline-warning mt-4 ms-2"><a href=" <?php echo URL; ?>admin/gestion_membres.php" class="text-decoration-none text-dark">Vider les champs</a></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <br>

        <!-- Affichage de la liste des membres -->
        <div class="row bg-light">
            <div class="table-responsive" id="affichage_vertical">
                <table class="table table-bordered">
                    <thead class="bg-dark text-light">
                    <tr>
                        <th>Id membre</th>
                        <th>Pseudo</th>
                        <th>Civilité</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th>Créé</th>
                        <th>Modif</th>
                        <th>Suppr</th>
                    </tr>
                    </thead>
                    <?php 
                        while($ligne = $liste_membres->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tbody>';
                            echo '<tr class="align-middle">';
                            echo '<td data-title="Id membre">' . $ligne['id_membre'] . '</td>';
                            echo '<td data-title="Pseudo">' . $ligne['pseudo'] . '</td>';
                            echo '<td data-title="Civilité">' . civilite($ligne['civilite']) . '</td>';
                            echo '<td data-title="Nom">' . $ligne['nom'] . '</td>';
                            echo '<td data-title="Prénom">' . $ligne['prenom'] . '</td>';
                            echo '<td data-title="Email">' . $ligne['email'] . '</td>';
                            echo '<td data-title="Statut">' . statut($ligne['statut']) . '</td>';
                            echo '<td data-title="Créé">' . $ligne['date_enregistrement'] . '</td>';
                            echo '<td><a href="?action=modifier&id_membre=' . $ligne['id_membre'] . '"  title="modifier" class="btn btn-warning text-white responsive_hidden"><i class="fas fa-edit"></i></a><a href="?action=modifier&id_membre=' . $ligne['id_membre'] . '"  title="modifier" class="btn btn-warning text-white responsive_display"><i class="fas fa-edit"></i></a><a href="?action=supprimer&id_membre=' . $ligne['id_membre'] . '" title="supprimer" class="btn btn-danger confirm_delete responsive_display" ><i class="far fa-trash-alt"></i></a></td>';
                            echo '<td><a href="?action=supprimer&id_membre=' . $ligne['id_membre'] . '" title="supprimer" class="btn btn-danger confirm_delete responsive_hidden"><i class="far fa-trash-alt"></i></a></td>';
                            echo '</tr>'; 
                            echo '</tbody>';                          


                        }

    
                    ?>

                </table>

            </div>
        </div>        
    </main>







<br><br><br>

<?php 
include '../inc/footer.inc.php';