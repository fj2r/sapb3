<?php

/*
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

namespace lib;

/**
 * Description of Administratif
 *
 * @author fred
 */
class Administratif extends Utilisateur {
    //put your code here
    public $db;
    protected $statut;
    protected $login;
    protected $passwordEncrypte;
    protected $passwd;
    protected $id_admin;
    protected $civilite;
    protected $prenom;
    protected $nom;
    protected $fonction;
    protected $email;
    
    
    public function __construct($db, $statut = 'administratif') {
        parent::__construct($db, $statut);
    }
    /////////////////////////////////////////////////////////////////////////
    public function setIdAdmin($data){
        $this->id_admin = $data;
    }
    public function getIdAdmin(){
        return $this->id_admin;
    }
    /////////////////////////////////////////////////////////////////////////
    
    
    public function existanceAdmin(){
        
        
        $statement = "SELECT `password` FROM `identifiants_administration` WHERE `login`=?  ";
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
    public function profilAdmin (){
        $statement = "SELECT * FROM `identifiants_administration` INNER JOIN `equipe_administrative` ON `identifiants_administration`.`id_admin` = `equipe_administrative`.`id_admin` WHERE `identifiants_administration`.`login`=?  ";
        $tabDatas =array ($this->login);
        $requete = $this->db->queryPDOPrepared($statement, $tabDatas);
        
        
        $this->id_admin = $requete[0]['id_admin'];
        $this->login = $requete[0]['login'];
        $this->passwordEncrypte = $requete[0]['password'];
        $this->civilite = $requete[0]['civilite'];
        $this->nom = $requete[0]['nom'];
        $this->prenom = $requete[0]['prenom'];
        
        $this->email = $requete[0]['email'];
        
        
        
       
        return $requete; //on retourne un seul tableau de dimension 2 contenant 3 tableaux...profil/matieres/codeStructure(classe + matiere associée)
    }
    
    
    
    public function genererSession(){
        $_SESSION = array ();
        $_SESSION = array (
            'id_admin' => ''.$this->id_admin.'',
            'login' => ''.$this->login.'',
            'nom' => ''.$this->nom.'',
            'prenom' => ''.$this->prenom.'',
            'civilite' => ''.$this->civilite.'',
            'fonction'=>''.$this->fonction.'',         
            
            'email' => ''.$this->email.'',
        );
        
     
        
    }
    
    public function genererCookie() {
       if ($this->statut == 'administratif'){
           
            $this->detruireCookie();
           
            setcookie('id_admin',$this->id_admin,time()+3600);
            setcookie('login',$this->login,time()+3600);
            setcookie('civilite',$this->civilite, time()+3600);
            setcookie('nom',$this->nom,time()+3600);
            
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
   
   public function ecrireCommentaireP1($tableauAvis){
       foreach ($tableauAvis as $idVoeu=>$avis){
           if ($avis != '' || $avis !=NULL){
                $nbrAvis = $this->verifierExistenceVoeu($idVoeu)[0][0]; //on vérifie si par hasard le voeu exite déjà
                
                if ($nbrAvis == '0'){
                    $statement = "INSERT INTO `analyse_voeux_prov` (`id_voeu`,`id_admin`,`avis`) VALUES (?,?,?)  ";
                    $tabDatas =array ($idVoeu, $this->id_admin, $avis);
                    $requete = $this->db->queryPDOPreparedExec($statement, $tabDatas);
                }
                else {
                    $statement = "UPDATE `analyse_voeux_prov` SET `avis` = ? WHERE `id_voeu`= ? AND `id_admin` = ?   ";
                    $tabDatas =array ($avis, $idVoeu, $this->id_admin, );
                    $requete = $this->db->queryPDOPreparedExec($statement, $tabDatas);
                }
                    
                }
            
           }
   }
    
   private function verifierExistenceVoeu($idVoeu){
            $statement = "SELECT COUNT(*) FROM `analyse_voeux_prov` WHERE `id_voeu` = ? AND `id_admin` = ? ";
            $tabDatas =array ($idVoeu, $this->id_admin);
            $requete = $this->db->queryPDOPrepared($statement, $tabDatas);
            
            return $requete;
   }
   
   public function recupererCommentairesVoeux($voeuxEleve){
       $resultat = array();
       
       foreach ($voeuxEleve as $idVoeu ){
           
            $statement = "SELECT * FROM `analyse_voeux_prov` WHERE `analyse_voeux_prov`.`id_voeu`= ? AND `analyse_voeux_prov`.`id_admin`= ? ";
            $tabDatas =array ($idVoeu['id_voeu'], $this->id_admin);
            $requete = $this->db->queryPDOPrepared($statement, $tabDatas);
            
            if ($requete != NULL){
                $resultat [$requete[0]['id_voeu']] = $requete[0]['avis'];
            }
            
            
       }
       
        
        return $resultat;
   }
    
}
