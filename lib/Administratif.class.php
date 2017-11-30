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
        
        if ($requete != FALSE){

                if ($this->verifierPassword($this->passwd, $hash) == TRUE) {

                    return TRUE;
                }
                else {
                    return FALSE;
                }
        }
        else { return FALSE ; }
        
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
        $_SESSION = array (); //destruction du tableau de session précédent
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
            setcookie('fonction',$this->fonction,time()+3600);
       }
   }   
    public function detruireCookie() {
            setcookie('id_admin','',  time()-3600);
            setcookie('login','',  time()-3600);
            setcookie('civilite','',  time()-3600);
            setcookie('nom','',  time()-3600);
            
            setcookie('prenom','',  time()-3600);
            setcookie('email','',  time()-3600);
            setcookie('fonction',$this->fonction,time()-3600);
            unset ($_COOKIE['id_admin']);
            unset ($_COOKIE['login']);
            unset ($_COOKIE['civilite']);
            unset ($_COOKIE['nom']);
            unset ($_COOKIE['prenom']);
            
            unset ($_COOKIE['email']);
            unset ($_COOKIE['fonction']);
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
   
   public function ecrireAvisCommission($tableauAvis){
        
       foreach ($tableauAvis as $clef=>$data){
            //*D'abord traiter la clé pour en extraire le n°du voeu*/
            $delimiter ="-";
            $splitArray = explode($delimiter, $clef);
            $type = $splitArray[0];
            $idVoeu = $splitArray[1];
            /*puis on peut continuer*/
            
           if ($data != '' || $data  !=NULL){
              
                $nbrAvis = $this->verifierExistenceAvis($idVoeu)[0][0]; //on vérifie si par hasard l'avis exite déjà
                
                if ($nbrAvis == '0'){
                     if ($type == 'decision'){
                            $statement = "INSERT INTO `decision_commission` (`id_voeu`,`decision_commission`) VALUES (?,?)  "; //d'abord on inscrit la décision
                            $tabDatas =array ($idVoeu,$data ,);
                            $requete = $this->db->queryPDOPreparedExec($statement, $tabDatas);
                            
                     }
                     elseif ($type == 'avis'){
                            $statement = "UPDATE `decision_commission` SET  `avis_commission` = ? WHERE `id_voeu`= ? "; //puis on update le  même voeu pour l'avis facultatif
                            $tabDatas =array ($data , $idVoeu );
                            $requete = $this->db->queryPDOPreparedExec($statement, $tabDatas);
                     }
                }
                else { //si le voeu existe déjà alors on update simplement
                    if ($type == 'decision'){
                            $statement = "UPDATE `decision_commission` SET  `decision_commission` = ? WHERE `id_voeu`= ? "; //d'abord on inscrit la décision
                            $tabDatas =array ($data ,$idVoeu);
                            $requete = $this->db->queryPDOPreparedExec($statement, $tabDatas);
                            
                     }
                     elseif ($type == 'avis'){
                            $statement = "UPDATE `decision_commission` SET  `avis_commission` = ? WHERE `id_voeu`= ? "; //puis on update le  même voeu pour l'avis facultatif
                            $tabDatas =array ($data , $idVoeu );
                            $requete = $this->db->queryPDOPreparedExec($statement, $tabDatas);
                }
                    
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
   
   private function verifierExistenceAvis($idVoeu){
            $statement = "SELECT COUNT(*) FROM `decision_commission` WHERE `id_voeu` = ? ";
            $tabDatas =array ($idVoeu);
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
   
   public function recupererAvisCommission($voeuxEleve){
        $resultat = array();
        foreach ($voeuxEleve as $idVoeu){
            $statement = "SELECT * FROM `decision_commission` WHERE `decision_commission`.`id_voeu`= ? ";
            $tabDatas =array ($idVoeu['id_voeu']);
            $requete = $this->db->queryPDOPrepared($statement, $tabDatas);
            
            if ($requete != NULL){
                $resultat ['decision-'.$requete[0]['id_voeu']] = $requete[0]['decision_commission'];
                $resultat ['avis-'.$requete[0]['id_voeu']] = $requete[0]['avis_commission'];
            }
            
        }
        return $resultat ;
    }
}