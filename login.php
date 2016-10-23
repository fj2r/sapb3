<?php

/* 
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

////////////////////////////* Appel du header - Sessions*//////////////////////
include_once ('inc/headers.inc.php');
///////////////////////////////Appel des libraires  ////////////////////////////
include_once('inc/mainLib.inc.php');
include_once('inc/fonctions.inc.php');
////////////////////////////* Appel du moteur de templates Twig*////////////////
include_once ('inc/initTwig.inc.php');

////////////////////////////Les variables à passer au template//////////////////
$statut = (isset($_GET['statut']) && !empty($_GET['statut'])) ? $_GET['statut'] : $statut='eleve';
$date = date('d/m/Y');
$version = $_SESSION['version']; 
$charset = "UTF-8";
$titrePage = $_SESSION['nom_application'];

if (isset($_SESSION)){
$connecte = TRUE;

$prenom = "titi";
$nom = "toto";
$sexe = "F";

}
else {
    $connecte= FALSE;
}

/*pour le footer*/
$texte_footer = 'Copyright LMN Autun';


///////////////////////////Fin des décla de variables pour le template//////////



$template = 'login';
$variablesTemplate = array('annee' => ''.$date.'',
    'version'=>''.$version.'',
    'charset'=>''.$charset.'',
    'titrePage'=>''.$titrePage.'',
    'connecte'=>''.$connecte.'',
    'prenom'=>''.$prenom.'',
    'nom'=>''.$nom.'',
    'sexe'=>''.$sexe.'',
    'texte_footer'=>''.$texte_footer.'',
    'bandeauLogin'=>''.bandeauLogin($statut).'',
    ) ;

appelTemplate($template, $twig, $variablesTemplate);