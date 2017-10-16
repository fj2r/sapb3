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
    protected $URL_AD = "adlycee.lyc-lmautun.loc";
    protected $nomCompteAD = "Consultweb";
    protected $passwdCompteAD = "Intranet08" ; 


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
   
   public function bindingAD ($login,$passwd){
        $message = "vous n\'êtes pas reconnu dans l\'annuaire du lyc&eacute;e, contactez le responsable.";

        // connexion à l'annuaire AD en anonymous (on fait que de la lecture et en plus c'est pas un LDAP standard..
        $connection_ldap = ldap_connect($this->URL_AD,"389")
	or die("<br />Impossible de se connecter &agrave; l'annuaire Active Directory du lyc&eacute;e !!! <br /> Veuillez contacter le responsable du site.");
        
        ldap_set_option($connection_ldap, LDAP_OPT_PROTOCOL_VERSION, "3");
	echo '<br /><i>&nbsp;Connexion &agrave; partir de l\'annuaire Active Directory du lyc&eacute;e... </i>';
        // binding  vers le serveur AD
        $ldapbind = ldap_bind($connection_ldap, $this->nomCompteAD ,$this->passwdCompteAD)
		or die("<br />Vos identifiants $login ne sont pas reconnus dans l'annuaire, veuillez vous identifier &agrave; nouveau");
	$base_ldap = "OU=IACA,DC=lyc-lmautun,DC=loc";
	
        if ($ldapbind)
	{    
		// preparation des donn&eacute;es			
		// on récupère le distinguishedName
		$dn= 'CN='.$login.',OU=PROFESSEURS,OU=Users,OU=Site par défaut,OU=IACA,DC=lyc-lmautun,DC=loc'; 		 
		$attribut1="name";
		//$attribut2="password";
				
		//comparaison du login dans l'annuaire
            $resultat=ldap_compare($connection_ldap, $dn, $attribut1, $login);
            
            if ($resultat === -1) {return ldap_error($ldapbind);}
            elseif ($resultat == FALSE) {return $message;}
            elseif ($resultat == TRUE) {
                //r&eacute;cup&eacute;rer les donn&eacute;es dans AD
                $filter="(|(cn=$login*))";
                $justthese = array( "cn", "displayName", "sn", "name", "givenName" );
                $sr=ldap_search($connection_ldap, $base_ldap, $filter, $justthese);
                $info = ldap_get_entries($connection_ldap, $sr);

                echo '<br />'.$info["count"]." entr&eacute;es trouv&eacute;es.\n";
                //on coupe le nom en plusieurs parties (pour avoir le pr&eacute;nom)

                $nom= utf8_decode($info[0]["sn"][0]);
                $prenom= utf8_decode($info[0]["givenname"][0]);
                             
                echo '<br /> Vous &ecirc;tes bien identifi&eacute; avec le nom d\'utilisateur suivant : '.$nom.' '.$prenom ;
                
                $enregistrement = $this->enregistrementProfBDD($login,$passwd);
                
                return $enregistrement;
                }
        
	}
	else
	{
		return 0;
	}
        
        
   }
   
   protected function enregistrementProfBDD ($login, $passwd){
       
       //hachage du passwd/////
       $crypted_passwd = $this->encrypte($passwd);
       ////////////////////////
       
       $statement = "UPDATE `identifiants_pedago` SET `id_pedago` = ? , `login` = ? , `password` = ? " ;
       $tabDatas = array($this->id_pedago,$login, $crypted_passwd);
       $requete =$this->db->queryPDOPreparedExec($statement,$tabDatas);
       
       return TRUE;
   }
}
