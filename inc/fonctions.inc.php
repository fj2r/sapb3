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
            $formBandeauAdmin->label = 'Nom d\'utilisateur';
            $formBandeauAdmin->size = 20 ;
            $formBandeauAdmin->type = 'text' ;
            $httpReturn = $formBandeauAdmin->input('login');
            
            $formBandeauAdmin->surround='span';
            $formBandeauAdmin->label = 'Mot de passe';
            $formBandeauAdmin->size = 20 ;
            $formBandeauAdmin->type = 'password' ;
          
            $httpReturn .= $formBandeauAdmin->input('passwd');
            $formBandeauAdmin->label = 'Connexion';
            $httpReturn .= $formBandeauAdmin->submit();
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

            $connecte = TRUE ;
        }
        elseif(isset($_COOKIE['code_conf']) && isset($_COOKIE['num_dossier']) && !empty($_COOKIE['code_conf']) && !empty($_COOKIE['num_dossier'])){
                $user->setCodeConfidentiel($_COOKIE['code_conf']);
                $user->setNumDossier($_COOKIE['num_dossier']);
                $user->setIdEleve($_COOKIE['id_eleve']);

                $connecte = TRUE ;
            }
        else{
                $connecte = FALSE ;
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

function VerifierConnexion (){
    
}

function identificationProf ($prof, $nomTemplate, $phpsessid, $login){
        

        if (isset($phpsessid) && !empty($phpsessid) && !isset($_POST['passwd']) ){

            $prof->setLogin($login);

            $profilProf = $prof->profilProf();

            $prof->genererSession();


            $profilProf =array(
            "nom"=>''.$prof->getNom().'',
            "prenom"=>''.$prof->getPrenom().'',
            "nomComplet"=>''.$prof->getNomComplet().'',
            "codeStructure"=>$prof->getCodeStructure(),
            "id_pedago"=>''.$prof->getIdPedago().'',
            "civilite"=>''.$prof->getCivilite().'',
            "matiere"=>$prof->getMatiere(),


        );


            $connecte = TRUE;


        ////////////////////////////passage du tableau de variables pour template///////

        ///////////////éventuelle surcharge des variables pour le template ?//////////
        $template = $nomTemplate;     //Nom du template à appeler

        $page = $nomTemplate;         //Nom de l'index pour récupérer les infos pour les textes
        $contenuJSON = new lib\generateurArticle($page); //on instancie le générateur d'article 
        $contenuArticle = $contenuJSON->lireContenu($page)[''.$page.''][0]; // méthode pour lire les infos du fichier de langue

        $pageIdentifiants = 'identifiants';                //Nom de l'index pour récupérer les infos pour les menus du bandeau
        $contenuJSONidentifiants = new lib\generateurArticle($pageIdentifiants);
        $contenuIdentifiants = $contenuJSONidentifiants->lireContenu($pageIdentifiants)[''.$pageIdentifiants.''][0];

        $pageMenu = 'menus';                //Nom de l'index pour récupérer les infos pour les menus du bandeau
        $contenuJSONMenu = new lib\generateurArticle($pageMenu);
        $contenuMenu = $contenuJSONMenu->lireContenu($pageMenu)[''.$pageMenu.''][0];

        /////////////////////////////////////////////////////////////


        $variablesTemplate = array('annee' => ''.$date.'',
            'version'=>''.$version.'',
            'charset'=>''.$charset.'',
            'titrePage'=>''.$titrePage.'',
            'connecte'=>''.$connecte.'',

            'texte_footer'=>''.$texte_footer.'',
            'bandeauLogin'=>''.bandeauLogin($statut).'', //pour la construction du bandeau 
            'statut'=>''.$statut.'',
            'profilProf'=>$profilProf,
            'classesProf'=>$prof->classesProf(),
            'matieres'=>$prof->matieresProf(),
            ) ;


        $mergeVarTemplate = array_merge(
                $contenuIdentifiants,
                $variablesTemplate,
                $contenuArticle,
                $contenuMenu,
                $profilProf

                ); //construction du tableau avec les données à envoyer au template


        appelTemplate($template, $twig, $mergeVarTemplate); //construction de la page web


        }
        else {

            $prof->setLogin($login);

            $prof->setPasswordNonEncrypte($passwd);
            $prof->setPasswordEncrypte($passwd); //encrypte à la volée le pass par hachage standard

            $existenceProfil = $prof->existanceProf(); //récupération des infos sur le prof, s'il existe


            if ($existenceProfil == TRUE){

                $profilProf = $prof->profilProf();

                $prof->genererSession();
                $prof->genererCookie();
                $connecte = TRUE;

                $profilProf =array(
                "nom"=>''.$prof->getNom().'',
                "prenom"=>''.$prof->getPrenom().'',
                "nomComplet"=>''.$prof->getNomComplet().'',
                "codeStructure"=>$prof->getCodeStructure(),
                "id_pedago"=>''.$prof->getIdPedago().'',
                "civilite"=>''.$prof->getCivilite().'',
                "matiere"=>$prof->getMatiere(),


                );



        ////////////////////////////passage du tableau de variables pour template///////

        ///////////////éventuelle surcharge des variables pour le template ?//////////
        $template = $nomTemplate;     //Nom du template à appeler

        $page = $nomTemplate;         //Nom de l'index pour récupérer les infos pour les textes
        $contenuJSON = new lib\generateurArticle($page); //on instancie le générateur d'article 
        $contenuArticle = $contenuJSON->lireContenu($page)[''.$page.''][0]; // méthode pour lire les infos du fichier de langue

        $pageIdentifiants = 'identifiants';                //Nom de l'index pour récupérer les infos pour les menus du bandeau
        $contenuJSONidentifiants = new lib\generateurArticle($pageIdentifiants);
        $contenuIdentifiants = $contenuJSONidentifiants->lireContenu($pageIdentifiants)[''.$pageIdentifiants.''][0];

        $pageMenu = 'menus';                //Nom de l'index pour récupérer les infos pour les menus du bandeau
        $contenuJSONMenu = new lib\generateurArticle($pageMenu);
        $contenuMenu = $contenuJSONMenu->lireContenu($pageMenu)[''.$pageMenu.''][0];

        /////////////////////////////////////////////////////////////


        $variablesTemplate = array('annee' => ''.$date.'',
            'version'=>''.$version.'',
            'charset'=>''.$charset.'',
            'titrePage'=>''.$titrePage.'',
            'connecte'=>''.$connecte.'',

            'texte_footer'=>''.$texte_footer.'',
            'bandeauLogin'=>''.bandeauLogin($statut).'', //pour la construction du bandeau 
            'statut'=>''.$statut.'',
            'profilProf'=>$profilProf,
            'classesProf'=>$prof->classesProf(),
            'matieres'=>$prof->matieresProf(),
            ) ;


        $mergeVarTemplate = array_merge(
                $contenuIdentifiants,
                $variablesTemplate,
                $contenuArticle,
                $contenuMenu,
                $profilProf

                ); //construction du tableau avec les données à envoyer au template


        appelTemplate($template, $twig, $mergeVarTemplate); //construction de la page web


            }

            else {

            $connecte = FALSE;
            header('Location:index.php');
            }





        }


}
