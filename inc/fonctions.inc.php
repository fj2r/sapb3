<?php
/* 
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

function appelDatabase (){

    //////////////////récupération des infos de connexion via un json///////////
        $infos_connexion = file_get_contents('admin/config_bdd.json');
        $parsed_json = json_decode($infos_connexion);
        $contenuHote = $parsed_json->{'informations_base_de_donnee'}->{'identifiants_base'}->{'host'};
        $contenuDatabase = $parsed_json->{'informations_base_de_donnee'}->{'identifiants_base'}->{'nomdb'};
        $contenuUtilisateur = $parsed_json->{'informations_base_de_donnee'}->{'identifiants_base'}->{'login'};
        $contenuPasswd = $parsed_json->{'informations_base_de_donnee'}->{'identifiants_base'}->{'passwd'};
    ////////////////////////////////////////////////////////////////////////////
   

    $db = new lib\bdd($contenuHote, $contenuDatabase, $contenuUtilisateur, $contenuPasswd);  //bind de la connexion ) la base de donnée
    $PDO = $db->getPDO(); //récupération de l'objet PDO
    
        
    return $db; //on retourne l'objet base de donnee
}

function appelTemplate ($template, $twig, $variablesPasseesAuTemplate){
////////////////////////////Les variables à passer au template//////////////////    
    $template .= '.twig'; //pour compléter le nom de fichier du template
    
    

        $date = date('d/m/Y');
        
        $charset = "UTF-8";
        

        if (isset($_SESSION)){
        $connecte = TRUE;
            if (isset($_SESSION['prenom'])&& isset($_SESSION['nom'])&& 
                    isset($_SESSION['nom_application'])&&isset($_SESSION['version'])){
            $prenom=$_SESSION['prenom'];
            $nom=$_SESSION['nom'];
            $titrePage = $_SESSION['nom_application'];
            $version = $_SESSION['version']; 
            }
            else {
            $connecte= FALSE;
            $prenom = "titi";
            $nom = "toto";
            $titrePage= substr($template, 0, -5) ; //le titre de la page est par défaut le nom du template
            $version= "3.0";
            }

        }
        

        $lien_eleve = 'login.php?statut=eleve'; //liens sur la page d'accueil
        $lien_professeur = 'login.php?statut=professeur';
        $lien_administratif = 'login.php?statut=administratif';
        

        $texte_footer = 'Copyright LMN Autun'; /*pour le footer*/
///////////////////////////Fin des décla de variables pour le template//////////
 
$variablesCommunesTemplate =   array(
    'annee' => ''.$date.'',
    'version'=>''.$version.'',
    'charset'=>''.$charset.'',
    'titrePage'=>''.$titrePage.'',
    'connecte'=>''.$connecte.'',
    
    
    'texte_footer'=>''.$texte_footer.'',
    'lien_eleve'=>''.$lien_eleve.'',
    'lien_professeur'=>''.$lien_professeur.'',
    'lien_administratif'=>''.$lien_administratif.'',
    ); 

$tableauPourTemplate = array_merge($variablesCommunesTemplate,
        $variablesPasseesAuTemplate ); //on push le tableau passé en argument 


$template = $twig->loadTemplate($template); //on va chercher le template associé
echo $template->render($tableauPourTemplate); //on envoie le tableau des variables au template qui se débrouille avec

}