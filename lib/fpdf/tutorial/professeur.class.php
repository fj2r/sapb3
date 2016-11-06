<?php

/* 
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

class professeur extends utilisateur {
    
    protected $statut;
    protected $login;
    protected $passwd;


    public function identifierProf ($connexion, $statut, $login , $passwd){
        
        if ($this->statut =='professeur'){
            try {
            $requete = $this->connexion->prepare("SELECT * FROM equipe_pedagogique WHERE login = :login AND passwd = :passwd");
            $requete->bindParam(':login',$this->login,PDO::PARAM_STR);
            $requete->bindParam(':passwd',$this->passwd,PDO::PARAM_STR);
            $requete->execute();
            }
            catch (Exception $e){
                die('Erreur :'.$e->getMessage());
                
            }
        }
               
        
    }
    
}