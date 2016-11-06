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
////////////////////////////inclusion des lib pour fpdf/////////////////////////
include_once ('lib/tfpdf/tfpdf.php');
include_once ('lib/pdf.class.php');

////////////////////////////identification////////////////////////////////////
$db = new lib\bdd();   
$admin = new lib\Administratif($db, $statut);
include_once ('inc/identificationAdmin.inc.php');

////////////////////////////Modèle  ////////////////////////////////////////////

$orientation= 'P';
$unit= 'mm';
$size= 'A4';
 
$codeStructure = $_GET['codeStructure'];


        
$tfpdf = new tFPDF($orientation, $unit, $size);
$publication = new \lib\pdf($db , $tfpdf);

$listeEleves = $publication->listeElevesParDivision($codeStructure);



