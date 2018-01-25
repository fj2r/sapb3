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
    "num_eleve_etab"=>''.$eleve->getNumEleveEtab().''
    );
  
$nbVoeux  = intval($eleve->verifierVoeux()); //combien a-t-il de voeux ?


if ($nbVoeux >=$nbVoeuxMax){
    $message = 'Désolé mais vous avez atteint la limite maximale des voeux.';
    
}
else {
    
    $etablissement = new \lib\Etablissement($db);
    $listeEtab =array();
    if ($_POST['etab']){
        $data = htmlentities($_POST['etab']);
        $idEtab = $data;    
            $etablissement->setIdEtab($data);
            $etablissement->enregistrerVoeuStandard($data,$eleve->getId_eleve(),$eleve->getNumEleveEtab());

        if ($_POST['commentaire']){
        $commentaire = htmlentities($_POST['commentaire']);
            $etablissement->ajouterCommentaire($idEtab, $commentaire);
        }
    }
    
    else {
        header('Location:orientation.php?statut='.$statut);
    }
           
}



$listeVoeux = $eleve->recupererVoeux();

$etablissement = new \lib\Etablissement($db);   //pour récapituler les voeux


////////////////////////////Les variables communes à passer au template//////////////////
include_once ('inc/varTwig.inc.php');

////////////////////////////passage du tableau de variables pour template///////


///////////////éventuelle surcharge des variables pour le template ?//////////
$template = 'validationVoeux';


$page = 'validationVoeux';         //Nom de l'index pour récupérer les infos pour les textes
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
    'profil'=>$profilEleve,
   
    'texte_footer'=>''.$texte_footer.'',
    'bandeauLogin'=>''.bandeauLogin($statut).'',   
    'statut'=>''.$statut.'',
    
    'nbVoeux'=>''.$nbVoeux.'',
    'nbVoeuxMax'=>''.$nbVoeuxMax.'',
   
    
    ) ;

$mergeVarTemplate = array_merge(
        $contenuIdentifiants,
        $variablesTemplate,
        $contenuArticle,
        $contenuMenu,
        $profilEleve
        ); //construction du tableau avec les données à envoyer au template



appelTemplate($template, $twig, $mergeVarTemplate); //construction de la page web