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
    if ($statut == 'eleve'){
        if(isset ($_SESSION) && !empty ($_SESSION['code_conf'])){
            $codeConf = $_SESSION['code_conf']; //pour le cookie de profil
        }
        elseif(isset ($_POST) && !empty ($_POST['code_conf'])){
            $codeConf = $_POST['code_conf']; //pour le cookie de profil
        }
        else{
            $codeConf=NULL;
        }

        if (isset ($_COOKIE) && !empty($_COOKIE['num_dossier'])){

           $numDossier = $_COOKIE['num_dossier']; //pour le cookie de profil

        }
        elseif(isset ($_SESSION) && !empty ($_SESSION['num_dossier'])){
            $numDossier = $_SESSION['num_dossier']; //pour le cookie de profil
        }
        elseif(isset ($_POST) && !empty ($_POST['num_dossier'])){
            $numDossier = $_POST['num_dossier']; //pour le cookie de profil
        }
        else {
            $numDossier=NULL;
        }
    }
    elseif($statut=='professeur'){
        if(isset ($_SESSION) && !empty ($_SESSION['passwd'])){
            $passwd = $_SESSION['passwd']; //pour le cookie de profil
        }
        elseif(isset ($_POST) && !empty ($_POST['passwd'])){
            $passwd = $_POST['passwd']; //pour le cookie de profil
            
        }
        else{
            $passwd=NULL;
        }

        if (isset ($_COOKIE) && !empty($_COOKIE['login'])){

           $login = $_COOKIE['login']; //pour le cookie de profil

        }
        elseif(isset ($_SESSION) && !empty ($_SESSION['login'])){
            $login = $_SESSION['login']; //pour le cookie de profil
        }
        elseif(isset ($_POST) && !empty ($_POST['login'])){
            $login = $_POST['login']; //pour le cookie de profil
        }
        else {
            $login=NULL;
        }
    }
    elseif($statut =='administratif'){
            if(isset ($_SESSION) && !empty ($_SESSION['passwd'])){
            $passwd = $_SESSION['passwd']; //pour le cookie de profil
        }
        elseif(isset ($_POST) && !empty ($_POST['passwd'])){
            $passwd = $_POST['passwd']; //pour le cookie de profil
            
        }
        else{
            $passwd=NULL;
        }

        if (isset ($_COOKIE) && !empty($_COOKIE['login'])){

           $login = $_COOKIE['login']; //pour le cookie de profil

        }
        elseif(isset ($_SESSION) && !empty ($_SESSION['login'])){
            $login = $_SESSION['login']; //pour le cookie de profil
        }
        elseif(isset ($_POST) && !empty ($_POST['login'])){
            $login = $_POST['login']; //pour le cookie de profil
        }
        else {
            $login=NULL;
        }
    }
   
    
    
}
else{
    
    //header('Location:index.php');
}

//récupération des variables stockées dans le(s) cookie(s)

/*pour la session*/
if (isset ($_COOKIE) && !empty($_COOKIE['PHPSESSID'])){
    
   $phpsessid = $_COOKIE['PHPSESSID']; //pour le cookie de session
    
}
if (isset ($_COOKIE) && !empty($_COOKIE['PHPSESSID'])){
    
   $phpsessid = $_COOKIE['PHPSESSID']; //pour le cookie de session
    
}

//récupération des informations de configuration et de personnalisation

$infoSite = new \lib\infos();

$tableauInfosSite = $infoSite->getConfig();

$nomSite =  $tableauInfosSite['nomSite']; 
$nbVoeuxMax = $tableauInfosSite['nbVoeuxMax']; //nombre maximal de voeux permis
