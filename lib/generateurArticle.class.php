<?php

/*
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

namespace lib;

/**
 * Description of articleGenerateur
 *
 * @author fred
 */
class generateurArticle {
    
    public $page = 'index';
   
    private $infosConnexion;
    private $parsed_json;
    private $contenuPage;
    
    public function __construct($page = 'index') {
       
        $this->contenuPage = $this->lireContenu($page);
        
        return $this->contenuPage;
    }
    
    public function setContenuArticle ($contenu) {
        if (!empty($contenu)){
        $this->contenuHTML = addslashes($contenu);
        }
    }
    
    public function lireContenu ($page){
        
        $this->infosConnexion = file_get_contents('lang/fr_contenu.json');
        $this->parsed_json = json_decode($this->infosConnexion, true);  //renvoie un tableau avec le contenu...
        
       
        return $this->parsed_json;
    }
    
}
