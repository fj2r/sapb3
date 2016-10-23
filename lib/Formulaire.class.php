<?php

/*
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

namespace lib;

/**
 * Description of Formulaire
 *
 * @author fred
 */
class Formulaire {
 
   private $data = array();
   private $html = '';
   public $label = 'Valider';
   public $surround = 'p'; //pour générer des tag propres autour des champs
   public $size = 25;
   public $titre = 'formulaire';
   public $submit = 'inscription.php';
   public $argumentsURL = ''; //pour le passage d'arguments en GET
   public $method = 'POST';
   public $type = 'text';
    
   public function __construct($data=array()) {
       $this->data = $data ;
       
   } 
   private function surround ($html){
       return "<{$this->surround}>{$html}</{$this->surround}>"; //pour entourer les champs avec le bon tag
   }
   private function getValue ($index){
      return  isset($this->data[$index]) ? $this->data[$index] : null ; //pour retourner la valeur du champ, piquée dans le tableau passé en paramètre à l'objet
      
   }
   private function getOptionValue ($type){
       
       if ($type == "jj"){
           $i=0; $this->html = '';
            for ($i=1; $i<=31; $i++){
                $this->html.='<option value="'.$i.'">'.$i.'</option>';
            }
            return $this->html; 
       }
       elseif ($type == "mm"){
           $i=0;
           $this->html = '';
            for ($i=1; $i<=12; $i++){
                $this->html.='<option value="'.$i.'">'.$i.'</option>';
            }
            return $this->html; 
       }
       elseif ($type == "aaaa"){
           $anneeEnCours=date('Y');
           $i=0; $this->html = '';
            for ($i=($anneeEnCours-25); $i<=$anneeEnCours; $i++){
                $this->html.='<option value="'.$i.'">'.$i.'</option>';
            }
            return $this->html; 
       }
       else {
           return null;
       }
       
       
   }
   
   public function input ($nomChamp){
      return $this->surround('<input type="text" name="'.$nomChamp.'" value="'.$this->getValue($nomChamp).'" type="'.$this->type.'" size="'.$this->size.'" '
              . 'onFocus="if(this.value==\''.$this->getValue($nomChamp).'\') {this.value=\'\'}" ></input> ');
   }
   public function select ($nomChamp){
       return $this->surround('<select name="'.$nomChamp.'">'.$this->getOptionValue($nomChamp).'</select>');
   }
   public function submit (){
      return $this->surround('<button type = "submit">'.$this->label.'</button>');
   }
    
}
