<?php

namespace lib;

class Eleve extends Utilisateur {
    
    protected $connexion;
    protected $db;
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
    
    
 
    

    public function __construct($db, $statut='eleve') {
        parent::__construct($db, $statut); //l'élève hérite du constructeur 
        $this->db = $db;  
        
        $this->pdo = $this->db->getPDO();
        
    }
    /////////////////////////// accesseurs ///////////////////////////////////////
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
    ////////////////////////////////////////////////////////////////////////////
    public function rechercheEleve ($nom, $prenom, $dateDeNaissance){
        $statement = "SELECT * FROM import_eleve_complet WHERE `Nom de famille` = ? AND `Prénom` = ? AND `Date Naissance` = ?";
        $tabDatas = array ($nom,$prenom,$dateDeNaissance);

        $tableau = $this->db->queryPDOPrepared($statement, $tabDatas); //on récupère TOUTES les infos sur l'élève!
        
        
        $infosEleve = $tableau[0]; //ATTENTION car c'est un tableau à 2 dimensions, donc on extrait le tableau du tableau
        $infosEleve = $this->preparerTableauPourTwig($infosEleve);
        
        return $infosEleve;
        
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
                    header('Location:logout.php');
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
                                    $this->email = $tableauFils ['Email'] ;

                            }

                        }
                        catch (Exception $e){
                            die('Erreur :'.$e->getMessage());
                        }


                    }
                }   
            }
            catch (Exception $e){
                die('Erreur :'.$e->getMessage());
                
            }
            
           
            
        }
        
    return ;
    }
       
    public function tableauNotes (){
                
    }
    public function listerProfesseurs(){
        
    }
    public function listerVoeux(){
        
    }
    
    public function genererSession($connexion,$id_eleve){
        
        try {
                $requete_session = $this->connexion->prepare("SELECT * FROM import_eleve_complet WHERE 'id_eleve' = :id_eleve");
                $requete_session->bindParam(':id_eleve',$this->id_eleve,PDO::PARAM_STR);
                $requete_session->execute();
                
                if ($requete_session->fetch()){
                    foreach ($requete_session as $liste) {
                        $this->session['sexe'] = $liste ['Sexe'] ;
                        $this->session['nom'] = $liste ['Nom de famille'] ;
                        $this->session['prenom'] = $liste ['Prénom'] ;
                        $this->session['prenom2'] = $liste ['Prénom 2'] ;
                        $this->session['prenom3'] = $liste ['Prénom 3'] ;
                        $this->session['naissance'] = $liste ['Date Naissance'] ;
                        $this->session['code_mef'] = $liste ['Code MEF'] ;
                        $this->session['lib_mef'] = $liste ['Lib. MEF'] ;
                        $this->session['code_structure'] = $liste ['Code Structure'] ;
                        $this->session['type_structure'] = $liste ['Type Structure'] ;
                        $this->session['lib_structure'] = $liste ['Lib. Structure'] ;
                        $this->session['cle_gestion1'] = $liste ['Clé Gestion Mat. Enseignée 1'] ;
                        $this->session['lib_matiere1'] = $liste ['Lib. Mat. Enseignée 1'] ;
                        $this->session['email'] = $liste ['Email'] ;
                        
                        
                        
                    }
                        $_SESSION ['sapb_nom'] = $this->session['nom'];
                        $_SESSION ['sapb_prenom'] = $this->session['prenom'];
                        $_SESSION ['sapb_classe'] = $this->session['code_structure'];
                        $_SESSION ['sapb_email'] = $this->session['email'];
                        $_SESSION ['sapb_num_eleve_etab'] = $this->session['nom'];
                        $_SESSION ['id_eleve'] = $this->id_eleve;
                    
                }
            
            }
            catch (Exception $e){
                die('Erreur :'.$e->getMessage());
            }
        
    }
    public function genererCookie() {
       if ($this->statut == 'eleve'){
            setcookie('sapb_num_dossier',$_SESSION['num_dossier'],time()+3600);
            setcookie('sapb_code_conf',$_SESSION['code_conf'],time()+3600);
            setcookie('sapb_num_eleve_etab',$_SESSION['num_eleve_etab'],time()+3600);
            setcookie('sapb_nom_eleve',$_SESSION['nom'],time()+3600);
            setcookie('sapb_prenom_eleve',$_SESSION['prenom'],time()+3600);
            setcookie('sapb_classe',$_SESSION['classe'], time()+3600);
       }
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
       
       $this->enregistrerCodesEleve($id, $tabIdentifiants);
      
       return $tabIdentifiants;
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
                var_dump($tabDatas);
            
            $resultat = $this->db->queryPDOPreparedExec($statementInsert, $tabDatas);
            
            $enregistrement = TRUE ;
        }  
        else {
            $enregistrement = FALSE;
        }
        
        return $enregistrement ;
       
   }
   
   
   
}
