<?php

include_once ('inc/headers.inc.php');

$recherche = $_GET['noeud'];
$fichierXML = 'datas/etablissement_superieur.xml';


$etablissement = new etablissement($maBase);

$etablissement->setDom();
$etablissement->lireXML($fichierXML);
$etablissement->afficherEtablissement($recherche);
//$etablissement->exportEtablissementBdd();


?>