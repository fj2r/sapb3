<?php
session_start();
//test sur l'environnement du serveur

$phpVersion = phpversion();
$magicQuotes  = get_magic_quotes_gpc();

//récupération des variables passés en session

if (isset ($_SESSION)){
    
    
    
}

//récupération des variables stockées dans le cookie

if (isset ($_COOKIE)){
    
   $phpsessid = $_COOKIE['PHPSESSID'];
    
}


//récupérations des variables passées en post

if (isset ($_POST)){
    
    ;
}

//récupération des variables passées en get

if (isset ($_GET)){
    
    ;
    
}