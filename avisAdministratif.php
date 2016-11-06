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
$db = new lib\bdd(); 
$admin = new lib\Administratif($db, $statut);
include_once ('inc/identificationAdmin.inc.php');

$division = new lib\Division($db, $_GET['codeStructure']); //on instancie pour avoir des infos sur la classe et lister les élèves

$listeEleves = $division->listerEleves();               //c'est un tableau à 1 dimension
$premierDeLaListe = $division->premierListeDivision()[0]['id_eleve'];  //  on cherche l'id du premier de la liste

$eleve = new \lib\Eleve($db, 'eleve');                  //mais qui est donc cet élève ??

/* Pour pouvoir passer d'un élève à l'autre dans le navigateur */
if (!isset($_GET['idEleve'])){
    $eleve->setIdEleve($premierDeLaListe);
    }
else {
    $eleve->setIdEleve($_GET['idEleve']);
    }

    
$infosEleve = $eleve->informationsEleve();  // et hop on sait tout de lui
$voeuxEleve = $eleve->recupererVoeux();     // et on a tous ses voeux
$avisProfesseurs = $eleve->recupererAvisProfesseurs();



$elevePrecedent = $division->elevePrecedent($eleve->getCodeStructure(), $eleve->getNom(), $eleve->getPrenom())[0];
$eleveSuivant = $division->eleveSuivant($eleve->getCodeStructure(), $eleve->getNom(), $eleve->getPrenom())[0];




////////////////////////////passage du tableau de variables pour template///////

///////////////éventuelle surcharge des variables pour le template ?//////////
$template = 'avisAdministratif';     //Nom du template à appeler

$page = 'avisAdministratif';         //Nom de l'index pour récupérer les infos pour les textes
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
    'statut'=>''.$statut.'',
    'profilAdmin'=>$profilAdmin,
    'classeSelectionnee'=>''.$_GET['codeStructure'].'',
    'listeEleves'=>$listeEleves,
    'classe'=>''.$_GET['codeStructure'].'',
    'elevePrecedent'=>$elevePrecedent,
    'eleveSuivant'=>$eleveSuivant,
    'infosEleve'=>$infosEleve, //on passe un tab à 1 dimension
    'voeuxEleve'=>$voeuxEleve, //tableau de dimension 2 (1array par voeu)
    'avisProfesseurs'=>$avisProfesseurs
    
    ) ;
//var_dump($infosEleve);

$mergeVarTemplate = array_merge(
        $contenuIdentifiants,
        $variablesTemplate,
        $contenuArticle,
        $contenuMenu,
        $profilAdmin
        
        
        ); //construction du tableau avec les données à envoyer au template


appelTemplate($template, $twig, $mergeVarTemplate); //construction de la page web