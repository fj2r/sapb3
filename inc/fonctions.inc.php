<?php
/* 
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

function appelDatabase (){

    //////////////////récupération des infos de connexion via un json///////////
        $infos_connexion = file_get_contents('admin/config_bdd.json');
        $parsed_json = json_decode($infos_connexion);
        $contenuHote = $parsed_json->{'informations_base_de_donnee'}->{'identifiants_base'}->{'host'};
        $contenuDatabase = $parsed_json->{'informations_base_de_donnee'}->{'identifiants_base'}->{'nomdb'};
        $contenuUtilisateur = $parsed_json->{'informations_base_de_donnee'}->{'identifiants_base'}->{'login'};
        $contenuPasswd = $parsed_json->{'informations_base_de_donnee'}->{'identifiants_base'}->{'passwd'};
    ////////////////////////////////////////////////////////////////////////////
   

    $db = new lib\bdd($contenuHote, $contenuDatabase, $contenuUtilisateur, $contenuPasswd);  //bind de la connexion ) la base de donnée
    $PDO = $db->getPDO(); //récupération de l'objet PDO
    
        
    return $db; //on retourne l'objet base de donnee
}