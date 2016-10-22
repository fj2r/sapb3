<?php

namespace lib;

class Eleve extends Utilisateur {
    
    protected $connexion;
    protected $maBDD;
    protected $numeroDossier;
    protected $codeConfidentiel;
    protected $statut;
    protected $num_eleve_etab;
    protected $carteIdentite;
    protected $id_eleve;
    

    public function __construct($statut='eleve') {
        parent::__construct($statut);
        
        if (isset($_POST['num_dossier']) && isset($_POST['code_confidentiel'])){
        $num_dossier  = htmlentities($_POST(['num_dossier']));
        $code_confidentiel  = htmlentities($_POST(['code_confidentiel']));
       }
        
       
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
    //////////////////////////////
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
    
    public function formLoginEleve (){
        
        
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
}
