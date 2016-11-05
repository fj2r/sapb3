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
    
    
    $connecte = TRUE;
}
else {
    $connecte = FALSE;
}

$profilProf =array(
    "nom"=>''.$_SESSION['nom'].'',
    "prenom"=>''.$_SESSION['prenom'].'',
    "nomComplet"=>''.$_SESSION['nomComplet'].'',
    "codeStructure"=>$_SESSION['codeStructure'],
    "id_pedago"=>''.$_SESSION['id_pedago'].'',
    "civilite"=>''.$_SESSION['civilite'].'',
    "matiere"=>$_SESSION['matiere'],
    
    
);
$prof->setIdPedago($_SESSION['id_pedago']); // penser à passer l'id à l'objet...




$division = new lib\Division($db, $_GET['codeStructure']); //on instancie pour avoir des infos sur la classe et lister les élèves

$listeEleves = $division->listerEleves(); //c'est un tableau à 1 dimension

$eleve = new \lib\Eleve($db, 'eleve');      //mais qui est donc cet élève ??

$eleve->setIdEleve($_GET['idEleve']);
$infosEleve = $eleve->informationsEleve();  // et hop on sait tout de lui
$voeuxEleve = $eleve->recupererVoeux();     // et on a tous ses voeux

$avisProfDejaEmis = $prof->recupererCommentairesVoeux($voeuxEleve); //pour chaque voeu le prof a peut-être déja émis un avis ?
        
$elevePrecedent = $division->elevePrecedent($eleve->getCodeStructure(), $eleve->getNom(), $eleve->getPrenom())[0];
$eleveSuivant = $division->eleveSuivant($eleve->getCodeStructure(), $eleve->getNom(), $eleve->getPrenom())[0];

////////////////////////////passage du tableau de variables pour template///////

///////////////éventuelle surcharge des variables pour le template ?//////////
$template = 'avisProfesseur';     //Nom du template à appeler

$page = 'avisProfesseur';         //Nom de l'index pour récupérer les infos pour les textes
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
    'profilProf'=>$profilProf,
    'classeSelectionnee'=>''.$_GET['codeStructure'].'',
    'listeEleves'=>$listeEleves,
    'classe'=>''.$_GET['codeStructure'].'',
    'elevePrecedent'=>$elevePrecedent,
    'eleveSuivant'=>$eleveSuivant,
    'infosEleve'=>$infosEleve, //on passe un tab à 1 dimension
    'voeuxEleve'=>$voeuxEleve, //tableau de dimension 2 (1array par voeu)
    'avisProf'=>$avisProfDejaEmis,
    ) ;
//var_dump($infosEleve);

$mergeVarTemplate = array_merge(
        $contenuIdentifiants,
        $variablesTemplate,
        $contenuArticle,
        $contenuMenu,
        $profilProf
        
        ); //construction du tableau avec les données à envoyer au template


appelTemplate($template, $twig, $mergeVarTemplate); //construction de la page web