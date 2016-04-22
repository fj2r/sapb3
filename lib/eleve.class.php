<?php

class eleve extends utilisateur {
    
    protected $connexion;
    protected $numeroDossier;
    protected $codeConfidentiel;
    protected $statut;
    protected $num_eleve_etab;
    protected $carteIdentite;

    public function __construct(){
        $this->carteIdentite =[];
        
    }
    
    public function identifierEleve ($connexion, $statut, $numeroDossier,$codeConfidentiel){
       
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
                $requete_carteIdentite = $this->connexion->prepare("SELECT * FROM import_eleve WHERE num_eleve_etab = :num_eleve_etab");
                $requete_carteIdentite->bindParam(':num_eleve_etab',$this->num_eleve_etab,PDO::PARAM_STR);
                $requete_carteIdentite->execute();
                
                if ($requete_carteIdentite->fetch()){
                    foreach ($requete_carteIdentite as $liste) {
                    $this->carteIdentite['sexe'] = $liste ['sexe'] ;
                        $this->carteIdentite['nom'] = $liste ['nom'] ;
                        $this->carteIdentite['prenom'] = $liste ['prenom'] ;
                        $this->carteIdentite['prenom2'] = $liste ['prenom2'] ;
                        $this->carteIdentite['prenom3'] = $liste ['prenom3'] ;
                        $this->carteIdentite['naissance'] = $liste ['naissance'] ;
                        $this->carteIdentite['code_mef'] = $liste ['code_mef'] ;
                        $this->carteIdentite['lib_mef'] = $liste ['lib_mef'] ;
                        $this->carteIdentite['code_structure'] = $liste ['code_structure'] ;
                        $this->carteIdentite['type_structure'] = $liste ['type_structure'] ;
                        $this->carteIdentite['lib_structure'] = $liste ['lib_structure'] ;
                        $this->carteIdentite['cle_gestion1'] = $liste ['cle_gestion1'] ;
                        $this->carteIdentite['lib_matiere1'] = $liste ['lib_matier1'] ;
                        $this->carteIdentite['email'] = $liste ['email'] ;
                        
                        
                        
                    }
                    
                }
            
            }
            catch (Exception $e){
                die('Erreur :'.$e->getMessage());
            }
            
        }
        
    return $this->carteIdentite;   
    }
    
}
