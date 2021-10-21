<?php 
include 'inc/init.inc.php';
include 'inc/functions.inc.php';


// Initialisation (à vide) des variables pour conservation des saisies du formulaire
$nom = '';
$prenom = '';
$email = '';
$entreprise = '';
$sujet = '';
$message = '';

// Controle des données du formulaire
if( isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email'])  && isset($_POST['entreprise']) && isset($_POST['sujet']) && isset($_POST['message']) ) {

    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $sujet = trim($_POST['sujet']);
    $message = $_POST['message'];
    $entreprise = $_POST['entreprise'];

    $erreur = false; // flag pour les erreurs


    // Saisie de tous les champs
    if( empty($nom) || empty($prenom) || empty($email) || empty($message) ) {
        $msg .= '<div class="alert alert-danger mt-3">Attention,<br>tous les champs sont obligatoires !</div>';
        $erreur = true;
    }

    // Format du mail
    if(filter_var($email, FILTER_VALIDATE_EMAIL) == false ) {
        $msg .= '<div class="alert alert-danger mt-3">Attention,<br>le format de l\'email n\'est pas correct !</div>';
        $erreur = true;
    }

    // Si pas d'erreur, on lance l''envoi du mail
    if($erreur == false) {

        // Envoi du mail de confirmation d'inscription
        $message = 'de : ' . $prenom . '-' . $nom . ' / sujet = ' . $sujet . ' / message = ' . $message;
        $expediteur = 'From: ' . $email;
        mail(EMAIL_ADMIN, $sujet, $message, $expediteur);

        // Réinitialisation des champs suite à l'envoi 
        $nom = '';
        $prenom = '';
        $email = '';
        $entreprise = '';
        $sujet = '';
        $message = '';

    }

}


//------------------------------------------------------------------
// Les affichages dans la page commencent depuis la ligne suivante :
//------------------------------------------------------------------
include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>


    <main class="container-fluid px-4">

        <?php echo $msg . '<br>'; // variable destinée à afficher des messages utilisateur  ?>


        <div class="">
            <h3 class="bg-light p-2">Formulaire de contact</h3>
            <p class="">Des questions ? Nos équipes sont à votre disposition pour vous apporter toutes les réponses.</p>
        </div>
    
        <div class="row">
            <div class="col-12 mb-5">
                <form method="post" action="" class="border border-1 p-3">
                    <div class="row">
                        <div class="col-sm-6">
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
                            <div class="mb-3">
                                <label for="entreprise" class="form-label">Entreprise</label>
                                <input type="text" class="form-control" id="entreprise" name="entreprise" value="<?php echo $entreprise; ?>"> 
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="sujet" class="form-label">Sujet</label>
                                <input class="form-control" id="sujet" name="sujet" value="<?php echo $sujet; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="8" value="<?php echo $message; ?>"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="mt-4 mb-4 text-center">
                                <input type="submit" class="btn btn-outline-dark w-25" id="inscription" name="inscription">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>







<br><br>

<?php 
include 'inc/footer.inc.php';