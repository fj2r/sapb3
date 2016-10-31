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
    
    private $login;
    private $passwordEncrypte;
    
    
    public function __construct($db, $statut = 'professeur') {
        parent::__construct($db, $statut);
    }
    
    public function setLogin ($data){
        $this->login = $data;
    }
    
    public function setPassword ($data){
        
        $this->passwordEncrypte->encrypte($data);
    }
    
    private function encrypte ($password){
        return password_hash($password, PASSWORD_DEFAULT); //on fait un hachage standard... le sel est mis de façon aléatoire.
    }
    
    public function profilProf(){
        $statement = "";
        $tabDatas =array ($this->login, $this->passwordEncrypte);
        $requete = $this->db->queryPDOPrepared($statement, $tabDatas);
        
        
    }
    
}
