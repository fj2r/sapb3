<?php

/* 
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

////////////////////////////* Appel du header - Sessions*//////////////////////
include_once ('inc/headers.inc.php');
///////////////////////////////Appel des libraires  ////////////////////////////
include_once('inc/mainLib.inc.php');
include_once('inc/fonctions.inc.php');
////////////////////////////* Appel du moteur de templates Twig*////////////////
include_once ('inc/initTwig.inc.php');


////////////////////////////Construction du formulaire//////////////////////////

$tableauPourFormulaire = array (
    'nom'=>'Inscrivez votre nom ici',
    'prenom'=>'Inscrivez votre prénom ici',    
    'mail1'=>'Inscrivez ici votre adresse email',
    'mail2'=>'Retapez ici votre adresse email',);

$form = new lib\Formulaire($tableauPourFormulaire);





///////////////////////////////////// TWIG /////////////////////////////////////
$template = 'Formulaire';
$variablesTemplate = array(
    'titreForm'=>$form->titre='Formulaire d\'inscription : ',
    'lienSubmit'=>$form->submit='inscription.php',
    'arguments'=>$form->argumentsURL='?statut=eleve',
    'method'=>$form->method='POST',
    'nom'=>$form->input('nom'),
    'prenom'=>$form->input('prenom'),
    'jj'=>$form->select('jj'),
    'mm'=>$form->select('mm'),
    'aaaa'=>$form->select('aaaa'),
    'mail1'=>$form->input('mail1'),
    'mail2'=>$form->input('mail2'),
    'submit'=>$form->submit(),
    ) ;
appelTemplate($template, $twig, $variablesTemplate);