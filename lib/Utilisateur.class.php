<?php

/**
 * Description of utilisateur
 *
 * @author fred

 */
namespace lib;

class Utilisateur {
    
    protected $connexion;
    protected $statut;
    protected $login;
    protected $codeConf;
    protected $numDossier;
    protected $passwd;
    protected $maBDD;
    protected $pdo;
    
    const ELEVE = 'eleve';
    const PROFESSEUR = 'professeur';
    const ADMINISTRATIF = 'administratif';
    


    public function __construct($db, $statut='eleve') {
        
        $this->pdo = $db->getPDO();
        
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
    
    public function genererCookie() {
        if ($this->statut== self::ELEVE){
            setcookie('sapb_num_dossier','',time()+0);
            setcookie('sapb_code_conf','',time()+0);
            setcookie('sapb_num_eleve_etab','',time()+0);
            setcookie('sapb_nom_eleve','',time()+0);
            setcookie('sapb_prenom_eleve','',time()+0);
        }    
        elseif ($this->statut== self::PROFESSEUR) {
            setcookie('sapb_login','',time()+0);
            setcookie('sapb_passwd','',time()+0);
            setcookie('sapb_id_pedago','',time()+0);
            setcookie('sapb_nom_prof','',time()+0);
            setcookie('sapb_prenom_prof','',time()+0);
        }
        elseif ($this->statut== self::ADMINISTRATIF) {
            setcookie('sapb_login','',time()+0);
            setcookie('sapb_passwd','',time()+0);
            setcookie('sapb_nom_admin','',time()+0);
            setcookie('sapb_prenom_admin','',time()+0);
            setcookie('sapb_id_admin','',time()+0);
        }
    }
        
    
    
   /////////////////////////////Gestion des Sessions pour les Utilisateurs//////
 
    public function detruireSession () {
        $_SESSION = array();
        session_destroy();
    }
    
    public function ecrireSession ($infosSession) {
        
    }
    
    /////////////////////////////Login des utilisateurs/////////////////////////
    
    public function login ($statut, $identifiant1 , $identifiant2 ){
        
        if ($statut == 'eleve'){
            $statement = "SELECT * FROM `cfg_eleves` WHERE numDossier = ? AND codeConf = ?";
            $tabDatas = array ($identifiant1, $identifiant2);
            $tableau = $this->db->queryPDOPrepared($statement, $tabDatas);
            
            if ($tableau == FALSE){ return FALSE; }
            else {
                
                return $tableau;
                
            }
            
            
        }
        elseif ($statut == 'professeur'){
            $statement = "SELECT * FROM `identifiants_pedago` WHERE  login = ? AND password = ?";
            $tabDatas = array ($identifiant1, $identifiant2);
            $tableau = $this->db->queryPDOPrepared($statement, $tabDatas);
            
            if ($tableau == FALSE){ return FALSE; }
            else {
                
                return $tableau;
                
            }
        }
        elseif ($statut == 'administratif'){
            $statement = "SELECT * FROM `identifiants_administration` WHERE  login = ? AND password = ?";
            $tabDatas = array ($identifiant1, $identifiant2);
            $tableau = $this->db->queryPDOPrepared($statement, $tabDatas);
            
            if ($tableau == FALSE){ return FALSE; }
            else {
                
                return $tableau;
                
            }
        }
        
    }
    
    
    
}

