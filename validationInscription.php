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



///////////////////////////// Kernel        ///////////////////////////////////

$db = new lib\bdd();        //instance de la database
$pdo = $db->getPDO();       //instance d'un objet PDO pour les requetes

if (isset($_POST) && !empty($_POST)){
    
    $POSTechappe = new lib\TraitementFormulaire();
    $donneesFormulaire = $POSTechappe->echappementChaine($_POST); //traitement pour échapper les caractères si nécessaires en prétraitement
}
/*A NOTER IMPORTANT : Les données utilisateur ne sont pas vérifiés car tout est traité par PDO en requêtes préparées...donc PDO gère */
$statut = $_GET['statut'];
$nom    = $_POST['nom'];
$prenom = $_POST['prenom'];
$jj     = $_POST['jj'];
$mm     = $_POST['mm'];
$aaaa   = $_POST['aaaa'];
$mail1  = $_POST['mail1'];
$mail2  = $_POST['mail2'];
$dateDeNaissance = formaterDate($jj, $mm, $aaaa);

$eleve = new \lib\Eleve($db,$statut); //si on a toutes les infos sur l'élève alors on l'instancie

$infosEleve = $eleve->rechercheEleve($nom, $prenom, $dateDeNaissance); //et on récupère Toutes les infos sur lui dans un tableau

$eleve->setNom($infosEleve['Nom_de_famille']);
$eleve->setPrenom($infosEleve['Prénom']);
$eleve->setNaissance($infosEleve['Date_Naissance']);
$eleve->setNumEleveEtab($infosEleve['Num__Elève_Etab']);
$eleve->setIdEleve($infosEleve['id_eleve']);

$numDossier = $eleve->construireCodesEleve($infosEleve['id_eleve'], 5);

////////////////////////////Les variables communes à passer au template//////////////////
include_once ('inc/varTwig.inc.php');

////////////////////////////passage du tableau de variables pour template///////


///////////////éventuelle surcharge des variables pour le template ?//////////
$template = 'validationInscription';


$page = 'validationInscription';         //Nom de l'index pour récupérer les infos pour les textes
$contenuJSON = new lib\generateurArticle($page); //on instancie le générateur d'article 
$contenuArticle = $contenuJSON->lireContenu($page)[''.$page.''][0]; // méthode pour lire les infos du fichier de langue

/////////////////////////////////////////////////////////////

$variablesTemplate = array(
    'annee' => ''.$date.'',
    'version'=>''.$version.'',
    'charset'=>''.$charset.'',
    'titrePage'=>''.$titrePage.'',
    'connecte'=>''.$connecte.'',
    'sexe'=>''.$sexe.'',
    'texte_footer'=>''.$texte_footer.'',
    'bandeauLogin'=>''.bandeauLogin($statut).'',   
    'statut'=>''.$statut.'',
    'mail1'=>''.$mail1.'',
    'numDossier'=>''.$numDossier[0].'',
    'codeConf'=>''.$numDossier[1].'',
    ) ;

$mergeVarTemplate = array_merge($variablesTemplate, $contenuArticle, $infosEleve); //construction du tableau avec les données à envoyer au template



appelTemplate($template, $twig, $mergeVarTemplate); //construction de la page web