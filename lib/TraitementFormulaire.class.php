<?php

/*
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

namespace lib;

/**
 * Description of TraitementFormulaire
 *
 * @author fred
 */
class TraitementFormulaire {
    
    private $POSTechappe;
   

    public function __construct($POST) {
      
      $this->POSTechappe->echappementChaine($POST);
      
      return $this->POSTechappe;
      
  }
    
    public function echappementChaine ($tableauPOST){
      
      foreach ($tableauPOST as $cle => $chaine){
          $chaine = addslashes($chaine);
      }
      
      return $tableauPOST;
      
  }
    
}
