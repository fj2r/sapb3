<?php

namespace lib;

class Eleve extends Utilisateur {
    
    protected $connexion;
    protected $db;
    protected $pdo;
    protected $numDossier;
    protected $codeConf;
    protected $statut;
    protected $num_eleve_etab;
    protected $carteIdentite;
    protected $id_eleve;
    protected $nom = 'DOE';
    protected $prenom;
    protected $naissance = '31/12/2000';
    protected $tableauInfos;
 
    

    public function __construct($db, $statut='eleve') {
        parent::__construct($db, $statut); //l'élève hérite du constructeur 
        $this->db = $db;    
    }
    ///////////////////////////mutateurs///////////////////////////////////////
    public function numeroDossier (){
        return $this->numeroDossier; 
    }
    public function codeConfidentiel (){
        return $this->codeConfidentiel; 
    }
    public function statut (){
        return $this->statut; 
    }
    public function id_eleve (){
        return $this->id_eleve; 
    }
    //////////////////////////
    
    public function setNumDossier ($num_dossier){
       $this->numeroDossier = htmlspecialchars($num_dossier);
    }
    public function setCodeConfidentiel ($code_confidentiel){
         $this->code_confidentiel = htmlspecialchars($code_confidentiel);
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
    //////////////////////////////
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
    
    public function profilEleve ($maBDD, $statut){
      
        if ($this->statut =='eleve'){
            try {
            
            $requete = $maBDD->prepare("SELECT * FROM cfg_eleves WHERE num_dossier = :numeroDossier AND code_conf = :codeConfidentiel");
            $requete->bindParam(':numeroDossier',$this->numeroDossier ,PDO::PARAM_STR);
            $requete->bindParam(':codeConfidentiel',$this->code_confidentiel,PDO::PARAM_STR);
            $requete->execute();
            if ($requete->fetch()){
                foreach ($requete as $liste){
                $this->id_eleve = $liste ['id_eleve'] ;
                }
            }
            else {echo 'Eleve inconnu...';}
            
            }
            catch (Exception $e){
                die('Erreur :'.$e->getMessage());
                
            }
            
            try {
                $requete_carteIdentite = $this->connexion->prepare("SELECT * FROM import_eleve_complet WHERE 'id_eleve' = :id_eleve");
                $requete_carteIdentite->bindParam(':id_eleve',$this->id_eleve,PDO::PARAM_STR);
                $requete_carteIdentite->execute();
                
                if ($requete_carteIdentite->fetch()){
                    foreach ($requete_carteIdentite as $liste) {
                        $this->carteIdentite['sexe'] = $liste ['Sexe'] ;
                        $this->carteIdentite['nom'] = $liste ['Nom de famille'] ;
                        $this->carteIdentite['prenom'] = $liste ['Prénom'] ;
                        $this->carteIdentite['prenom2'] = $liste ['Prénom 2'] ;
                        $this->carteIdentite['prenom3'] = $liste ['Prénom 3'] ;
                        $this->carteIdentite['naissance'] = $liste ['Date Naissance'] ;
                        $this->carteIdentite['code_mef'] = $liste ['Code MEF'] ;
                        $this->carteIdentite['lib_mef'] = $liste ['Lib. MEF'] ;
                        $this->carteIdentite['code_structure'] = $liste ['Code Structure'] ;
                        $this->carteIdentite['type_structure'] = $liste ['Type Structure'] ;
                        $this->carteIdentite['lib_structure'] = $liste ['Lib. Structure'] ;
                        $this->carteIdentite['cle_gestion1'] = $liste ['Clé Gestion Mat. Enseignée 1'] ;
                        $this->carteIdentite['lib_matiere1'] = $liste ['Lib. Mat. Enseignée 1'] ;
                        $this->carteIdentite['email'] = $liste ['Email'] ;
                        
                        
                        
                    }
                    
                }
            
            }
            catch (Exception $e){
                die('Erreur :'.$e->getMessage());
            }
            
        }
        
    return $this->id_eleve;   
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
