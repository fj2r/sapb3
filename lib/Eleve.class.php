<?php

namespace lib;

class Eleve extends Utilisateur {
    
    protected $connexion;
    public $db;
    protected $pdo;
    protected $numDossier = 000000;
    protected $codeConf = 'JD0000';
    protected $statut = 'eleve';
    protected $num_eleve_etab;
    protected $carteIdentite = array ();
    protected $id_eleve = 0;
    protected $nom = 'DOE';
    protected $prenom = 'John';
    protected $naissance = '31/12/2000';
    protected $tableauInfos;
    protected $sexe = 'M';
    protected $prenom2;
    protected $prenom3;
    protected $libMEF ;
    protected $codeMEF;
    protected $codeStructure = 'P';
    protected $libStructure = 'Première';
    protected $email = 'john.doe@localhost';
    protected $typeStructure ='D';
    protected $communeNaissance = 'NYC';
    protected $redoublant  = 'N';
    protected $idNational;
    protected $portable;
    protected $fixe;
    public $nbVoeux = 0 ;
    
    /* Les LV et options */
    protected $LV1;
    protected $LV2;
    
    /* eventuellement, les données des parents */
    protected $parent1Lien;
    protected $parent1Civilite;
    protected $parent1Nom;
    protected $parent1Prenom;
    protected $parent1Fixe;
    protected $parent1Portable;
    protected $parent1Email;
    protected $parent1Adresse;
    protected $parent1CP;
    protected $parent1Commune;
    protected $parent1Pays;
    
    protected $parent2Lien;
    protected $parent2Civilite;
    protected $parent2Nom;
    protected $parent2Prenom;
    protected $parent2Fixe;
    protected $parent2Portable;
    protected $parent2Email;
    protected $parent2Adresse;
    protected $parent2CP;
    protected $parent2Commune;
    protected $parent2Pays;

    public function __construct($db, $statut='eleve') {
        parent::__construct($db, $statut); //l'élève hérite du constructeur 
        $this->db = $db;  
        
        $this->pdo = $this->db->getPDO();
        
    }
    /////////////////////////// accesseurs ///////////////////////////////////////
    public function getCommuneNaissance(){
        return $this->communeNaissance;
    }
    public function getNumeroDossier (){
        return $this->numDossier; 
    }
    public function getCodeConfidentiel (){
        return $this->codeConf; 
    }
    public function getStatut (){
        return $this->statut; 
    }
    public function getId_eleve (){
        return $this->id_eleve; 
    }
    public function getNom (){
        return $this->nom; 
    }
    public function getPrenom (){
        return $this->prenom; 
    }
    public function getNumEleveEtab (){
        return $this->num_eleve_etab; 
    }
    public function getSexe (){
        return $this->sexe; 
    }
    public function getNaissance (){
        return $this->naissance; 
    }
    public function getCodeStructure (){
        return $this->codeStructure; 
    }
    public function getLibStructure (){
        return $this->libStructure; 
    }
    ////////////////////////// mutateurs ////////////////////////////////////////
    
    public function setNumDossier ($num_dossier){
       $this->numDossier = htmlspecialchars($num_dossier);
        
    }
    public function setCodeConfidentiel ($code_confidentiel){
         $this->codeConf = htmlspecialchars($code_confidentiel);
         
    }
    
    public function setNom ($nom){
        $this->nom = htmlspecialchars($nom);
    }
    public function setPrenom ($prenom){
        $this->prenom = htmlspecialchars($prenom);
    }
    public function setNaissance ($naissance){
        $this->naissance = htmlspecialchars($naissance);
    }
    public function setNumEleveEtab ($num_eleve_etab){
        $this->num_eleve_etab = htmlspecialchars($num_eleve_etab);
    }
    public function setIdEleve ($id_eleve){
        $this->id_eleve = htmlspecialchars($id_eleve);
    }
    public function setSexe ($sexe){
        $this->sexe = htmlspecialchars($sexe);
    }
    ////////////////////////////////////////////////////////////////////////////
    public function rechercheEleve ($nom, $prenom, $dateDeNaissance){
        $statement = "SELECT * FROM import_eleve_complet WHERE `Nom de famille` = ? AND `Prénom` = ? AND `Date Naissance` = ?";
        $tabDatas = array ($nom,$prenom,$dateDeNaissance);
        $tableau = $this->db->queryPDOPrepared($statement, $tabDatas); //on récupère TOUTES les infos sur l'élève!
        
        if ($tableau !=NULL){
        $infosEleve = $tableau[0]; //ATTENTION car c'est un tableau à 2 dimensions, donc on extrait le tableau du tableau
        $infosEleve = $this->preparerTableauPourTwig($infosEleve);
        
        return $infosEleve;
        }
        else {
            return FALSE;
        }
        
    }
    public function preparerTableauPourTwig ($tableau){ // IMPORTANT : nécessaire car les champs de la database sont par défaut avec des espaces et de points, ca qui est très mal pour Twig
        
        $search = array(" ", ".");
        $replace = array ("_","_");
        $tabModifie = array();
        foreach ($tableau as $key=>$values){
            
            $tabModifie [$key=str_replace( $search,$replace, $key)] = $values;
            
        }
       
        return $tabModifie;
    }
    
    public function creerDossier (){
        
    }
    
    public function profilEleve (){
      
        if ($this->statut =='eleve'){
            try {
                $statement = "SELECT * FROM cfg_eleves WHERE numDossier = ? AND codeConf = ?";
                $tabDatas = array ($this->numDossier, $this->codeConf);
                $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
                
                if ($tableau == FALSE){
                    //header('Location:logout.php');
                    return FALSE;
                }
                else {

                    if(!empty($tableau)){
                        $this->id_eleve = $tableau[0]['id_eleve'];

                     try {

                        $statement = "SELECT * FROM import_eleve_complet WHERE `id_eleve` = ?";
                        $tabDatas = array ($this->id_eleve);
                        $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
                        $tableauFils = array ();
                        $tableauFils = $tableau[0];


                            if (!empty($tableauFils)){

                                    $this->sexe = $tableauFils ['Sexe'] ;
                                    $this->num_eleve_etab = $tableauFils ['Num. Elève Etab'] ;
                                    $this->nom = $tableauFils ['Nom de famille'] ;
                                    $this->prenom = $tableauFils ['Prénom'] ;
                                    $this->prenom2 = $tableauFils ['Prénom 2'] ;
                                    $this->prenom3 = $tableauFils ['Prénom 3'] ;
                                    $this->naissance = $tableauFils ['Date Naissance'] ;
                                    $this->codeMEF = $tableauFils ['Code MEF'] ;
                                    $this->libMEF = $tableauFils ['Lib. MEF'] ;
                                    $this->codeStructure = $tableauFils ['Code Structure'] ;
                                    $this->typeStructure = $tableauFils ['Type Structure'] ;
                                    $this->libStructure = $tableauFils ['Lib. Structure'] ;
                                    $this->LV1 = $tableauFils ['Lib. Mat. Enseignée 1'] ;
                                    $this->LV2 = $tableauFils ['Lib. Mat. Enseignée 2'] ;
                                    $this->email = $tableauFils ['Email'] ;
                                    $this->portable = $tableauFils ['Tél. Portable'];
                                    $this->fixe=$tableauFils ['Tél. Personnel'];
                                    $this->email = $tableauFils ['Email'];
                                    
                                    return TRUE;
                            }

                        }
                        catch (Exception $e){
                            die('Erreur :'.$e->getMessage());
                        }


                    }
                }   
            }
            catch (Exception $e){
                die('Erreur : '.$e->getMessage());
                
            }
            
           
            
        }
        
    
    }
       
    public function tableauNotes (){
                
    }
    public function listerProfesseurs(){
        try {
            $statement = "SELECT `matiere`,`nomComplet` FROM attribution_matieres WHERE `Code Structure` = ?";
                $tabDatas = array ($this->codeStructure);
                $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
                if ($tableau == FALSE){
                    
                }
                else {
                    if (!empty($tableau)){
                                                
                        return $tableau;
                    }
                    else {return NULL;}
                }
        }
        catch (Exception $e){
            die('Erreur : '.$e->getMessage());
        };
    }
    public function listerVoeux(){
        
    }
    
    public function genererSession(){
        $_SESSION = array ();
        $_SESSION = array (
            'num_dossier' => ''.$this->numDossier.'',
            'code_conf' => ''.$this->codeConf.'',
            'nom' => ''.$this->nom.'',
            'prenom' => ''.$this->prenom.'',
            'classe' => ''.$this->codeStructure.'',
            'id_eleve' => ''.$this->id_eleve.'',
            'num_eleve_etab' => ''.$this->num_eleve_etab.'',
        );
        
     
        
    }
    
    
    public function genererCookie() {
       if ($this->statut == 'eleve'){
           
            $this->detruireCookie();
           
            setcookie('num_dossier',$this->numDossier,time()+3600);
            setcookie('code_conf',md5($this->codeConf),time()+3600);
            setcookie('num_eleve_etab',$this->num_eleve_etab,time()+3600);
            setcookie('nom',$this->nom,time()+3600);
            setcookie('prenom',$this->prenom,time()+3600);
            setcookie('classe',$this->codeStructure, time()+3600);
            setcookie('id_eleve',$this->id_eleve, time()+3600);
       }
   }   
    public function detruireCookie() {
            setcookie('num_dossier');
            setcookie('code_conf');
            setcookie('num_eleve_etab');
            setcookie('nom');
            setcookie('prenom');
            setcookie('classe');
            setcookie('id_eleve');
            unset ($_COOKIE['num_dossier']);
            unset ($_COOKIE['code_conf']);
            unset ($_COOKIE['num_eleve_etab']);
            unset ($_COOKIE['nom']);
            unset ($_COOKIE['prenom']);
            unset ($_COOKIE['classe']);
            unset ($_COOKIE['id_eleve']);
   }
   
     public function envoiMail() {
       
       
       
   }
   
    public function construireCodesEleve ($id, $nbItérations = 5) {
       /*construction d'un numéro de dossier aléatoire (par défaut avec 5 caractères*/
       $char = [];   
       $numDossier = 000000;
       for ($i=0 ; $i<=$nbItérations; $i++){
           $char[$i]= mt_rand(0, 9);
       }
       foreach ($char as $values){
           $numDossier .= $values;
       }
       $this->numDossier = $numDossier;
       
       /*construction d'un code confidntiel aléatoire*/
       $sel = mt_rand(1000, 9999);
       $codeConf = substr($this->nom,0,2)."$sel";
       
       $this->codeConf = $codeConf;
       
       $tabIdentifiants = array($numDossier, $codeConf);
       
       $enregistrement = $this->enregistrerCodesEleve($id, $tabIdentifiants);
       
       if ($enregistrement == TRUE){
            return $tabIdentifiants;
       
       }
       else {
           return FALSE;
       }
   }
   
   public function enregistrerCodesEleve ($id, $tabIdentifiants){
       /*verification pour voir si l'id existe déjà dans la base*/ 
        $statementSelect = "SELECT * FROM cfg_eleves WHERE `id_eleve` = ? ";
        $tabDatas = array ($id);

        $tableau = $this->db->queryPDOPrepared($statementSelect, $tabDatas); //vérifie si la config de l'élève est déja faite
        
        if ($tableau == FALSE){
            
            
            $statementInsert = "INSERT INTO cfg_eleves (id_eleve,num_eleve_etab, first_login, last_login, inscription_ok, numDossier, codeConf, validation_tuteur) VALUES (?,?,?,?,?,?,?,?)";
            $tabDatas =array(
                $this->id_eleve,
                $this->num_eleve_etab,
                $firstLogin = mktime(),
                $lastLogin = mktime(),
                $inscription = TRUE,
                $this->numDossier,
                $this->codeConf,
                $validationTuteur = TRUE
                );
               
            
            $resultat = $this->db->queryPDOPreparedExec($statementInsert, $tabDatas);
            
            $enregistrement = TRUE ;
        }  
        else {
            $enregistrement = FALSE;
        }
        
        return $enregistrement ;
       
   }
   
   public function verifierVoeux () {
       
       
        $statement = "SELECT count(*) FROM validations WHERE `id_eleve` = ?";
        $tabDatas = array ($this->id_eleve);
        $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
        
        
        if ($tableau[0][0] == 0){
            
            return $this->nbVoeux = 0;
             
        }
        else {
            return $this->nbVoeux = $tableau[0][0];
        }
       
   }
   
   public function recupererVoeux () {
        
        if ($this->verifierVoeux() != 0){
            $statement = "SELECT * FROM `validations` INNER JOIN etablissement ON `validations`.`id_etab`=`etablissement`.`id_etab` WHERE `validations`.`id_eleve`= ? ORDER BY `validations`.`classement` ASC";
            $tabDatas = array ($this->id_eleve);
            $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
           
            

            return $tableau;
        }
        else {
            return $tableau = array ();
        }
        
   }
   
   public function recupererVoeuAModifier ($id_voeu) {
       if ($this->verifierVoeux() != 0){
            $statement = "SELECT * FROM `validations` INNER JOIN `etablissement` ON `validations`.`id_etab`=`etablissement`.`id_etab` WHERE `validations`.`id_eleve`= ? AND `validations`.`id_voeu`= ? ORDER BY `validations`.`classement` ASC";
            $tabDatas = array ($this->id_eleve, $id_voeu );
            
            $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
            

            return $tableau;
        }
        else {
            return $tableau = array ();
        }
   }
   
   public function enregistrerVoeu () {
       
       $statement = "SELECT * FROM validations WHERE `id_eleve` = ?";
        $tabDatas = array ($this->id_eleve);
        $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
   }
   
  
}
