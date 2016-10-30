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


$academie = $_POST['academie'];

$type = $_POST ['type'];

$db = new lib\bdd();  
$etablissement = new \lib\Etablissement($db);  

$enregistrement = '*';
$champ1='academie';
$champ2='type';
$values=array($_POST['academie'], $_POST['type']);
$champTri='nom';
$liste=$etablissement->listerEtablissement($enregistrement, $champ1, $champ2, $values, $champTri);

////////////////////////////Les variables communes à passer au template//////////////////
include_once ('inc/varTwig.inc.php');

////////////////////////////passage du tableau de variables pour template///////

///////////////éventuelle surcharge des variables pour le template ?//////////
$template = 'listeEtablissementsPourModif';     //Nom du template à appeler

$page = 'listeEtablissements';         //Nom de l'index pour récupérer les infos pour les textes
$contenuJSON = new lib\generateurArticle($page); //on instancie le générateur d'article 
$contenuArticle = $contenuJSON->lireContenu($page)[''.$page.''][0]; // méthode pour lire les infos du fichier de langue



/////////////////////////////////////////////////////////////

 $connecte = true ;
$variablesTemplate = array(
    'annee' => ''.$date.'',
    'version'=>''.$version.'',
    'charset'=>''.$charset.'',
    'titrePage'=>''.$titrePage.'',
    'connecte'=>''.$connecte.'',
    
    'texte_footer'=>''.$texte_footer.'',
    'bandeauLogin'=>''.bandeauLogin($statut).'', //pour la construction du bandeau 
    'statut'=>''.$statut.'',
    'liste'=>$liste,
    'statut'=>''.$_GET['statut'].'',
   'idVoeu'=>''.$_GET['idVoeu'].'',
   
    ) ;


$mergeVarTemplate = array_merge(
        
        $variablesTemplate,
        $contenuArticle
        
        ); //construction du tableau avec les données à envoyer au template


appelTemplate($template, $twig, $mergeVarTemplate); //construction de la page web