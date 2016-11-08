<?php

/*
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

namespace lib;

/**
 * Description of Notes
 *
 * @author fred
 */
class Notes extends Eleve {
    
    public $db;
    protected $listeMatieres ;
    protected $codeStructure ;
    protected $id_eleve;
    
    
    public function __construct($db, $statut = 'eleve', $id_eleve) {
        parent::__construct($db, $statut);
        $this->setIdEleve($id_eleve);
    }
    
    public function setCodeStructure ($data){
        $this->codeStructure = $data;
    }
    public function setIdEleve($id_eleve) {
        $this->id_eleve = $id_eleve;
    }
    
    public function getCodeStructure(){
        return $this->codeStructure;
    }
    
    public function matieresSelectionnees (){
        
                $statement = "SELECT DISTINCT `matiere` FROM attribution_matieres WHERE `Code Structure` = ? ORDER BY `matiere` ASC ";
                $tabDatas = array ($this->codeStructure);
                $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
                if ($tableau == FALSE){
                    return FALSE ;
                }
                else {
                    if (!empty($tableau)){
                                                
                        return $tableau;
                    }
                    else {return NULL;}
                }
    }
    
    private function verifierNotesT1 ($matiere){
                $statement = "SELECT COUNT(*) FROM notes WHERE `id_eleve` = ? AND `matiere` = ? AND `moyennes_t1` IS NOT NULL ";
                $tabDatas = array ($this->id_eleve, $matiere);
               return $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
    }
    private function verifierNotesT2 ($matiere){
                $statement = "SELECT COUNT(*) FROM notes WHERE `id_eleve` = ? AND `matiere` = ? AND `moyennes_t2` IS NOT NULL ";
                $tabDatas = array ($this->id_eleve, $matiere);
               return $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
    }
    
    public function ecrireNotes ($tableauMoyennes){
            
            foreach ($tableauMoyennes as $matiere => $note){
                
                //*D'abord traiter la clé pour en extraire le trimestre/
                $delimiter ="-";
                $splitArray = explode($delimiter, $matiere);
                $trimestre = $splitArray[0];
                $matiere = $splitArray[1];
                $matiere = str_replace('_', ' ', $matiere); // ATTENTION car le POST rajoute des _ qu'il faut vite virer
                
               
                
                /*puis on peut continuer*/
                
                if ($trimestre == 'T1' && $note !=''){
                    
                    if ($this->verifierNotesT1($matiere)[0][0] == '0' ){
                    $statement = "INSERT INTO `notes` (`id_eleve`, `matiere`, `moyennes_t1`) VALUES (?,?,?)  ";
                    $tabDatas = array ($this->id_eleve,$matiere, $note );
                    $tableau  = $this->db->queryPDOPreparedExec($statement, $tabDatas);
                    }
                    else{
                    $statement = "UPDATE `notes` SET  `moyennes_t1`= ? WHERE `id_eleve` = ? AND `matiere` = ? ";
                    $tabDatas = array ($note, $this->id_eleve,$matiere  );
                    $tableau  = $this->db->queryPDOPreparedExec($statement, $tabDatas);
                    }
                }
                elseif ($trimestre == 'T2' && $note !=''){
                    if ($this->verifierNotesT2($matiere)[0][0] == '0'){
                    $statement = "INSERT INTO `notes` (`id_eleve`, `matiere`, `moyennes_t2`) VALUES (?,?,?)  ";
                    $tabDatas = array ($this->id_eleve,$matiere, $note );
                    $tableau  = $this->db->queryPDOPreparedExec($statement, $tabDatas);
                    }
                    else{
                    $statement = "UPDATE `notes` SET `moyennes_t2`= ? WHERE `id_eleve` = ? AND `matiere` = ?  ";
                    $tabDatas = array ($note, $this->id_eleve,$matiere  );
                    $tableau  = $this->db->queryPDOPreparedExec($statement, $tabDatas);    
                    }
                
                
            }
    }
    
    }
    
    public function recupererNotes (){
                        
      
                    $statement = "SELECT * FROM `notes` WHERE `id_eleve` = ? ";
                    $tabDatas = array ($this->id_eleve );
                    return $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas); 
                    
        
    }
}
