<?php

/* 
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */


if (isset($phpsessid) && !empty($phpsessid) ){
    
    
    $profilProf =array(
    "nom"=>''.$_SESSION['nom'].'',
    "prenom"=>''.$_SESSION['prenom'].'',
    "nomComplet"=>''.$_SESSION['nomComplet'].'',
    
    "id_pedago"=>''.$_SESSION['id_pedago'].'',
    "civilite"=>''.$_SESSION['civilite'].'',
        
    );
    
    
    $connecte = TRUE;
}
elseif (isset($_COOKIE) && !empty ($_COOKIE)){
    
    $profilProf =array(
    "nom"=>''.$_COOKIE['nom'].'',
    "prenom"=>''.$_COOKIE['prenom'].'',
    "nomComplet"=>''.$_COOKIE['nomComplet'].'',
    
    "id_pedago"=>''.$_COOKIE['id_pedago'].'',
    "civilite"=>''.$_COOKIE['civilite'].'',
        
    );
    
    
    $connecte = TRUE;
}
else {
        
        $connecte = FALSE;

        
}
