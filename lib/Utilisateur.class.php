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
    protected $maBDD;
    
    const ELEVE = 'eleve';
    const PROFESSEUR = 'professeur';
    const ADMINISTRATIF = 'administratif';
    


    public function __construct($statut) {
             
        if (isset($statut)){
            $this->statut=$statut;
       }
       else{
           $this->statut = self::ELEVE ; //statut par défaut
       }
       
    }
   ///////////////////////*Gestion des cookies*//////////////////////////////
    public function detruireCookie() {
        if ($this->statut== self::ELEVE){
            setcookie('sapb_num_dossier','',time()-3600*3);
            setcookie('sapb_code_conf','',time()-3600*3);
            setcookie('sapb_num_eleve_etab','',time()-3600*3);
            setcookie('sapb_nom_eleve','',time()-3600*3);
            setcookie('sapb_prenom_eleve','',time()-3600*3);
        }    
        elseif ($this->statut== self::PROFESSEUR) {
            setcookie('sapb_login','',time()-3600*3);
            setcookie('sapb_passwd','',time()-3600*3);
            setcookie('sapb_id_pedago','',time()-3600*3);
            setcookie('sapb_nom_prof','',time()-3600*3);
            setcookie('sapb_prenom_prof','',time()-3600*3);
        }
        elseif ($this->statut== self::ADMINISTRATIF) {
            setcookie('sapb_login','',time()-3600*3);
            setcookie('sapb_passwd','',time()-3600*3);
            setcookie('sapb_nom_admin','',time()-3600*3);
            setcookie('sapb_prenom_admin','',time()-3600*3);
            setcookie('sapb_id_admin','',time()-3600*3);
        }
        
    }
        
    
    
   /////////////////////////////Gestion des Sessions pour les Utilisateurs//////
 
    public function detruireSession () {
        $_SESSION = array();
        session_destroy();
    }
    
    
    
    
    
}

