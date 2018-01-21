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

////////////////////////////Modèle  ////////////////////////////////////////////
$db = new lib\bdd();            //instance de la database nécessaire pour les identifications

$prof = new lib\Professeur($db, $statut); //création de l'instance professeur

if (isset($phpsessid) && !empty($phpsessid) ){
    
    $profilProf =array(
    "nom"=>''.$_SESSION['nom'].'',
    "prenom"=>''.$_SESSION['prenom'].'',
    "nomComplet"=>''.$_SESSION['nomComplet'].'',
    "codeStructure"=>$_SESSION['codeStructure'],
    "id_pedago"=>''.$_SESSION['id_pedago'].'',
    "civilite"=>''.$_SESSION['civilite'].'',
    "matiere"=>$_SESSION['matiere'],
    
    
    );
    $connecte = TRUE;
}
else {
     $profilProf = $prof->profilProf();

        $prof->genererSession();
        $prof->genererCookie();
        $connecte = TRUE;

        $profilProf =array(
        "nom"=>''.$prof->getNom().'',
        "prenom"=>''.$prof->getPrenom().'',
        "nomComplet"=>''.$prof->getNomComplet().'',
        "codeStructure"=>$prof->getCodeStructure(),
        "id_pedago"=>''.$prof->getIdPedago().'',
        "civilite"=>''.$prof->getCivilite().'',
        "matiere"=>$prof->getMatiere(),


        );
}

$classe = htmlentities($_GET['classe']);
$listing = $prof->listingParClasse($classe);

///////////////éventuelle surcharge des variables pour le template ?//////////
$template = 'listing';     //Nom du template à appeler

$page = 'listing';         //Nom de l'index pour récupérer les infos pour les textes
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
    'connecte'=>''.$connecte.'',
      
    'texte_footer'=>''.$texte_footer.'',
    'bandeauLogin'=>''.bandeauLogin($statut).'', //pour la construction du bandeau 
    'listing'=> $listing,
    'nomClasse'=>''.$classe.'',
    
    ) ;


$mergeVarTemplate = array_merge(
        $contenuIdentifiants,
        $variablesTemplate,
        $contenuArticle,
        $contenuMenu,
        $profilProf
        
        ); //construction du tableau avec les données à envoyer au template


appelTemplate($template, $twig, $mergeVarTemplate); //construction de la page web
    
    