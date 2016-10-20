<?php
////////////////////////////* Appel du header - Sessions*//////////////////////
include_once ('inc/headers.inc.php');
///////////////////////////////Appel des libraires  ////////////////////////////
include_once('inc/mainLib.inc.php');

////////////////////////////* Appel du moteur de templates Twig*////////////////
include_once ('inc/initTwig.inc.php');

////////////////////////////Connexion à la bdd/////////////////////////////////

$maConnexion = new bdd();
$maBDD = $maConnexion->getConnexion();  //récupération de l'objet BDD
$maConnexion->connexion(); //instance de connexion à la base
echo $maBDD;
///////////////////////test
$table = 'etablissement';
$champ = 'commune';
$enregistrement = '*';
$valeur = 'Strasbourg';
$ordre ='asc';

$maConnexion->lireValeurBdd($champ);
///////////////////////////////////////////////////////////////////////////////

$maPomme = new eleve();
$maPomme->code_confidentiel = 00000;
$maPomme->num_dossier = 00000;
$maPomme->setDossier(000000, 000000);
//$maPomme->genererSession($maBDD,1);
//$maPomme->identifierEleve($maBDD, 'eleve');
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
    'texte_footer'=>''.$texte_footer.'',
    )); //on envoie la variable au template

