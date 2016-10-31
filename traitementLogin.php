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
////////////////////////////Les variables communes à passer au template//////////////////
include_once ('inc/varTwig.inc.php');

////////////////////////////Modèle  ////////////////////////////////////////////

$db = new lib\bdd();            //instance de la database nécessaire pour les identifications

if ($statut == "eleve"){
    require 'inc/loginEleve.inc.php';
}
elseif ($statut =="professeur"){
    require 'inc/loginProf.inc.php';
}
elseif ($statut == "administratif") {
    require 'inc/loginAdmin.inc.php';
}
else{ 
    header('Location:index.php');
}