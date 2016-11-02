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

$prof = new lib\Professeur($db, $statut); //création de l'instance professeur

if (isset($phpsessid) && !empty($phpsessid) ){
    
    
    $connecte = TRUE;
}
else {
    $connecte = FALSE;
}

$profilProf =array(
    "nom"=>''.$_SESSION['nom'].'',
    "prenom"=>''.$_SESSION['prenom'].'',
    "nomComplet"=>''.$_SESSION['nomComplet'].'',
    "codeStructure"=>$_SESSION['codeStructure'],
    "id_pedago"=>''.$_SESSION['id_pedago'].'',
    "civilite"=>''.$_SESSION['civilite'].'',
    "matiere"=>$_SESSION['matiere'],
    
    
);

$idPedago = $_SESSION['id_pedago'];
$prof->setIdPedago($idPedago);

$prof->ecrireCommentaireVoeu($_POST);


