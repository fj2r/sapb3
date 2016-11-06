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

$admin = new lib\Administratif($db, $statut);
include_once ('inc/identificationAdmin.inc.php');

$admin->setIdAdmin($_SESSION['id_admin']);
$admin->ecrireCommentaireP1($_POST);

header ('Location:avisAdministratif.php?&statut='.$statut.'&codeStructure='.$_GET['codeStructure'].'&idEleve='.$_GET['idEleve'].'');