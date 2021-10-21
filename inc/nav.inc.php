<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo URL; ?>index.php"><strong>Room</strong></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo class_active('/index.php'); ?>" aria-current="page" href="<?php echo URL; ?>index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo class_active('/contact.php'); ?>" href="<?php echo URL; ?>contact.php">Contact</a>
                </li>


                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo class_active('/profil.php'); ?> <?php echo class_active('/connexion.php'); ?> <?php echo class_active('/inscription.php'); ?>" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Espace membre</a>

                    <?php if( user_is_connected() ) { ?>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?php echo URL; ?>profil.php">Profil</a></li>
                        <li><a class="dropdown-item" href="<?php echo URL; ?>connexion.php?action=deconnexion">Déconnexion</a></li>
                    </ul>
                    <?php } else { ?> 
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?php echo URL; ?>inscription.php">Inscription</a></li>
                        <li><a class="dropdown-item" href="<?php echo URL; ?>connexion.php">Connexion</a></li>
                    </ul>
                    <?php } ?>
                </li>
                
                <?php if( user_is_admin() ) { // condition pour affichage menu admin selon statut ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo class_active('/gestion_salles.php'); ?> <?php echo class_active('/gestion_produits.php'); ?> <?php echo class_active('/gestion_membres.php'); ?> <?php echo class_active('/gestion_avis.php'); ?> <?php echo class_active('/gestion_commandes.php'); ?> <?php echo class_active('/statistiques.php'); ?>" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Administration</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_salles.php">Gestion des salles</a></li>
                        <li><a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_produits.php">Gestion des produits</a></li>
                        <li><a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_membres.php">Gestion des membres</a></li>
                        <li><a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_avis.php">Gestion des avis</a></li>
                        <li><a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_commandes.php">Gestion des commandes</a></li>
                        <li><a class="dropdown-item" href="<?php echo URL; ?>admin/statistiques.php">Statistiques</a></li>
                    </ul>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>