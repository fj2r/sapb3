<?php
////////////////////////////* Appel du header - Sessions*//////////////////////
 include_once ('inc/headers.inc.php');
//////////////////////////////////Fin de l'appel du header/////////////////////

////////////////////////////* Appel du moteur de templates Twig*////////////////
 include_once ('inc/initTwig.inc.php');
//////////////////////////////////Fin de l'appel du moteur de template/////////

///////////////////////////////Appel des libraires  nécessaires////////////////
include_once('inc/mainLib.inc.php');  
///////////////////////////////Fin de l'appel des lib//////////////////////////


////////////////////////////Connexion à la bdd/////////////////////////////////

$maConnexion = new bdd();
$maConnexion->connexion();
/*
$table = 'etablissement';
$champ = 'commune';
$valeur = 'Strasbourg';
//$maConnexion->lireValeurBdd($valeur); */
///////////////////////////////////////////////////////////////////////////////

//////////////////////////Lecture des infos du site////////////////////////////
$mesInfos = new infos();
$mesInfos->lireInfos();
///////////////////////////////////////////////////////////////////////////////

/*
$eleveConnecte = new eleve();
$statut = 'eleve';
$numeroDossier= 007;
$codeConfidentiel='JD007';
print_r($eleveConnecte->identifierEleve($maConnexion, $statut, $numeroDossier, $codeConfidentiel));
*/
////////////////////////////Les variables à passer au template//////////////////
$date = date('d/m/Y');
$version = $_SESSION['version']; 
$charset = "UTF-8";
$titrePage = $_SESSION['nom_application'];

$connecte = TRUE;

$prenom = "titi";
$nom = "toto";
$sexe = "F";
///////////////////////////Fin des décla de variables///////////////////////////


$template = $twig->loadTemplate('index.twig'); //on va chercher le template associé
echo $template->render(array(
    'annee' => ''.$date.'',
    'version'=>''.$version.'',
    'charset'=>''.$charset.'',
    'titrePage'=>''.$titrePage.'',
    'connecte'=>''.$connecte.'',
    'prenom'=>''.$prenom.'',
    'nom'=>''.$nom.'',
    'sexe'=>''.$sexe.'',
    )); //on envoie la variable au template


    
    
