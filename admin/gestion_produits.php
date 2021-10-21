<?php 
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

// Redirection si l'utilisateur n'est pas connecté (saisie de l'url dans la barre d'adresse)
if( !user_is_admin() ) {
    header('location: ../connexion.php');
}


// Suppression produit
if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_produit'])) {
    $suppression = $pdo->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
    $suppression->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
    $suppression->execute();
}


// Enregistrement et modification produit

$id_produit = '';
$date_arrivee = '';
$date_depart = '';
$id_salle = '';
$prix = '';
$etat = '';


// Controles des données
if(isset($_POST['date_arrivee']) && isset($_POST['date_depart']) && isset($_POST['id_salle']) && isset($_POST['prix']) && isset($_POST['etat']) ) {

    // modif format de la string $_POST[date] de mm/jj/aaaa -> jj/mm/aaaa (sinon le format de la date envoyée sur $_POST est du format mm/jj/aaaa au lieu de jj/mm/aaaa... je n'ai pas trouvé pourquoi...)
    $mm_da1 = substr($_POST['date_arrivee'], 0, 3);
    $jj_da1 = substr($_POST['date_arrivee'], 3, 3);
    $aaaa_da1 = substr($_POST['date_arrivee'], 6, 4);
    $date_arrivee = $jj_da1 . $mm_da1 . $aaaa_da1 ;

    $mm_dp1 = substr($_POST['date_depart'], 0, 3);
    $jj_dp1 = substr($_POST['date_depart'], 3, 3);
    $aaaa_dp1 = substr($_POST['date_depart'], 6, 4);
    $date_depart = $jj_dp1 . $mm_dp1 . $aaaa_dp1 ;



    $date_arrivee = date('Y/m/d H:i:s', strtotime($date_arrivee)); // ajout de l'heure
    $date_depart = date('Y/m/d H:i:s', strtotime($date_depart)); // ajout de l'heure
    $id_salle = trim(strstr($_POST['id_salle'], '-', true)); // pour récuperer l'id_salle de la salle choisie
    $prix = trim($_POST['prix']);
    $etat = trim($_POST['etat']);


    // Pour la modif, recup de l'id
    if( !empty($_POST['id_produit']) ) {
        $id_produit = trim($_POST['id_produit']);
    }

    // déclaration du flag pour les erreurs
    $erreur = false;

    // Rendre la date_arrivee, date_depart et le prix obligatoires
    if (empty($date_arrivee) || empty($date_depart) || empty($prix)) {
        $msg .= '<div class="alert alert-danger mt-3">Attention, les dates et le prix sont obligatoires.</div>';
        // cas d'erreur
        $erreur = true;
        // Réinitialisation des dates pour affichage vierge dans formulaire
        $date_arrivee = '';
        $date_depart = '';;
    }

    // si pas d'erreur, on enregistre en bdd
    if($erreur == false) {

        // si l'id_produit n'est pas vide -> modif       

        if( !empty($id_produit) ) {
            $enregistrement = $pdo->prepare("UPDATE produit SET date_arrivee = :date_arrivee, date_depart = :date_depart, etat = :etat, id_salle = :id_salle, prix = :prix WHERE id_produit = :id_produit");
            $enregistrement->bindParam(':id_produit', $id_produit, PDO::PARAM_STR);
            echo ' <hr>';

        } else {
            
            $enregistrement = $pdo->prepare("INSERT INTO produit (date_arrivee, date_depart, etat, id_salle, prix) VALUES (:date_arrivee, :date_depart, :etat, :id_salle, :prix)");
        } 


        $enregistrement->bindParam(':date_arrivee', $date_arrivee, PDO::PARAM_STR);
        $enregistrement->bindParam(':date_depart', $date_depart, PDO::PARAM_STR);
        $enregistrement->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);
        $enregistrement->bindParam(':etat', $etat, PDO::PARAM_STR);
        $enregistrement->bindParam(':prix', $prix, PDO::PARAM_STR);
        $enregistrement->execute();
        
        // Réinitialisation des dates pour affichage vierge dans formulaire
        $date_arrivee = '';
        $date_depart = '';

    }

} // fin de isset


// Récupération des infos du membre à modifier

if(isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_produit'])) {

    $recup_infos = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $recup_infos->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
    $recup_infos->execute();

    $infos_produits = $recup_infos->fetch(PDO::FETCH_ASSOC);

    $id_produit = $infos_produits['id_produit'];
    $date_arrivee =  date("d/m/Y", strtotime($infos_produits['date_arrivee']));

    $date_depart = date("d/m/Y", (strtotime($infos_produits['date_depart'])));
    // $date_depart = ($date_dep, '%d/%m/%Y %H:%i');
    $etat = $infos_produits['etat'];
    $id_salle = $infos_produits['id_salle'];
    $prix = $infos_produits['prix'];
}


// Récupération de la table salle pour remplir champ "Salle" du formulaire
$liste_salles = $pdo->query("SELECT * FROM salle ORDER BY id_salle");


// Récupération des données des tables produit et salle
$liste_produits = $pdo->query("SELECT id_produit, produit.id_salle, date_format(date_arrivee, '%d/%m/%Y') AS date_arrivee_fr, date_format(date_depart, '%d/%m/%Y') AS date_depart_fr, prix, etat, photo, titre, categorie FROM produit INNER JOIN salle ON produit.id_salle = salle.id_salle ORDER BY date_arrivee");


//------------------------------------------------------------------
// Les affichages dans la page commencent depuis la ligne suivante :
//------------------------------------------------------------------

include '../inc/header.inc.php';
include '../inc/nav.inc.php';
?>

<main class="container-fluid px-4">
    <div class="bg-light mt-4 p-2">
        <h3 class="p-2">Gestion des produits</h3>
    </div>

    <?php echo $msg . '<br>'; // variable destinée à afficher des messages utilisateur
    ?>

    <!-- Affichage du formulaire -->
    <div class="row">
        <div class="col-12 mt-2">
            <form method="post" action="" class="border p-3">

                <input type="hidden" name="id_produit" id="id_produit" value="<?php echo $id_produit ?>">

                <div class="row">

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date_arrivee" class="form-label">Date d'arrivée</label>
                            <input type="text" class="form-control" placeholder="JJ/MM/AAAA" name="date_arrivee" id="date_arrivee" value="<?php echo $date_arrivee ?>">
                        </div>
                        <div class="mb-3">
                            <label for="date_depart" class="form-label">Date de départ</label>
                            <input type="text" class="form-control" placeholder="JJ/MM/AAAA" name="date_depart" id="date_depart" value="<?php echo $date_depart ?>">
                        </div>
                        <div class="mb-3">
                            <label for="etat" class="form-label">Etat</label>
                            <select class="form-control w-50" id="etat" name="etat">
                                <option value="libre">Libre</option>
                                <option value="reserve" <?php if($etat == 'réservé') { echo 'selected'; } ?> >Réservé</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="id_salle" class="form-label">Salle</label>
                            <select class="form-control" id="id_salle" name="id_salle">
                            <?php

                                $info_salle = $liste_salles->fetchAll(PDO::FETCH_ASSOC);

                                // condition pour afficher le champ Salle si on est dans une modif
                                if(!empty($id_produit)) {
                                    echo '<option>' . $info_salle[$id_salle-1]['id_salle'] . ' - ' . $info_salle[$id_salle-1]['titre'] . ' - ' . $info_salle[$id_salle-1]['adresse'] . ' - ' . $info_salle[$id_salle-1]['cp'] . ' - ' . $info_salle[$id_salle-1]['ville'] . ' - ' . $info_salle[$id_salle-1]['categorie'] . ' - ' . $info_salle[$id_salle-1]['capacite'] . ' pers' . '</option>';
                                }

                                else {
                                
                                    for($i = 0; $i < count($info_salle); $i++) {
                                        echo '<option>' . $info_salle[$i]['id_salle'] . ' - ' . $info_salle[$i]['titre'] . ' - ' . $info_salle[$i]['adresse'] . ' - ' . $info_salle[$i]['cp'] . ' - ' . $info_salle[$i]['ville'] . ' - ' . $info_salle[$i]['categorie'] . ' - ' . $info_salle[$i]['capacite'] . ' pers' . '</option>';
                                    }
                                }

                            ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="prix" class="form-label">Prix</label>
                            <input type="text" class="form-control mb-4" name="prix" id="prix" value="<?php echo $prix; ?>">
                        </div>
                        <div class="mb-3 text-center">
                            <input type="submit" class="btn btn-outline-dark w-50 mt-4" id="enregistrement" name="enregistrement" value="Enregistrement">
                            <button type="button" class="btn btn-outline-warning mt-4 ms-2"><a href=" <?php echo URL; ?>admin/gestion_produits.php" class="text-decoration-none text-dark">Vider les champs</a></button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <!-- Affichage du tableau des produits -->
    <div class="row">
        <div class="col-12 mt-5">
            <table class="table table-bordered">
                <tr class="bg-dark text-white text-center">

                    <th>Id</th>
                    <th>Date d'arrivée</th>
                    <th>Date de départ</th>
                    <th>Salle</th>
                    <th>Prix</th>
                    <th>Etat</th>
                    <th>Voir</th>
                    <th>Modif</th>
                    <th>Suppr</th>
                </tr>
                <?php

                    while($ligne = $liste_produits->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr class="text-center align-middle">';
                        echo '<td>' . $ligne['id_produit'] . '</td>';
                        echo '<td>' . $ligne['date_arrivee_fr'] . '</td>';
                        echo '<td>' . $ligne['date_depart_fr'] . '</td>';
                        echo '<td>' . $ligne['id_salle'] . ' - ' . ucfirst($ligne['categorie']) . ' ' . $ligne['titre'] . '<br>' . '<img src="' . URL. 'assets/images_salles/' . $ligne['photo'] . '" class="img-thumbnail" width="100">';
                        echo '<td>' . $ligne['prix'] . '</td>';
                        echo '<td>' . $ligne['etat'] . '</td>';
                        echo '<td><a href="' . URL . 'fiche_produit.php?id_produit=' .  $ligne['id_produit'] . '" class="text-dark"><strong><i class="fas fa-search"></i></strong></a></td>';                        
                        echo '<td><a href="?action=modifier&id_produit=' . $ligne['id_produit'] . '"  title="modifier" class="btn btn-warning text-white"><i class="fas fa-edit"></i></a></td>';
                        echo '<td><a href="?action=supprimer&id_produit=' . $ligne['id_produit'] . '" title="supprimer" class="btn btn-danger confirm_delete"><i class="far fa-trash-alt"></i></a></td>';
                        echo '</tr>';
                    }
                
                ?>
            </table> 
        </div>
    </div>

</main>




<?php 
include '../inc/footer.inc.php';