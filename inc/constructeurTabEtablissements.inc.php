<?php

/* 
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

$enregistrement = '*';
$champ0='formation';
$champ1='academie';
$champ2='type';
$values=array($_POST['academie'], $_POST['type']);
$values_CPGE=array($_POST['academie'], $_POST['type']);

$champTri='nom';
$liste=$etablissement->listerEtablissement($enregistrement, $champ1, $champ2, $values, $champTri);
$CPGE=$etablissement->listerCPGE($enregistrement,$champ0, $champ1, $champ2,$values_CPGE, $champTri);

$values_BTS=array($_POST['secteur']);
$champ0='secteur';
$champTri='type';
$BTS=$etablissement->listerBTS($enregistrement,$champ0, $values_BTS, $champTri);