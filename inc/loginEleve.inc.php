<?php

/* 
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

$eleve = new lib\Eleve($db, $statut); //création de l'élève

$eleve->setCodeConfidentiel($codeConf);
$eleve->setNumDossier($numDossier);

$existenceProfil = $eleve->profilEleve(); //récupération des infos sur l'élève


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
    "idEleve"=>''.$eleve->getId_eleve().'',
    "sexe"=>''.$eleve->getSexe().'',
    "numEleveEtab"=>''.$eleve->getNumEleveEtab().'',
    
);

$listeProfesseurs = $eleve->listerProfesseurs(); // qui sont les professeurs de sa classe ? Renvoi un tableau de dimension 2

$nbVoeux  = intval($eleve->verifierVoeux()); //combien a-t-il de voeux ?
$listeVoeux = $eleve->recupererVoeux();



////////////////////////////passage du tableau de variables pour template///////

///////////////éventuelle surcharge des variables pour le template ?//////////
$template = 'traitementLogin';     //Nom du template à appeler

$page = 'traitementLogin';         //Nom de l'index pour récupérer les infos pour les textes
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
    
    'sexe'=>''.$eleve->getSexe().'',
    'texte_footer'=>''.$texte_footer.'',
    'bandeauLogin'=>''.bandeauLogin($statut).'', //pour la construction du bandeau 
    'statut'=>''.$statut.'',
    'listeProfesseurs'=>$listeProfesseurs,
    'nbVoeuxMax'=>''.$nbVoeuxMax.'',
    'nbVoeux'=>''.$nbVoeux.'',
    'listeVoeux'=>$listeVoeux,
    ) ;


$mergeVarTemplate = array_merge(
        $contenuIdentifiants,
        $variablesTemplate,
        $contenuArticle,
        $contenuMenu,
        $profilEleve
        
        ); //construction du tableau avec les données à envoyer au template


appelTemplate($template, $twig, $mergeVarTemplate); //construction de la page web