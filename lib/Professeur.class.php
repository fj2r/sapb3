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
    
   
    
    
      public function setNomComplet($data){
        $this->nomComplet = $data;
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
    
   
    public function getNomComplet(){
        return $this->nomComplet;
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
    
   public function recupererCommentairesVoeux($voeuxEleve){
       $resultat = array();
       
       foreach ($voeuxEleve as $idVoeu ){
           
            $statement = "SELECT * FROM `analyse_voeux` WHERE `analyse_voeux`.`id_voeu`= ? AND `analyse_voeux`.`id_pedago`= ? ";
            $tabDatas =array ($idVoeu['id_voeu'], $this->id_pedago);
            $requete = $this->db->queryPDOPrepared($statement, $tabDatas);
            
            if ($requete != NULL){
                $resultat [$requete[0]['id_voeu']] = $requete[0]['avis'];
            }
            
            
       }
       
        
        return $resultat;
   }
   public function ecrireCommentaireVoeu($tableauAvis){
       
       foreach ($tableauAvis as $idVoeu=>$avis){
           if ($avis != '' || $avis !=NULL){
                $nbrAvis = $this->verifierExistenceVoeu($idVoeu)[0][0]; //on vérifie si par hasard le voeu exite déjà
                
                if ($nbrAvis == '0'){
                    $statement = "INSERT INTO `analyse_voeux` (`id_voeu`,`id_pedago`,`avis`) VALUES (?,?,?)  ";
                    $tabDatas =array ($idVoeu, $this->id_pedago, $avis);
                    $requete = $this->db->queryPDOPreparedExec($statement, $tabDatas);
                }
                else {
                    $statement = "UPDATE `analyse_voeux` SET `avis` = ? WHERE `id_voeu`= ? AND `id_pedago` = ?   ";
                    $tabDatas =array ($avis, $idVoeu, $this->id_pedago, );
                    $requete = $this->db->queryPDOPreparedExec($statement, $tabDatas);
                }
                    
                }
            
           }
           
       }
            
 
   
   private function verifierExistenceVoeu($idVoeu){
            $statement = "SELECT COUNT(*) FROM `analyse_voeux` WHERE `id_voeu` = ? AND `id_pedago` = ? ";
            $tabDatas =array ($idVoeu, $this->id_pedago);
            $requete = $this->db->queryPDOPrepared($statement, $tabDatas);
            
            return $requete;
   }
}
