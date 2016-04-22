<?php

/* 
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */


class administrateur extends utilisateur {
    
    protected $connexion;
    protected $statut;
    protected $login;
    protected $passwd;


    public function identifierAdministrateur ($connexion, $statut, $login, $passwd){
        
        if ($this->statut =='administrateur'){
            try {
            $requete = $this->connexion->prepare("SELECT * FROM administration WHERE login = :login AND passwd = :passwd");
            $requete->bindParam(':login',$login,PDO::PARAM_STR);
            $requete->bindParam(':passwd',$passwd,PDO::PARAM_STR);
            
            $requete->execute();
            }
            catch (Exception $e){
                die('Erreur :'.$e->getMessage());
                
            }
        }
        
    }
    
}