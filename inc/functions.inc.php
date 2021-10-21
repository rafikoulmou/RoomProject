<?php

// Fonction pour mettre la classe active sur les liens du menu
function class_active($url) {
    $page = strrchr($_SERVER['PHP_SELF'], '/');
    if($page == $url) {
        return ' active ';
    }
}

// Fonction permettant de savoir si l'utilisateur est connecté
function user_is_connected() {
    if( !empty($_SESSION['membre']) ) {
        return true;
    } else {
        return false;
    }
}

// Fonction permettant de savoir si un utilisateur est connecté et si admin
function user_is_admin() {
    if(user_is_connected() && $_SESSION['membre']['statut'] == 2) {
        return true;
    }
    return false;
}


// Fonction pour récupération de la civilité
function civilite($x) {
    if($x == 'm') {
        return 'Monsieur';
    } else {
    return 'Madame';
}
}

// Fonction pour récupération du statut 
function statut($x) {
    if($x == 1) {
        return 'Membre';
    } else {
        return 'Admin';
}
}