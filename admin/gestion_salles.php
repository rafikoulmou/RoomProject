<?php 
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

// Redirection si l'admin n'est pas connecté (saisie de l'url dans la barre d'adresse)
if( !user_is_admin() ) {
    header('location: ../connexion.php');
    exit();
}


// Suppression salle
if( isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_salle'])) {
    $suppression = $pdo->prepare("DELETE FROM salle WHERE id_salle = :id_salle");
    $suppression->bindParam(':id_salle', $_GET['id_salle'], PDO::PARAM_STR);
    $suppression->execute();
}


// Enregistrement et modification salle

$id_salle = ''; // utilisée pour la modif d'une salle
$ancienne_photo = ''; // utilisée pour la modif d'une salle
$titre = '';
$description = '';
$capacite = '';
$categorie = '';
$pays = '';
$ville = '';
$adresse = '';
$cp = '';

if( isset($_POST['titre']) && isset($_POST['description']) && isset($_POST['capacite']) && isset($_POST['categorie']) && isset($_POST['pays']) && isset($_POST['ville']) && isset($_POST['adresse']) && isset($_POST['cp']) ) {

    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $capacite = trim($_POST['capacite']);
    $categorie = trim($_POST['categorie']);
    $pays = trim($_POST['pays']);
    $ville = trim($_POST['ville']);
    $adresse = trim($_POST['adresse']);
    $cp = trim($_POST['cp']);

    // Pour la modif, recup de l'id et de la photo
    if( !empty($_POST['id_salle']) ) {
        $id_salle = trim($_POST['id_salle']);
    }
    if( !empty($_POST['ancienne_photo']) ) {
        $photo = trim($_POST['ancienne_photo']);
    }    

    // flag pour les erreurs
    $erreur = false;

    // Saisie obligatoire de tous les champs (sauf photo)
    if(empty($titre) || empty($description) || empty($adresse) || empty($cp)) {
        $msg .= '<div class="alert alert-danger mt-3">Attention,<br>tous les champs sont obligatoires !</div>';
        $erreur = true;
    }

    // Controle sur la dispo du titre de la salle (unique en BDD)
    $verif_titre = $pdo->prepare("SELECT * FROM salle WHERE titre = :titre");
    $verif_titre->bindParam(':titre', $titre, PDO::PARAM_STR);
    $verif_titre->execute();

    // verif si titre est dispo et si cas d'une modif (si id_salle existe-> modif-> condition du if non remplie).
    if($verif_titre->rowCount() > 0 && empty($id_salle) ) { 
        $msg .= '<div class="alert alert-danger mt-3">Attention, le titre est indisponible.</div>';
        // cas d'erreur
        $erreur = true;
    }    

    // Controle sur l'image
    if ( !empty($_FILES['photo']['name']) ) {
        // on renomme pour éviter l'ajout d'une photo dont le nom existe déjà
        $photo = $titre . '-' . $_FILES['photo']['name'];

        // tableau avec formats autorisés
        $tab_extension = array('jpg', 'jpeg', 'png', 'gif', 'webp');

        // récupération du format de la photo
        $extension = strtolower(substr(strrchr($photo, '.'), 1));

        // vérif si format autorisé
        if( in_array($extension, $tab_extension) ) {
            // format ok
            // on retravaille le nom pour enlever les caracteres  speciaux et les espaces
            $photo = preg_replace('/[^A-Za-z0-9.\-]/', '', $photo);

            // s'il ny a pas eu d'erreur, on copie l'image depuis le form vers un dossier
            if($erreur == false) {
                // copy(emplacement_de_base, emplacement_cible);
                // l'image est conservée à la validation du form dans l'indice de $_FILES['photo']['tmp_name']
                copy($_FILES['photo']['tmp_name'], ROOT_PATH . PROJECT_PATH . 'assets/images_salles/' . $photo);
            }            
        } else {
            // format invalide
            $msg .= '<div class="alert alert-danger mt-3">Attention, le format de l\'image est invalide. Les formats autorisés sont jpg/jpeg/png/gif/webp.</div>';
            $erreur = true;
        }

    }

    // On enregistre en bdd
    if($erreur == false) {

        // si l'id_salle n'est pas vide, on est une modif
        if( !empty($id_salle) ) {
            $enregistrement = $pdo->prepare("UPDATE salle SET titre = :titre, description = :description, photo = :photo, capacite = :capacite, categorie = :categorie, pays = :pays, ville = :ville, adresse = :adresse, cp = :cp WHERE id_salle = :id_salle");
            $enregistrement->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);
        } else {
            $enregistrement = $pdo->prepare("INSERT INTO salle (titre, description, photo, capacite, categorie, pays, ville, adresse, cp) VALUES (:titre, :description, :photo, :capacite, :categorie, :pays, :ville, :adresse, :cp)");
        }        
        
        $enregistrement->bindParam(':titre', $titre, PDO::PARAM_STR);
        $enregistrement->bindParam(':description', $description, PDO::PARAM_STR);
        $enregistrement->bindParam(':photo', $photo, PDO::PARAM_STR);
        $enregistrement->bindParam(':capacite', $capacite, PDO::PARAM_STR);
        $enregistrement->bindParam(':categorie', $categorie, PDO::PARAM_STR);
        $enregistrement->bindParam(':pays', $pays, PDO::PARAM_STR);
        $enregistrement->bindParam(':ville', $ville, PDO::PARAM_STR);
        $enregistrement->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $enregistrement->bindParam(':cp', $cp, PDO::PARAM_STR);
        $enregistrement->execute();
    }


} // fin des isset


// RECUPERATION DES INFOS DE LA SALLE A MODIFIER

if( isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_salle']) ) {
    // pour la modif on lance une requete en bdd et on affecte les infos dans le variables presentes dans les value de nos champs de form
    $recup_infos = $pdo->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
    $recup_infos->bindParam(':id_salle', $_GET['id_salle'], PDO::PARAM_STR);
    $recup_infos->execute();

    $infos_salle = $recup_infos->fetch(PDO::FETCH_ASSOC);

    $id_salle = $infos_salle['id_salle'];
    $titre = $infos_salle['titre'];
    $description = $infos_salle['description'];
    $ancienne_photo = $infos_salle['photo'];
    $capacite = $infos_salle['capacite'];
    $categorie = $infos_salle['categorie'];
    $pays = $infos_salle['pays'];
    $ville = $infos_salle['ville'];
    $adresse = $infos_salle['adresse'];
    $cp = $infos_salle['cp'];
}


$liste_salles = $pdo->query("SELECT * FROM salle ORDER BY id_salle");
// Récupération en bdd des données de la table salle
if( isset($_GET['tri'])){
$liste_salles = $pdo->prepare("SELECT * FROM salle ORDER BY $_GET[tri]");

$liste_salles->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);
$liste_salles->bindParam(':titre', $titre, PDO::PARAM_STR);
$liste_salles->bindParam(':description', $description, PDO::PARAM_STR);
$liste_salles->bindParam(':photo', $photo, PDO::PARAM_STR);
$liste_salles->bindParam(':capacite', $capacite, PDO::PARAM_STR);
$liste_salles->bindParam(':categorie', $categorie, PDO::PARAM_STR);
$liste_salles->bindParam(':pays', $pays, PDO::PARAM_STR);
$liste_salles->bindParam(':ville', $ville, PDO::PARAM_STR);
$liste_salles->bindParam(':adresse', $adresse, PDO::PARAM_STR);
$liste_salles->bindParam(':cp', $cp, PDO::PARAM_STR);
$liste_salles->execute();


}
//------------------------------------------------------------------
// Les affichages dans la page commencent depuis la ligne suivante :
//------------------------------------------------------------------
include '../inc/header.inc.php';
include '../inc/nav.inc.php';
?>


    <main class="container-fluid px-4">
        <div class="bg-light mt-4 p-2">
            <h3 class="p-2">Gestion des salles</h3>
        </div>

        <?php echo $msg . '<br>'; // variable destinée à afficher des messages utilisateur ?>

        <!-- Affichage du formulaire -->
        <h4 class="px-3">Ajout/Modification</h4>
        <div class="row">
            <div class="col-12 mt-1">
                <form method="post" action="" class="border p-3" enctype="multipart/form-data">

                    <input type="hidden" name="id_salle" value="<?php echo $id_salle ?>">
                    <input type="hidden" name="ancienne_photo" value="<?php echo $ancienne_photo ?>">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="titre" class="form-label">Titre</label>
                                <input type="text" class="form-control" id="titre" name="titre" value="<?php echo $titre; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" rows="3" name="description"><?php echo $description; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo (taille limitée à 2 Mo)</label>
                                <input type="file" class="form-control" id="photo" name="photo">
                            </div>
                            <div class="mb-3">
                                <label for="capacite" class="form-label">Capacité</label>
                                <select class="form-control" id="capacite" name="capacite">
                                    <option>1</option>
                                    <option <?php if($capacite == 2) { echo 'selected'; } ?> >2</option>
                                    <option <?php if($capacite == 6) { echo 'selected'; } ?> >6</option>
                                    <option <?php if($capacite == 10) { echo 'selected'; } ?> >10</option>
                                    <option <?php if($capacite == 15) { echo 'selected'; } ?> >15</option>
                                    <option <?php if($capacite == 20) { echo 'selected'; } ?> >20</option>
                                    <option <?php if($capacite == 30) { echo 'selected'; } ?> >30</option>
                                    <option <?php if($capacite == 50) { echo 'selected'; } ?> >50</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="categorie" class="form-label">Catégorie</label>
                                <select class="form-control w-50" id="categorie" name="categorie">
                                    <option>Réunion</option>
                                    <option <?php if($categorie == 'bureau') { echo 'selected'; } ?> >Bureau</option>
                                    <option <?php if($categorie == 'formation') { echo 'selected'; } ?> >Formation</option>                              
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="pays" class="form-label">Pays</label>
                                <select class="form-control" id="pays" name="pays">
                                    <option>France</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="ville" class="form-label">Ville</label>
                                <select class="form-control" id="ville" name="ville">
                                    <option>Paris</option>
                                    <option <?php if($ville == 'Lyon') { echo 'selected'; } ?> >Lyon</option>
                                    <option <?php if($ville == 'Marseille') { echo 'selected'; } ?> >Marseille</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse</label>
                                <textarea class="form-control" id="adresse" rows="3" name="adresse"><?php echo $adresse; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="cp" class="form-label">Code Postal</label>
                                <input type="text" class="form-control mb-4" id="cp" name="cp" value="<?php echo $cp; ?>">
                            </div>
                            <div class="mb-3 text-center">
                                <input type="submit" class="btn btn-outline-dark w-50 mt-4" id="enregistrement" name="enregistrement" value="Enregistrement">
                                <button type="button" class="btn btn-outline-warning mt-4 ms-2"><a href=" <?php echo URL; ?>admin/gestion_salles.php" class="text-decoration-none text-dark">Vider les champs</a></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <br><br>

        <!-- Affichage du tableau des salles -->
        <h4 class="px-3">Liste des salles</h4>

        <p class="text-end me-3">Trier par
            <a href="gestion_salles.php?tri=ville">ville</a>
            <a href="gestion_salles.php?tri=capacite">capacité</a>
        </p>

        <div class="row bg-light">
            <div class="table-responsive" id="affichage_vertical">

                <table class="table table-bordered">
                    <thead class="bg-dark text-light">
                        <tr class="align-middle">
                            <th>Id salle</th>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Photo</th>
                            <th>Pays</th>
                            <th>Ville</th>
                            <th>Adresse</th>
                            <th>Code Postal</th>
                            <th>Capacité</th>
                            <th>Catégorie</th>
                            <th>Modif</th>
                            <th>Suppr</th>
                        </tr>
                    </thead>
                    <?php 
                        while($ligne = $liste_salles->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tbody>';
                            echo '<tr class="align-middle">';
                            echo '<td data-title="Id_salle">' . $ligne['id_salle'] . '</td>';
                            echo '<td data-title="titre">' . $ligne['titre'] . '</td>';
                            echo '<td data-title="description">' . substr($ligne['description'], 0, 70) . ' <a href="">...</a></td>';
                            echo '<td data-title="photo"><a href="' . URL. 'assets/images_salles/' . $ligne['photo'] . '" target="_blank"><img src="' . URL. 'assets/images_salles/' . $ligne['photo'] . '" class="img-thumbnail" width="100%"></a></td>';
                            echo '<td data-title="pays">' . $ligne['pays'] . '</td>';
                            echo '<td data-title="ville">' . $ligne['ville'] . '</td>';
                            echo '<td data-title="adresse">' . $ligne['adresse'] . '</td>';
                            echo '<td data-title="cp">' . $ligne['cp'] . '</td>';
                            echo '<td data-title="capacite">' . $ligne['capacite'] . '</td>';
                            echo '<td data-title="categorie">' . $ligne['categorie'] . '</td>';
                            // le btn supp sera caché en css / display uniquement pour mobile
                            echo '<td><a href="?action=modifier&id_salle=' . $ligne['id_salle'] . '"  title="modifier" class="btn btn-warning text-white responsive_hidden"><i class="fas fa-edit"></i></a><a href="?action=modifier&id_salle=' . $ligne['id_salle'] . '"  title="modifier" class="btn btn-warning text-white responsive_display"><i class="fas fa-edit"></i></a><a href="?action=supprimer&id_salle=' . $ligne['id_salle'] . '" title="supprimer" class="btn btn-danger confirm_delete responsive_display"><i class="far fa-trash-alt"></i></a></td>';
                            echo '<td><a href="?action=supprimer&id_salle=' . $ligne['id_salle'] . '" title="supprimer" class="btn btn-danger confirm_delete responsive_hidden"><i class="far fa-trash-alt"></i></a></td>';
                            echo '</tr>';
                            echo '</tbody>';
                        }
                    ?>
                </table>
            </div>
        </div>        

    </main>


<?php 
include '../inc/footer.inc.php';