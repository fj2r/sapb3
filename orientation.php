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


$db = new lib\bdd();                //instance de la database pour passer à l'éleve.

$eleve = new lib\Eleve($db, $statut); //création de l'élève

$connecte = gestionIdentification($eleve);        //gestion de l'identification (session & cookies)

$eleve->profilEleve();                //récupération des infos sur l'élève

$profilEleve =array(
    "nom"=>''.$eleve->getNom().'',
    "prenom"=>''.$eleve->getPrenom().'',
    "classe"=>''.$eleve->getLibStructure().'',
    "codeClasse"=>''.$eleve->getCodeStructure().'',
    "id"=>''.$eleve->getId_eleve().'',
    "sexe"=>''.$eleve->getSexe().'',
    
    );

$listeProfesseurs = $eleve->listerProfesseurs(); // qui sont les professeurs de sa classe ? Renvoi un tableau de dimension 2

$nbVoeux  = intval($eleve->verifierVoeux()); //combien a-t-il de voeux ?
$listeVoeux = $eleve->recupererVoeux();

////////////////////////////Les variables communes à passer au template//////////////////
include_once ('inc/varTwig.inc.php');

////////////////////////////passage du tableau de variables pour template///////

///////////////éventuelle surcharge des variables pour le template ?//////////
$template = 'orientation';     //Nom du template à appeler

$page = 'orientation';         //Nom de l'index pour récupérer les infos pour les textes
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
$variablesTemplate = array(
    'annee' => ''.$date.'',
    'version'=>''.$version.'',
    'charset'=>''.$charset.'',
    'titrePage'=>''.$titrePage.'',
    'connecte'=>''.$connecte.'',
    
    'sexe'=>''.$eleve->getSexe().'',
    'texte_footer'=>''.$texte_footer.'',
    'bandeauLogin'=>''.bandeauLogin($statut).'', //pour la construction du bandeau 
    'statut'=>''.$statut.'',
    'listeProfesseurs'=>$listeProfesseurs,
    'nbVoeux'=>''.$nbVoeux.'',
    'listeVoeux'=>$listeVoeux,
    ) ;


$mergeVarTemplate = array_merge(
        $contenuIdentifiants,
        $variablesTemplate,
        $contenuArticle,
        $contenuMenu,
        $profilEleve
        ); //construction du tableau avec les données à envoyer au template


appelTemplate($template, $twig, $mergeVarTemplate); //construction de la page web