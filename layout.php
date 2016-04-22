<?php

////////////////////////////* Appel du moteur de templates Twig*////////////////
 
 include_once ('inc/initTwig.inc.php');
 //////////////////////////////////Fin de l'appel du moteur de template/////////


    
$template = $twig->loadTemplate('layout.twig'); //on va chercher le template associé
echo $template->render(array(
    
    )); //on envoie la variable au template


    
    
