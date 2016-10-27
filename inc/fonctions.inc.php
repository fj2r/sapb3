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

function bandeauLogin ($statut){
        if ($statut == 'eleve'){
            $tableauPourBandeau = array (
            'num_dossier'=>'N° de dossier',
            'code_conf'=>'Mot de passe',);
           

            $formBandeauEleve = new lib\Formulaire($tableauPourBandeau);
            
            $formBandeauEleve->surround = 'span';
            $formBandeauEleve->label = 'Connexion';
            $formBandeauEleve->size = 15 ;
            $formBandeauEleve->type = 'password' ;
            $httpReturn = $formBandeauEleve->input('num_dossier').$formBandeauEleve->input('code_conf').$formBandeauEleve->submit();
           
            return $httpReturn;
            
        }
        elseif ($statut == 'professeur'){
            $tableauPourBandeau = array (
            'login'=>'Votre login',
            'passwd'=>'Votre mot de passe',    
            );

            $formBandeauProf = new lib\Formulaire($tableauPourBandeau);
            
            $formBandeauProf->surround='span';
            $formBandeauProf->label = 'Connexion';
            $formBandeauProf->size = 20 ;
            $formBandeauProf->type = 'password' ;
            $httpReturn = $formBandeauProf->input('login').$formBandeauProf->input('passwd').$formBandeauProf->submit();
            
            return $httpReturn;
            
        }
        elseif ($statut == 'administratif'){
            $tableauPourBandeau = array (
            'login'=>'Votre login',
            'passwd'=>'Votre mot de passe',    
            );

            $formBandeauAdmin = new lib\Formulaire($tableauPourBandeau);
            
                        
            $formBandeauAdmin->surround='span';
            $formBandeauAdmin->label = 'Connexion';
            $formBandeauAdmin->size = 20 ;
            $formBandeauAdmin->type = 'password' ;
            $httpReturn = $formBandeauAdmin->input('login').$formBandeauAdmin->input('passwd').$formBandeauAdmin->submit();
            
             return $httpReturn;
        }

        else {return null;}

}



function formulaireInscription (){
    
    $tableauPourFormulaire = array (
    'nom'=>'Inscrivez votre nom ici',
    'prenom'=>'Inscrivez votre prénom ici',    
    'mail1'=>'Inscrivez ici votre adresse email',
    'mail2'=>'Retapez ici votre adresse email',);

    return $form = new lib\Formulaire($tableauPourFormulaire);
    
}

function formaterDate ($jj, $mm, $aaaa){
    $date_US="$jj-$mm-$aaaa";
    $ddn = date("d/m/Y", strtotime($date_US));
    return $ddn;
}

function identificationSessionPuisCookie () {
    if (!empty($_POST['code_conf']) && !empty($_POST['num_dossier'])){
    $eleve->setCodeConfidentiel($_SESSION['code_conf']);
    $eleve->setNumDossier($_SESSION['num_dossier']);
}
elseif(!empty($_COOKIE['code_conf']) && !empty($_COOKIE['num_dossier'])){
    $eleve->setCodeConfidentiel($_COOKIE['code_conf']);
    $eleve->setNumDossier($_COOKIE['num_dossier']);
}
else{
    $string = 'Location: login.php?statut=eleve';
        header($string);
}
}