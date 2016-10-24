<?php

/* 
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */


$template = 'mainTemplate';                                //Nom par défaut du template appelé                                                 
$statut = (isset($_GET['statut']) &&
        !empty($_GET['statut']))
        ? $_GET['statut']
        : $statut='eleve';                          // on assigne le statut par défaut à élève s'il n'est pas passé en paramètre
$date = date('d/m/Y');                              // la date du jour
$version = '3.0';                                   // la version de sapb
$charset = "UTF-8";                                 // le charset par défaut
$titrePage = 'sAPB';                                // le titre des fenêtres

if (isset($_SESSION)){
    if (isset ($_SESSION['connecte'])&& !empty($_SESSION['connecte'])){
        $connecte = TRUE;
    } else { $connecte=FALSE ; }
    if (isset ($_SESSION['prenom'] ) && !empty($_SESSION['prenom'])){
        $prenom = $_SESSION['prenom'];
    } else {$prenom = 'John'; }
    if (isset ($_SESSION['nom'] ) && !empty($_SESSION['nom'])){
        $nom = $_SESSION['nom'];
    } else { $nom = 'DOE'; }
    if (isset ($_SESSION['sexe'] ) && !empty($_SESSION['sexe'])){
        $sexe = $_SESSION['sexe'];
    } else { $sexe = 'M'; }
    if (isset ($_SESSION['classe'] ) && !empty($_SESSION['classe'])){
        $classe = $_SESSION['classe'];
    } else { $classe = 'PSTMG';}

}
else {
    $connecte= FALSE;
    $nom = 'DOE';
    $prenom = 'John';
    $sexe = 'M';
    $classe = "";
}

/*pour le contenu des articles */

$contenuArticle = ':)';

/*pour le footer*/
$texte_footer = 'Copyright LMN Autun';
