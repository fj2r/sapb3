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
$db = new lib\bdd();            //instance de la database

$eleve = new lib\Eleve($db, $statut); //création de l'élève

$eleve->setCodeConfidentiel($codeConf);
$eleve->setNumDossier($numDossier);

$existenceProfil = $eleve->profilEleve(); //récupération des infos sur l'élève


if ($existenceProfil == TRUE){
    //$eleve->genererSession();
   // $eleve->genererCookie();
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
    "numEleveEtab"=>''.$eleve->getNumEleveEtab().'',
    
);

$nbVoeux  = intval($eleve->verifierVoeux()); //combien a-t-il de voeux ?

$idVoeu = ($_GET['idVoeu']);

$listeVoeux = $eleve->recupererVoeuAModifier($idVoeu);


        
$etablissement = new \lib\Etablissement($db);   //pour construire les formulaires de choix d'étab
$etablissement->setClassement($listeVoeux[0]['classement']);




if ($_POST['etab']){
    $nouvelEtab = ($_POST['etab']);
    
    $etablissement->modifierEtablissement($idVoeu,$nouvelEtab);

    if ($_POST['commentaire'] && $_POST['commentaire'] != ""){
        $commentaire = htmlentities($_POST['commentaire']);
        $etablissement->modifierCommentaire($idVoeu, $commentaire);

    }
}



header('Location:modificationVoeu.php?statut='.$statut.'&idVoeu='.$idVoeu);