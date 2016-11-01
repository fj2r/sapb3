<?php

/*
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

namespace lib;

/**
 * Description of Professeur
 *
 * @author fred
 */
class Professeur extends Utilisateur {
    
    protected $statut;
    protected $login;
    protected $passwordEncrypte;
    protected $passwd;
    protected $civilite ;
    protected $nom ;
    protected $prenom;
    protected $nomComplet;
    protected $email;
    protected $codeStructure =array();
    protected $matiere = array();
    protected $id_pedago;
    
    public function __construct($db, $statut = 'professeur') {
        parent::__construct($db, $statut);
    }
    
        public function setLogin ($data){
        $this->login = $data;
    }
    public function setPasswordNonEncrypte ($data){
        $this->passwd = $data;
    }
    public function setPasswordEncrypte ($data){
        
        $this->passwordEncrypte = $this->encrypte($data);
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
      public function setNomComplet($data){
        $this->nomComplet = $data;
    }
      public function setEmail($data){
        $this->email = $data;
    }
      public function setIdPedago($data){
        $this->id_pedago = $data;
    }
      public function setCodeStructure($data){
        $this->codeStructure = $data;
    }
      public function setMatiere($data){
        $this->matiere = $data;
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
    public function getNomComplet(){
        return $this->nomComplet;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getLogin(){
        return $this->login;
    }
    public function getIdPedago(){
        return $this->id_pedago;
    }
    public function getCodeStructure(){
        return $this->codeStructure;
    }
    public function getMatiere(){
        return $this->matiere;
    }
    
    
    
    
    
    private function encrypte ($password){
        return password_hash($password, PASSWORD_DEFAULT); //on fait un hachage standard... le sel est mis de façon aléatoire.
    }
    private function verifierPassword ($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public function existanceProf(){
        
        
        $statement = "SELECT `password` FROM `identifiants_pedago` WHERE `login`=?  ";
        $tabDatas =array ($this->login);
        $requete = $this->db->queryPDOPrepared($statement, $tabDatas);
        
        $hash = $requete[0]['password'];
        
        if ($this->verifierPassword($this->passwd, $hash) == TRUE) {
            
            return TRUE;
        }
        else {
            return FALSE;
        }
        
    }
    
    public function profilProf (){
        $statement = "SELECT * FROM `identifiants_pedago` INNER JOIN `equipe_pedagogique` ON `identifiants_pedago`.`id_pedago` = `equipe_pedagogique`.`id_pedago` WHERE `identifiants_pedago`.`login`=?  ";
        $tabDatas =array ($this->login);
        $requete = $this->db->queryPDOPrepared($statement, $tabDatas);
        
        
        $this->id_pedago = $requete[0]['id_pedago'];
        $this->login = $requete[0]['login'];
        $this->passwordEncrypte = $requete[0]['password'];
        $this->civilite = $requete[0]['civilite'];
        $this->nom = $requete[0]['nom'];
        $this->prenom = $requete[0]['prenom'];
        $this->nomComplet = $requete[0]['nomComplet'];
        $this->email = $requete[0]['email'];
        
        /* Ses matières et ses classes ?? */
        $this->matiere = $this->matieresProf();
        $this->codeStructure = $this->classesProf();
        
       
        return $requete; //on retourne un seul tableau de dimension 2 contenant 3 tableaux...profil/matieres/codeStructure(classe + matiere associée)
    }
    public function matieresProf (){
        $statement = "SELECT DISTINCT `matiere` FROM `attribution_matieres` INNER JOIN `equipe_pedagogique` ON `attribution_matieres`.`nomComplet` = `equipe_pedagogique`.`nomComplet` WHERE `equipe_pedagogique`.`id_pedago`=?  ";
        $tabDatas =array ($this->id_pedago);
        $requete = $this->db->queryPDOPrepared($statement, $tabDatas);
        
        return $requete;
    }
    public function classesProf (){
        $statement = "SELECT DISTINCT `Code Structure`,`matiere` FROM `attribution_matieres` INNER JOIN `equipe_pedagogique` ON `attribution_matieres`.`nomComplet` = `equipe_pedagogique`.`nomComplet` WHERE `equipe_pedagogique`.`id_pedago`=?  ";
        $tabDatas =array ($this->id_pedago);
        $requete = $this->db->queryPDOPrepared($statement, $tabDatas);
        
        return $requete;
    }
    
    public function genererSession(){
        $_SESSION = array ();
        $_SESSION = array (
            'id_pedago' => ''.$this->id_pedago.'',
            'login' => ''.$this->login.'',
            'nom' => ''.$this->nom.'',
            'prenom' => ''.$this->prenom.'',
            'civilite' => ''.$this->civilite.'',
            'nomComplet' => ''.$this->nomComplet.'',
            'codeStructure' => $this->codeStructure,
            'matiere' => $this->matiere,
            'email' => ''.$this->email.'',
        );
        
     
        
    }
    
    public function genererCookie() {
       if ($this->statut == 'professeur'){
           
            $this->detruireCookie();
           
            setcookie('id_pedago',$this->id_pedago,time()+3600);
            setcookie('login',$this->login,time()+3600);
            setcookie('civilite',$this->civilite, time()+3600);
            setcookie('nom',$this->nom,time()+3600);
            setcookie('nomComplet',$this->nomComplet,time()+3600);
            setcookie('prenom',$this->prenom,time()+3600);            
            setcookie('email',$this->email, time()+3600);
       }
   }   
    public function detruireCookie() {
            setcookie('id_pedago');
            setcookie('login');
            setcookie('civilite');
            setcookie('nom');
            setcookie('nomComplet');
            setcookie('prenom');
                        setcookie('email');
            unset ($_COOKIE['id_pedago']);
            unset ($_COOKIE['login']);
            unset ($_COOKIE['civilite']);
            unset ($_COOKIE['nom']);
            unset ($_COOKIE['prenom']);
            unset ($_COOKIE['nomComplet']);
            unset ($_COOKIE['email']);
   }
    
}
