<?php

/* 
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */


if (isset($phpsessid) && !empty($phpsessid) && !isset($_POST['passwd']) ){
    $admin->setLogin($login);
    $profilAdmin = $admin->profilAdmin();
    $admin->genererSession();
    
    
    
    $profilAdmin =array(
    "nom"=>''.$admin->getNom().'',
    "prenom"=>''.$admin->getPrenom().'',
    
    "id_admin"=>''.$admin->getIdAdmin().'',
    "civilite"=>''.$admin->getCivilite().'',
    
    
    
);
    
    $connecte = TRUE;
    
    
}
else {
$admin->setLogin($login);

$admin->setPasswordNonEncrypte($passwd);
$admin->setPasswordEncrypte($passwd); //encrypte à la volée le pass par hachage standard

$existenceProfil = $admin->existanceAdmin(); //récupération des infos sur la personne, s'il existe


if ($existenceProfil == TRUE){
    
    $profilAdmin = $admin->profilAdmin();
    $admin->genererSession();
    $admin->genererCookie();
    
    $listeClasses = $admin->listerClasses();
    
    $connecte = TRUE;
    $profilAdmin =array(
    "nom"=>''.$admin->getNom().'',
    "prenom"=>''.$admin->getPrenom().'',
    
    
    "id_admin"=>''.$admin->getIdAdmin().'',
    "civilite"=>''.$admin->getCivilite().'',
       
    
    );
    
}
else {
    $connecte = FALSE;
    header('Location:index.php');
}





}

