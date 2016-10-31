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
            $formBandeauEleve->label = 'Numéro de dossier' ;
            $formBandeauEleve->size = 10 ;
            $formBandeauEleve->type = 'text' ;
            $httpReturn = $formBandeauEleve->input('num_dossier');
           
            $formBandeauEleve->surround = 'span';
            $formBandeauEleve->label = 'Code confidentiel' ;
            $formBandeauEleve->size = 10 ;
            $formBandeauEleve->type = 'password' ;
            $httpReturn .=$formBandeauEleve->input('code_conf');
            
            $formBandeauEleve->label = 'Connexion' ;
            $httpReturn .=$formBandeauEleve->submit();
            
            return $httpReturn;
            
        }
        elseif ($statut == 'professeur'){
            $tableauPourBandeau = array (
            'login'=>'Votre login',
            'passwd'=>'Votre mot de passe',    
            );

            $formBandeauProf = new lib\Formulaire($tableauPourBandeau);
            
            $formBandeauProf->surround='span';
            $formBandeauProf->label = 'Nom d\'utilisateur';
            $formBandeauProf->size = 20 ;
            $formBandeauProf->type = 'text' ;
            $httpReturn = $formBandeauProf->input('login');
            
            $formBandeauProf->surround='span';
            $formBandeauProf->label = 'Mot de passe';
            $formBandeauProf->size = 20 ;
            $formBandeauProf->type = 'password' ;
          
            $httpReturn .= $formBandeauProf->input('passwd');
            
            $formBandeauProf->label = 'Connexion';
            $httpReturn .= $formBandeauProf->submit();
            return $httpReturn;
            
        }
        elseif ($statut == 'administratif'){
            $tableauPourBandeau = array (
            'login'=>'Votre login',
            'passwd'=>'Votre mot de passe',    
            );

            $formBandeauAdmin = new lib\Formulaire($tableauPourBandeau);
            
                        
            $formBandeauAdmin->surround='span';
            $formBandeauAdmin->label = $formBandeauAdmin->label;
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

function gestionIdentification ($user, $statut) {
    
    if($statut == "eleve"){
        if (isset($_SESSION['code_conf']) && isset($_SESSION['num_dossier']) 
            && ($_SESSION['code_conf'] !=NULL) && ($_SESSION['num_dossier'] !=NULL)){
            
            $user->setCodeConfidentiel($_SESSION['code_conf']);

            $user->setNumDossier($_SESSION['num_dossier']);
            $user->setIdEleve($_SESSION['id_eleve']);

            $connecte = true ;
        }
        elseif(isset($_COOKIE['code_conf']) && isset($_COOKIE['num_dossier']) && !empty($_COOKIE['code_conf']) && !empty($_COOKIE['num_dossier'])){
                $user->setCodeConfidentiel($_COOKIE['code_conf']);
                $user->setNumDossier($_COOKIE['num_dossier']);
                $user->setIdEleve($_COOKIE['id_eleve']);

                $connecte = true ;
            }
        else{
                $connecte = false ;
            }  
    
        return $connecte;
    }
    
    elseif ($statut == "professeur") {
        if (isset($_SESSION['code_conf']) && isset($_SESSION['num_dossier']) 
                && ($_SESSION['code_conf'] !=NULL) && ($_SESSION['num_dossier'] !=NULL)){
                $user->setCodeConfidentiel($_SESSION['code_conf']);

                $user->setNumDossier($_SESSION['num_dossier']);
                $user->setIdPedago($_SESSION['id_eleve']);

                $connecte = true ;
            }
            elseif(isset($_COOKIE['code_conf']) && isset($_COOKIE['num_dossier']) && !empty($_COOKIE['code_conf']) && !empty($_COOKIE['num_dossier'])){
                    $user->setCodeConfidentiel($_COOKIE['code_conf']);
                    $user->setNumDossier($_COOKIE['num_dossier']);
                    $user->setIdEleve($_COOKIE['id_eleve']);

                    $connecte = true ;
                }
            else{
                    $connecte = false ;
                }  

            return $connecte;
    }
    elseif ($statut == "administratif"){
            if (isset($_SESSION['code_conf']) && isset($_SESSION['num_dossier']) 
            && ($_SESSION['code_conf'] !=NULL) && ($_SESSION['num_dossier'] !=NULL)){
            $user->setCodeConfidentiel($_SESSION['code_conf']);

            $user->setNumDossier($_SESSION['num_dossier']);
            $user->setIdAdmin($_SESSION['id_eleve']);

            $connecte = true ;
            }
            elseif(isset($_COOKIE['code_conf']) && isset($_COOKIE['num_dossier']) && !empty($_COOKIE['code_conf']) && !empty($_COOKIE['num_dossier'])){
                $user->setCodeConfidentiel($_COOKIE['code_conf']);
                $user->setNumDossier($_COOKIE['num_dossier']);
                $user->setIdEleve($_COOKIE['id_eleve']);

                $connecte = true ;
            }
            else{
                $connecte = false ;
            }  
    
        return $connecte;
    }
    
    else { 
        return $connecte = false;
    }
}

function genererAlertBox ($message){
    echo '<script language=javascript> function genererAlertBox(){alert('.$message.')}</script><body onload="genererAlertBox()">';
}