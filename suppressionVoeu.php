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

//$listeProfesseurs = $eleve->listerProfesseurs(); // qui sont les professeurs de sa classe ? Renvoi un tableau de dimension 2

//$nbVoeux  = intval($eleve->verifierVoeux()); //combien a-t-il de voeux ?
//$listeVoeux = $eleve->recupererVoeux();


$etablissement = new \lib\Etablissement($db);   //pour construire les formulaires de choix d'étab

if (isset ($_GET)){
    $idVoeu = $_GET['idVoeu'];
    $resultatSuppression = $etablissement->supprimerVoeu($idVoeu);
    
    header('Location:traitementLogin.php?statut='.$statut);
}

