<?php
session_start();

//
include_once('inc/mainLib.inc.php');
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

//récupération des informations de configuration et de personnalisation

$infoSite = new \lib\infos();

$tableauInfosSite = $infoSite->getConfig();

$nomSite =  $tableauInfosSite['nomSite']; 
$nbVoeuxMax = $tableauInfosSite['nbVoeuxMax']; //nombre maximal de voeux permis
