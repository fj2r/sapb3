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

$connecte = gestionIdentification($eleve, $statut);        //gestion de l'identification (session & cookies)

$eleve->profilEleve();                //récupération des infos sur l'élève

$profilEleve =array(
    "nom"=>''.$eleve->getNom().'',
    "prenom"=>''.$eleve->getPrenom().'',
    "classe"=>''.$eleve->getLibStructure().'',
    "codeClasse"=>''.$eleve->getCodeStructure().'',
    "id"=>''.$eleve->getId_eleve().'',
    "sexe"=>''.$eleve->getSexe().'',
    
    );
  
$nbVoeux  = intval($eleve->verifierVoeux()); //combien a-t-il de voeux ?

$nbVoeux = 6;
if ($nbVoeux >=6){
    $message = 'Désolé mais vous avez atteint la limite maximale des voeux.';
    genererAlertBox($message);
    //header('Location:index.php');
}

$listeVoeux = $eleve->recupererVoeux();

$etablissement = new \lib\Etablissement($db);   //pour construire les formulaires de choix d'étab

//construction des foermulaires (mis en forme dans Twig
$champ ='academie'; $tri = 'academie';

$formEtab1 = $etablissement->formEtablissement($champ, $tri); 

$champ ='region'; $tri = 'region';

$formEtab2 = $etablissement->formEtablissement($champ, $tri);

$champ ='nom'; $tri = 'nom';
 
$formEtab3 = $etablissement->formEtablissement($champ, $tri);

