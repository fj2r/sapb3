<?php
session_start();
//test sur l'environnement du serveur

$phpVersion = phpversion();
$magicQuotes  = get_magic_quotes_gpc();

//récupération des variables passées en get sur tout le site 

if (isset ($_GET) && !empty($_GET['statut'])){
    
    $statut = $_GET['statut']    ;
       
    
}

//récupération des variables stockées dans le(s) cookie(s)

if (isset ($_COOKIE) && !empty($_COOKIE['PHPSESSID'])){
    
   $phpsessid = $_COOKIE['PHPSESSID']; //pour le cookie de session
    
}



