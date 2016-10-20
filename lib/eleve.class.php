<?php

class eleve extends Utilisateur {
    
    protected $connexion;
    protected $numeroDossier;
    protected $codeConfidentiel;
    protected $statut;
    protected $num_eleve_etab;
    protected $carteIdentite;
    

    public function __construct() {
        parent::__construct();
        $this->statut = 'eleve';
    }
    
    public function setDossier (){
        $this->numeroDossier=  htmlentities($_POST(['num_dossier']));
        $this->codeConfidentiel=htmlentities($_POST(['code_confidentiel']));
        
    }
    
    public function creerDossier (){
        
    }
    
    public function identifierEleve ($connexion, $statut){
       $numeroDossier = $this->numeroDossier;
       $codeConfidentiel = $this->codeConfidentiel;
        if ($this->statut =='eleve'){
            try {
            $requete = $this->connexion->prepare("SELECT * FROM cfg_eleves WHERE num_dossier = :numeroDossier AND code_conf = :codeConfidentiel");
            $requete->bindParam(':numeroDossier',$numeroDossier,PDO::PARAM_STR);
            $requete->bindParam(':codeConfidentiel',$codeConfidentiel,PDO::PARAM_STR);
            $requete->execute();
            if ($requete->fetch()){
                foreach ($requete as $liste){
                $this->num_eleve_etab = $liste ['num_eleve_etab'] ;
                }
            }
            else {echo 'Eleve inconnu...';}
            
            }
            catch (Exception $e){
                die('Erreur :'.$e->getMessage());
                
            }
            
            try {
                $requete_carteIdentite = $this->connexion->prepare("SELECT * FROM import_eleve_complet WHERE 'Num. Elève Etab' = :num_eleve_etab");
                $requete_carteIdentite->bindParam(':num_eleve_etab',$this->num_eleve_etab,PDO::PARAM_STR);
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
        
    return $this->carteIdentite;   
    }
    
    public function tableauNotes (){
        
        
    }
    public function listeProfesseurs(){
        
    }
    public function listerVoeux(){
        
    }
    
    public function formLoginEleve (){
        
        
    }
}
