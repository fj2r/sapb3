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


//////////////////////////// Modèle ////////////////////////////////////////////
$db = new lib\bdd();   

////////////////////////////passage du tableau de variables pour template///////

///////////////éventuelle surcharge des variables pour le template ?//////////
$template = 'login';     //Nom du template à appeler

$page = 'login';         //Nom de l'index pour récupérer les infos pour les textes
$contenuJSON = new lib\generateurArticle($page); //on instancie le générateur d'article 
$contenuArticle = $contenuJSON->lireContenu($page)[''.$page.''][0]; // méthode pour lire les infos du fichier de langue

$pageIdentifiants = 'identifiants';                //Nom de l'index pour récupérer les infos pour les menus du bandeau
$contenuJSONidentifiants = new lib\generateurArticle($pageIdentifiants);
$contenuIdentifiants = $contenuJSONidentifiants->lireContenu($pageIdentifiants)[''.$pageIdentifiants.''][0];

$pageMenu = 'menus';                //Nom de l'index pour récupérer les infos pour les menus du bandeau
$contenuJSONMenu = new lib\generateurArticle($pageMenu);
$contenuMenu = $contenuJSONMenu->lireContenu($pageMenu)[''.$pageMenu.''][0];
/////////////////////////////////////////////////////////////


$variablesTemplate = array('annee' => ''.$date.'',
    'version'=>''.$version.'',
    'charset'=>''.$charset.'',
    'titrePage'=>''.$titrePage.'',
    'connecte'=> FALSE,
    
    'texte_footer'=>''.$texte_footer.'',
    'bandeauLogin'=>''.bandeauLogin($statut).'',
    
    'statut'=>''.$statut.'',
    ) ;


////////////////////cas selon le statut /////////////////////////////////

if ($_GET['statut'] == 'eleve'){
$eleve = new lib\Eleve($db);

$eleve->detruireCookie();
$eleve->detruireSession();

$connecte = gestionIdentification($eleve, $statut);        //gestion de l'identification (session & cookies)

$existenceProfil = $eleve->profilEleve();                //récupération des infos sur l'élève

if ($existenceProfil == TRUE){
    $eleve->genererSession();
    $eleve->genererCookie();
    $connecte = TRUE;
}
else {
    $connecte = FALSE;
}
$profilEleve =array(
    "nom"=>''.$eleve->getNom().'',
    "prenom"=>''.$eleve->getPrenom().'',
    "classe"=>''.$eleve->getLibStructure().'',
    "codeClasse"=>''.$eleve->getCodeStructure().'',
    "id"=>''.$eleve->getId_eleve().'',
    "sexe"=>''.$eleve->getSexe().'',
    
    );




$mergeVarTemplate = array_merge(
        $contenuIdentifiants,
        $variablesTemplate,
        $contenuArticle,
        $contenuMenu,
        $profilEleve
        ); //construction du tableau avec les données à envoyer au template


appelTemplate($template, $twig, $mergeVarTemplate); //construction de la page web
}
elseif ($_GET['statut']=='professeur'){
    $prof = new lib\Professeur($db);
    $prof->detruireCookie();
    $prof->detruireSession();
    
    
    
    $mergeVarTemplate = array_merge(
        $contenuIdentifiants,
        $variablesTemplate,
        $contenuArticle,
        $contenuMenu
        
        ); //construction du tableau avec les données à envoyer au template


appelTemplate($template, $twig, $mergeVarTemplate); //construction de la page web
}
elseif ($_GET['statut']=='administratif'){
    $admin = new \lib\Administratif($db);
    $admin ->detruireCookie();
    $admin ->detruireSession();
    
    $mergeVarTemplate = array_merge(
        $contenuIdentifiants,
        $variablesTemplate,
        $contenuArticle,
        $contenuMenu
        
        ); //construction du tableau avec les données à envoyer au template


appelTemplate($template, $twig, $mergeVarTemplate); //construction de la page web
}
else {
 header('Location:index.php');
}
