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
    
    public $search = "'";
    private $replace = "\'";
    private $subject ;
        

    public function __construct() {
      
    
      
  }
    
    public function echappementChaine ($tableauPOST){
      
      $this->subject = $tableauPOST;
      
      $tableauPOSTechappe = str_replace($this->search,$this->replace ,$this->subject );
      
           
      return $tableauPOSTechappe;
      
  }
  
    public function estBienUnMail (){
      
      
    } 
    
    public function estBienNumeric (){
        
    }
    
      
  
    
}
