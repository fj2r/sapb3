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


////////////////////////////Modèle  ////////////////////////////////////////////
if(isset($_POST)){
    if(empty($_POST['num_dossier']) || empty($_POST['code_conf']) || $_POST['num_dossier'] =='N° de dossier' || $_POST['code_conf']=='Mot de passe' ){
        $string = 'Location: login.php?statut=eleve';
        header($string);
    }
}

$db = new lib\bdd();            //instance de la database

$eleve = new lib\Eleve($db, $statut); //création de l'élève
$eleve->setCodeConfidentiel($_POST['code_conf']);
$eleve->setNumDossier($_POST['num_dossier']);

$eleve->profilEleve(); //récupération des infos sur l'élève

$profilEleve =array(
    "nom"=>''.$eleve->getNom().'',
    "prenom"=>''.$eleve->getPrenom().'',
    "classe"=>''.$eleve->getLibStructure().'',
    "codeClasse"=>''.$eleve->getCodeStructure().'',
    "id"=>''.$eleve->getId_eleve().'',
    "sexe"=>''.$eleve->getSexe().'',
    
);

////////////////////////////Les variables communes à passer au template//////////////////
include_once ('inc/varTwig.inc.php');

////////////////////////////passage du tableau de variables pour template///////

///////////////éventuelle surcharge des variables pour le template ?//////////
$template = 'traitementLogin';     //Nom du template à appeler

$page = 'traitementLogin';         //Nom de l'index pour récupérer les infos pour les textes
$contenuJSON = new lib\generateurArticle($page); //on instancie le générateur d'article 
$contenuArticle = $contenuJSON->lireContenu($page)[''.$page.''][0]; // méthode pour lire les infos du fichier de langue

$pageIdentifiants = 'identifiants';                //Nom de l'index pour récupérer les infos pour les menus du bandeau
$contenuJSONidentifiants = new lib\generateurArticle($pageIdentifiants);
$contenuIdentifiants = $contenuJSONidentifiants->lireContenu($pageIdentifiants)[''.$pageIdentifiants.''][0];

$pageMenu = 'menus';                //Nom de l'index pour récupérer les infos pour les menus du bandeau
$contenuJSONMenu = new lib\generateurArticle($pageMenu);
$contenuMenu = $contenuJSONMenu->lireContenu($pageMenu)[''.$pageMenu.''][0];

/////////////////////////////////////////////////////////////

$connecte = true ;
$variablesTemplate = array('annee' => ''.$date.'',
    'version'=>''.$version.'',
    'charset'=>''.$charset.'',
    'titrePage'=>''.$titrePage.'',
    'connecte'=>''.$connecte.'',
    
    'sexe'=>''.$sexe.'',
    'texte_footer'=>''.$texte_footer.'',
    'bandeauLogin'=>''.bandeauLogin($statut).'', //pour la construction du bandeau 
    'statut'=>''.$statut.'',
    ) ;


$mergeVarTemplate = array_merge(
        $contenuIdentifiants,
        $variablesTemplate,
        $contenuArticle,
        $contenuMenu,
        $profilEleve); //construction du tableau avec les données à envoyer au template


appelTemplate($template, $twig, $mergeVarTemplate); //construction de la page web