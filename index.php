<?php
////////////////////////////* Appel du header - Sessions*//////////////////////
include_once ('inc/headers.inc.php');
///////////////////////////////Appel des libraires  ////////////////////////////
include_once('inc/mainLib.inc.php');
include_once('inc/fonctions.inc.php');
////////////////////////////* Appel du moteur de templates Twig*////////////////
include_once ('inc/initTwig.inc.php');


///////////////////////test
 $enregistrement = '*';
    $table = 'etablissement';
    $champ = 'region';
    $valeur = 'bourgogne';
    $orderby ='commune ASC';
 $monEtablissement = new lib\Etablissement(appelDatabase()); //retourne l'objet $database
 /*$monEtablissement->rechercherEtablissement(appelDatabase(), $enregistrement, $table, $champ, $valeur, $orderby);*/
///////////////////////////////////////////////////////////////////////////////

$maPomme = new lib\Eleve('eleve');

$maPomme->setNumDossier('000000');
$maPomme->setCodeConfidentiel('0000000');
//$maPomme->genererSession($maBDD,1);
//$maPomme->identifierEleve($maBDD, 'eleve');
//////////////////////////Lecture des infos du site////////////////////////////
$mesInfos = new lib\infos();
$mesInfos->lireInfos();
///////////////////////////////////////////////////////////////////////////////

/*
$eleveConnecte = new eleve();
$statut = 'eleve';
$numeroDossier= 007;
$codeConfidentiel='JD007';
print_r($eleveConnecte->identifierEleve($maConnexion, $statut, $numeroDossier, $codeConfidentiel));
*/

///////////////////////////////////// TWIG /////////////////////////////////////


$template = 'index';
$variablesTemplate = array('sexe'=>'F') ;
appelTemplate($template, $twig, $variablesTemplate);


