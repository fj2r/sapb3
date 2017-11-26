<?php

/* 
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

$prof = new lib\Professeur($db, $statut); //création de l'instance professeur

if (isset($phpsessid) && !empty($phpsessid) && !isset($_POST['passwd']) ){
    $prof->setLogin($login);
    $profilProf = $prof->profilProf();
    
    $prof->genererSession();
    
    
    $profilProf =array(
    "nom"=>''.$prof->getNom().'',
    "prenom"=>''.$prof->getPrenom().'',
    "nomComplet"=>''.$prof->getNomComplet().'',
    "codeStructure"=>$prof->getCodeStructure(),
    "id_pedago"=>''.$prof->getIdPedago().'',
    "civilite"=>''.$prof->getCivilite().'',
    "matiere"=>$prof->getMatiere(),
    
    
);
    
    $connecte = TRUE;
    
    
}
else {
    
    $prof->setLogin($login);

    $prof->setPasswordNonEncrypte($passwd);
    $prof->setPasswordEncrypte($passwd); //encrypte à la volée le pass par hachage standard

    $existenceProfil = $prof->existanceProf(); //récupération des infos sur le prof, s'il existe


    if ($existenceProfil == TRUE){
    
    $profilProf = $prof->profilProf();
    
    $prof->genererSession();
    $prof->genererCookie();
    $connecte = TRUE;
    }
    else {
    $connecte = FALSE;
    }


    $profilProf =array(
    "nom"=>''.$prof->getNom().'',
    "prenom"=>''.$prof->getPrenom().'',
    "nomComplet"=>''.$prof->getNomComplet().'',
    "codeStructure"=>$prof->getCodeStructure(),
    "id_pedago"=>''.$prof->getIdPedago().'',
    "civilite"=>''.$prof->getCivilite().'',
    "matiere"=>$prof->getMatiere(),
    
    
);


}







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
      
    'texte_footer'=>''.$texte_footer.'',
    'bandeauLogin'=>''.bandeauLogin($statut).'', //pour la construction du bandeau 
    'statut'=>''.$statut.'',
    'profilProf'=>$profilProf,
    'classesProf'=>$prof->classesProf(),
    'matieres'=>$prof->matieresProf(),
    ) ;


$mergeVarTemplate = array_merge(
        $contenuIdentifiants,
        $variablesTemplate,
        $contenuArticle,
        $contenuMenu,
        $profilProf
        
        ); //construction du tableau avec les données à envoyer au template


appelTemplate($template, $twig, $mergeVarTemplate); //construction de la page web