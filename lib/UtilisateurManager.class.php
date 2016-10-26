<?php

/*
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

namespace lib;

/**
 * Description of UtilisateurManager
 *
 * @author fred
 */
class UtilisateurManager {

    private $database;
    private $statement;
    
    public function __construct($maBase) {
        $this->setDB($maBase);
    }
    //////////////////////////////CRUD////////////////////////////////////////
    public function createUtilisateur(\Utilisateur $utilisateur, $table, $champs, $values){
        
        $statement = 'INSERT INTO'.$table.'('.$champs.') VALUES('.$values.')' ;
        $this->database->executePDO($statement);
    }
    public function readUtilisateur(\Utilisateur $utilisateur){}
    public function updateUtilisateur(\Utilisateur $utilisateur){}
    public function deleteUtilisateur(\Utilisateur $utilisateur){}
    

    public function setDB ($db){
        $this->database = $db;
    }
    
}
