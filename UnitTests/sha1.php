<?php

/* 
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

$passwd = htmlentities($_GET['passwd']);
$salt = 'optiplex';

$hash = sha1($passwd.$salt);

echo $hash;


?>
