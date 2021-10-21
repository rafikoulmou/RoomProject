<?php 
include '../inc/init.inc.php';
include '../inc/functions.inc.php';


// Redirection si l'utilisateur n'est pas admin (saisie de l'url dans la barre d'adresse)
if( !user_is_admin() ) {
    header('location: ../connexion.php');
}

// var pour enregistrer le choix de la stat à afficher (clic sur le lien)
$selection='';

// Récupération du choix de la stat à afficher
if( isset($_GET['choix'])){
    
    // requête pour salles les mieux notées
    if($_GET['choix'] == 'meilleures_notes'){
        $note = $pdo->query("SELECT AVG(avis.note) AS note, avis.id_salle, salle.titre, membre.id_membre FROM avis INNER JOIN salle ON avis.id_salle=salle.id_salle INNER JOIN membre ON avis.id_membre = membre.id_membre GROUP BY avis.id_salle ORDER BY note DESC");
        $note_moyenne = $note->fetchAll(PDO::FETCH_ASSOC);
        $selection='meilleures_notes';

    // requête pour salles les plus commandées
    } elseif( $_GET['choix'] == 'plus_commandees' ) {
        $commandes = $pdo->query("SELECT COUNT(produit.etat) AS total_reservation, produit.id_salle, salle.titre FROM produit INNER JOIN salle ON produit.id_salle = salle.id_salle WHERE produit.etat = 'réservé' GROUP BY produit.id_salle ORDER BY total_reservation DESC");
        $plus_commandees = $commandes->fetchAll(PDO::FETCH_ASSOC);
        $selection = 'plus_commandees';

    // requête pour membres ayant le plus commandé (en nombre)
    } elseif( $_GET['choix'] == 'membre_commande' ) {
        $commandes = $pdo->query("SELECT COUNT(commande.id_membre) AS nbre_commandes, commande.id_membre, membre.pseudo FROM commande INNER JOIN membre ON commande.id_membre = membre.id_membre group by commande.id_membre ORDER BY nbre_commandes DESC");
        $plus_commandees = $commandes->fetchAll(PDO::FETCH_ASSOC);
        $selection = 'membre_commande';
    
    // requête pour membres ayant le plus commandé (en montant)
    } elseif( $_GET['choix'] == 'membre_montant' ) {
        $commandes = $pdo->query("SELECT SUM(produit.prix) AS montant_total, produit.id_produit, commande.id_membre, commande.id_produit, membre.pseudo FROM produit INNER JOIN commande ON produit.id_produit=commande.id_produit INNER JOIN membre ON commande.id_membre = membre.id_membre GROUP BY commande.id_membre ORDER BY montant_total DESC");
        $plus_commandees = $commandes->fetchAll(PDO::FETCH_ASSOC);
        $selection = 'membre_montant';
    } else { 
        header('location:../index.php'); // redirection si choix n'existe pas (saisie dans l'URL)
    }
} 


//------------------------------------------------------------------
// Les affichages dans la page commencent depuis la ligne suivante :
//------------------------------------------------------------------
include '../inc/header.inc.php';
include '../inc/nav.inc.php';
?>


    <main class="container-fluid">
        <div class="bg-light mt-4 p-2">
            <h3 class="p-2">Statistiques</h3>
        </div>

        <?php echo $msg . '<br>'; // variable destinée à afficher des messages utilisateur  ?>



        <div class="row">
            <div class="col-auto m-3">

                <!-- Affichage des liens pour choix de la stat à afficher -->
                <a href="statistiques.php?choix=meilleures_notes">Top 5 des salles les mieux notées</a><br>
                <a href="statistiques.php?choix=plus_commandees">Top 5 des salles les plus commandées</a><br>
                <a href="statistiques.php?choix=membre_commande">Top 5 des membres ayant le plus commandé (nombre de commandes)</a><br>
                <a href="statistiques.php?choix=membre_montant">Top 5 des membres ayant le plus commandé (montant)</a><br>
                <br>

            </div>
        

            <div class="col-auto ms-3">

                <?php

                    // Condition pour savoir quelle stat afficher

                    if ($selection == 'meilleures_notes' ) {
                        echo '<h5>Id_salle - Nom -> note</h5>'; 
                        // boucle pour afficher les 5 salles les mieux notées
                        for ($i = 0; $i <=4; $i++) {
                            echo '
                                <div>' . $note_moyenne[$i]['id_salle'] . ' - '. $note_moyenne[$i]['titre'] . ' -> ' . number_format($note_moyenne[$i]['note'],2) . ' /5<br><div>';
                        }

                    } elseif($selection == 'plus_commandees'){
                        echo '<h5>Id_salle - Nom -> total</h5>'; 
                        // boucle pour afficher les 5 plus grandes commandes 
                        for ($i = 0; $i <= 4; $i++) {
                            echo '
                                <div>' . $plus_commandees[$i]['id_salle'] . ' - '. $plus_commandees[$i]['titre'] . ' -> ' . $plus_commandees[$i]['total_reservation'] . ' commandes<br><div>';
                        } 

                    } elseif($selection == 'membre_commande'){
                        echo '<h5>Id_membre - pseudo -> total</h5>'; 
                        // boucle pour afficher les 5 membres ayant le plus commandé (en nombre) 
                        for ($i = 0; $i <= 4; $i++) {
                            echo '
                                <div>' . $plus_commandees[$i]['id_membre'] . ' - ' .$plus_commandees[$i]['pseudo'] . ' -> ' . $plus_commandees[$i]['nbre_commandes'] . ' commandes<br><div>';
                        }

                    } elseif($selection == 'membre_montant'){
                        echo '<h5>Id_membre - pseudo -> total</h5>'; 
                        // boucle pour afficher les 5 membres ayant le plus commandé (en montant) 
                        for ($i = 0; $i <= 4; $i++) {
                            echo '
                                <div>' . $plus_commandees[$i]['id_membre'] . ' - ' .$plus_commandees[$i]['pseudo'] . ' -> ' . $plus_commandees[$i]['montant_total'] . ' EUR<br><div>';
                        }
                    
                    } 
                ?>
            </div>
        </div>
    </main>



<br><br><br>
<br><br><br>




<?php 
include '../inc/footer.inc.php';