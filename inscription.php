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

////////////////////////////passage du tableau de variables pour template///////

///////////////éventuelle surcharge des variables pour le template ?//////////
$template = 'mainTemplate';
/////////////////////////////////////////////////////////////

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
    'contenuArticle'=>''.$contenuArticle.'',
    ) ;

appelTemplate($template, $twig, $variablesTemplate);