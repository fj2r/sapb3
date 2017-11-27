<?php

/* 
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

$champ ='academie'; $tri = 'academie';

$formEtab1 = $etablissement->formEtablissement($champ, $tri); 

$champ ='type'; $tri = 'type';

$formEtab2 = $etablissement->formEtablissement($champ, $tri);

$champ = 'secteur' ; $tri= 'secteur';

$formEtab3 = $etablissement->formBTS($champ, $tri);