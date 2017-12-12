<?php

/* 
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */


$template = 'mainTemplate';                                //Nom par défaut du template appelé                                                 
$statut = (isset($_GET['statut']) &&
        !empty($_GET['statut']))
        ? $_GET['statut']
        : $statut='eleve';                          // on assigne le statut par défaut à élève s'il n'est pas passé en paramètre
$date = date('d/m/Y');                              // la date du jour
$version = '3.1';                                   // la version de sapb
$charset = "UTF-8";                                 // le charset par défaut
$titrePage = 'simulation ParcourSup';               // le titre des fenêtres



/*pour le contenu des articles */

$contenuArticle = ':)';

/*pour le footer*/
$texte_footer = 'Copyright Lycée Militaire National Autun';
