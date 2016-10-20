<?php

/**
 * Description of utilisateur
 *
 * @author fred

 */
class Utilisateur {
    
    protected $connexion;
    protected $statut;
    protected $login;
    protected $passwd;


    public function __construct() {
       if (isset($statut)){
            $this->statut=$statut;
       }
       else{
           $this->statut = 'eleve'; //statut par défaut
       }
       
    }
   ///////////////////////*Gestion des cookies*//////////////////////////////
    public function detruireCookie() {
        if ($statut=='eleve'){
            setcookie('sapb_num_dossier','',time()-3600*3);
            setcookie('sapb_code_conf','',time()-3600*3);
            setcookie('sapb_num_eleve_etab','',time()-3600*3);
            setcookie('sapb_nom_eleve','',time()-3600*3);
            setcookie('sapb_prenom_eleve','',time()-3600*3);
        }    
        elseif ($statut=='prof') {
            setcookie('sapb_login','',time()-3600*3);
            setcookie('sapb_passwd','',time()-3600*3);
            setcookie('sapb_id_pedago','',time()-3600*3);
            setcookie('sapb_nom_prof','',time()-3600*3);
            setcookie('sapb_prenom_prof','',time()-3600*3);
        }
        elseif ($statut=='admin') {
            setcookie('sapb_login','',time()-3600*3);
            setcookie('sapb_passwd','',time()-3600*3);
            setcookie('sapb_nom_admin','',time()-3600*3);
            setcookie('sapb_prenom_admin','',time()-3600*3);
            setcookie('sapb_id_admin','',time()-3600*3);
        }
        
    }
        
   public function genererCookie() {
       
   }    
    
   /////////////////////////////Gestion des Sessions pour les Utilisateurs//////
 
    public function detruireSession () {
        $_SESSION = array();
        session_destroy();
    }
    
    
    
    
    
}

