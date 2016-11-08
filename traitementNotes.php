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

$listeProfesseurs = $eleve->listerProfesseurs(); // qui sont les professeurs de sa classe ? Renvoi un tableau de dimension 2

$notes = new \lib\Notes($db, $statut, $_GET['idEleve']);
$notes->setCodeStructure($eleve->getCodeStructure());
$notes->setIdEleve($_GET['idEleve']);
$notes->ecrireNotes($_POST);

$string='Location:notesEleve.php?statut='.$statut.'&idEleve='.$_GET['idEleve'];
header($string);

