<?php

/*
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

namespace lib;

/**
 * Description of Division
 *
 * @author fred
 */
class Division {
   
    public $db;
    protected $codeStructure;
    protected $listeDivisions;
    protected $listeEleves;
    protected $idClasse ;
    protected $libMEF;
    protected $libStructure ;
    protected $codeMEF;
    
    
    public function __construct($db, $division = '') {
        $this->db = $db;
        $this->setCodeStructure($division);
        
    }
    
    public function setCodeStructure($data){
        $this->codeStructure = $data ;
    }
     public function setListeDivisions($data){
        $this->listeDivisions = $data ;
    }
     public function setListeEleves($data){
        $this->listeEleves = $data ;
    }
     public function setIdClasse($data){
        $this->idClasse = $data ;
    }
     public function setLibMEF($data){
        $this->libMEF = $data ;
    }
     public function setLibStructure($data){
        $this->libStructure = $data ;
    }
     public function setCodeMEF($data){
        $this->codeMEF = $data ;
    }
    ///////////////////////////////////////////////////////
    public function getCodeStructure (){
        return $this->codeStructure;
    }
     public function getListeDivisions (){
        return $this->listeDivisions;
    }
     public function getListeEleves (){
        return $this->listeEleves;
    }
     public function getIdClasse (){
        return $this->idClasse;
    }
     public function getLibMEF (){
        return $this->libMEF;
    }
     public function getCodeMEF (){
        return $this->codeMEF;
    }
     public function getLibStructure (){
        return $this->libStructure;
    }
    
    
    ///////////////////////////////////////////////////////////
    
    public function listerEleves (){
        
        
        $statement = "SELECT DISTINCT `import_eleve_complet`.`id_eleve`,`import_eleve_complet`.`Nom de famille`,`import_eleve_complet`.`Prénom` FROM `import_eleve_complet` WHERE `import_eleve_complet`.`Code Structure`=? ORDER BY `import_eleve_complet`.`Nom de famille` ASC ";
        $tabDatas =array ($this->codeStructure);
        $requete = $this->db->queryPDOPrepared($statement, $tabDatas);
        
        //var_dump($requete);
        $requeteCompletee = $this->completerlisteAvecNbVoeux($requete);
        
        
        return $requeteCompletee;
    }
    
    private function completerlisteAvecNbVoeux ($listeEleves){
        
        $i = 0;
        foreach ($listeEleves as $eleve){
    
            $listeEleves[$i]['nombreVoeux'] = $this->nombreVoeuxEleve($eleve['id_eleve'])[0][0] ;
            $i++;
        
        }
        return $listeEleves;

    }
    
    public function nombreVoeuxEleve ($data){
        $statement = "SELECT COUNT(*) FROM `validations` WHERE `id_eleve` = ?";
        $tabDatas =array ($data);
        $requete = $this->db->queryPDOPrepared($statement, $tabDatas);
         return $requete;
    }
    
    public function elevePrecedent  ($codeStructure, $nom, $prenom){
        
       
        $statement = "SELECT * FROM `import_eleve_complet` WHERE `Code Structure` = ? AND (`Nom de famille`,`Prénom`) < (?,?) ORDER BY `Nom de famille` DESC,`Prénom` DESC,`Prénom 2` DESC  LIMIT 0, 1";
        $tabDatas =array ($codeStructure,$nom, $prenom);
        $requete = $this->db->queryPDOPrepared($statement, $tabDatas);
        
        return $requete;
    }
     public function eleveSuivant  ($codeStructure, $nom, $prenom){
        
       
        $statement = "SELECT * FROM `import_eleve_complet` WHERE `Code Structure` = ? AND (`Nom de famille`,`Prénom`) > (?,?)  ORDER BY `Nom de famille`,`Prénom`,`Prénom 2`  LIMIT 0, 1";
        $tabDatas =array ($codeStructure,$nom, $prenom);
        $requete = $this->db->queryPDOPrepared($statement, $tabDatas);
        
        return $requete;
    }
    
}
