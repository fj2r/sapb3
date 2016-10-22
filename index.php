<?php
////////////////////////////* Appel du header - Sessions*//////////////////////
include_once ('inc/headers.inc.php');
///////////////////////////////Appel des libraires  ////////////////////////////
include_once('inc/mainLib.inc.php');
include_once('inc/fonctions.inc.php');
////////////////////////////* Appel du moteur de templates Twig*////////////////
include_once ('inc/initTwig.inc.php');

////////////////////////////Connexion à la bdd/////////////////////////////////
 

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

$lien_eleve = 'login.php?statut=eleve'; //liens sur la page d'accueil
$lien_professeur = 'login.php?statut=professeur';
$lien_administratif = 'login.php?statut=administratif';
        

$texte_footer = 'Copyright LMN Autun'; /*pour le footer*/
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
    'lien_eleve'=>''.$lien_eleve.'',
    'lien_professeur'=>''.$lien_professeur.'',
    'lien_administratif'=>''.$lien_administratif.'',
    )); //on envoie la variable au template

