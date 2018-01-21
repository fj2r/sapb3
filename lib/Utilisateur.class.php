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
    protected $passwordEncrypte;
    protected $passwd;
    protected $maBDD;
    public $db;
    protected $pdo;
    protected $civilite;
    protected $nom;
    protected $prenom;
    protected $email;
    private   $salt = 'optiplex';
    
    const ELEVE = 'eleve';
    const PROFESSEUR = 'professeur';
    const ADMINISTRATIF = 'administratif';
    


    public function __construct($db, $statut='eleve') {
        $this->db = $db;
        $this->pdo = $db->getPDO();
        
        if (isset($statut)){
            $this->statut=$statut;
       }
       else{
           $this->statut = self::ELEVE ; //statut par défaut
       }
       
    }
   
    public function setLogin ($data){
        $this->login = $data;
    }
    public function setCivilite($data){
        $this->civilite = $data;
    }
    public function setNom($data){
        $this->nom = $data;
    }
    public function setPrenom($data){
        $this->prenom = $data;
    }
    public function setEmail($data){
        $this->email = $data;
    }
    public function setPasswordNonEncrypte ($data){
        $this->passwd = $data;
    }
    public function setPasswordEncrypte ($data){
        
        $this->passwordEncrypte = $this->encrypte($data);
    }
    
    
     public function getPasswordEncrypte(){
        return $this->passwordEncrypte;
    }
    public function getCivilite(){
        return $this->civilite;
    }
    public function getNom(){
        return $this->nom;
    }
    public function getPrenom(){
        return $this->prenom;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getLogin(){
        return $this->login;
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
 
    public function detruireSession(){
        $_SESSION = array ();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
                );
        }
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
    
    protected function encrypte ($password){
        
        return sha1($password.$this->salt); //on fait un hachage standard... le sel est mis de façon aléatoire.
    
    }
    protected function verifierPassword ($password, $hash) {
        
        if (sha1($password.$this->salt) == $hash){
            return TRUE;
        }
        else { return FALSE ; }
       
        
    }
    
    ////////////////////////////////////////////////////////////////////////////
    
    public function listerClasses (){
            $statement = "SELECT DISTINCT `Code Structure` FROM `import_eleve_complet`";
            $tabDatas = array ();
            return $tableau = $this->db->queryPDOPrepared($statement, $tabDatas);
    }
    
    public function listerTotaliteEleves (){
            $statement = "SELECT * FROM `import_eleve_complet`";
            $tabDatas = array ();
            return $tableau = $this->db->queryPDOPrepared($statement, $tabDatas);
    }
    public function listerParDivision($codeStructure){
            $statement = "SELECT * FROM `import_eleve_complet` WHERE `Code Structure`=?";
            $tabDatas = array ($codeStructure);
            return $tableau = $this->db->queryPDOPrepared($statement, $tabDatas);
    }
    
}

