<?php
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


$db = new lib\bdd();                //instance de la database pour passer à l'éleve.

if ($statut == 'eleve'){
$eleve = new lib\Eleve($db, $statut); //création de l'élève

$connecte = gestionIdentification($eleve, $statut);        //gestion de l'identification (session & cookies)

$eleve->detruireCookie();
$eleve->detruireSession();

}
elseif($statut == 'professeur'){
    $prof = new lib\Professeur($db, $statut);
    $prof->detruireCookie();
    $prof->detruireSession();
}
elseif($statut == 'administratif'){
    $admin = new lib\Administratif($db, $statut);
    $admin->detruireCookie();
    $admin->detruireSession();
}
header('Location:index.php');

