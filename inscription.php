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


///////////////////////////// modèle //////////////////////////////////////////

$db = new lib\bdd();   
$eleve = new lib\Eleve($db);


$connecte = gestionIdentification($eleve, $statut);  
$eleve->profilEleve();                //récupération des infos sur l'élève

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

////////////////////////////Construction du formulaire//////////////////////////

$form = formulaireInscription();
$form->surround = 'span';
$form->label = 'Valider';
///////////////éventuelle surcharge des variables pour le template ?//////////
$template = 'inscription';


$page = 'inscription';         //Nom de l'index pour récupérer les infos pour les textes
$contenuJSON = new lib\generateurArticle($page); //on instancie le générateur d'article 
$contenuArticle = $contenuJSON->lireContenu($page)[''.$page.''][0]; // méthode pour lire les infos du fichier de langue

$pageIdentifiants = 'identifiants';                //Nom de l'index pour récupérer les infos pour les menus du bandeau
$contenuJSONidentifiants = new lib\generateurArticle($pageIdentifiants);
$contenuIdentifiants = $contenuJSONidentifiants->lireContenu($pageIdentifiants)[''.$pageIdentifiants.''][0];

$pageMenu = 'menus';                //Nom de l'index pour récupérer les infos pour les menus du bandeau
$contenuJSONMenu = new lib\generateurArticle($pageMenu);
$contenuMenu = $contenuJSONMenu->lireContenu($pageMenu)[''.$pageMenu.''][0];
/////////////////////////////////////////////////////////////

$variablesTemplate = array(
    'annee' => ''.$date.'',
    'version'=>''.$version.'',
    'charset'=>''.$charset.'',
    'titrePage'=>''.$titrePage.'',
    'connecte'=>''.$connecte.'',
    
    'texte_footer'=>''.$texte_footer.'',
    'bandeauLogin'=>''.bandeauLogin($statut).'',   
    'titreForm'=>$form->titre='Formulaire d\'inscription : ',
    'lienSubmit'=>$form->submit='validationInscription.php',
    'arguments'=>$form->argumentsURL='?statut=eleve',
    'method'=>$form->method='POST',
    'nom'=>$form->input('nom'),
    'prenom'=>$form->input('prenom'),
    'jj'=>$form->select('jj'),
    'mm'=>$form->select('mm'),
    'aaaa'=>$form->select('aaaa'),
    'mail1'=>$form->input('mail1'),
    'mail2'=>$form->input('mail2'),
    'submit'=>$form->submit(),
    ) ;

$mergeVarTemplate = array_merge($variablesTemplate, $contenuArticle); //construction du tableau avec les données à envoyer au template


appelTemplate($template, $twig, $mergeVarTemplate); //construction de la page web