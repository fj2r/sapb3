<?php

/*
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

/**
 * Description of infos
 *
 * @author fred
 */
class infos {
    
    protected $fichierConfig;
    protected $fichierInfos;
    protected $domConfig;
    protected $domInfos;


    protected $bdd;    
    protected $adresse_postale;
    protected $cp;
    protected $commune;
    protected $pays;
    protected $mail;
    protected $telephone;   
    protected $proviseur;
    protected $proviseur_adjoint;
    protected $administrateur;
    
    protected $nomAppli;
    protected $version;
    protected $dev;
    protected $licence;
    protected $auteur;
    
    public function __construct() {
        $this->fichierConfig = 'admin/config.xml';
        $this->fichierInfos ='admin/infos.xml';
    }
    
    public function lireInfos (){
    $arrayInfoEtab = [];    //tableau qu'on va renvoyer
    
    $this->domConfig = new DomDocument ();
    $this->domInfos = new DomDocument ();
    $this->domConfig->load($this->fichierConfig);
    $this->domInfos->load($this->fichierInfos);
    
    $objDom = $this->domConfig->getElementsByTagName('informations_etablissement');	
    $listeDom = $objDom->item(0);
    $listeChild = $listeDom->childNodes;
    
    foreach ($listeChild as $liste){
       $arrayInfoEtab [$liste->nodeName] = $liste->nodeValue;
        }
    
    $objDom2 = $this->domConfig->getElementsByTagName('informations_administration');	
    $listeDom2 = $objDom2->item(0);
    $listeChild2 = $listeDom2->childNodes;
    foreach ($listeChild2 as $liste){
       $arrayInfoEtab [$liste->nodeName] = $liste->nodeValue;
        }
        
    $objDom3 = $this->domInfos->getElementsByTagName('infos_produit');	
    $listeDom3 = $objDom3->item(0);
    $listeChild3 = $listeDom3->childNodes;
    foreach ($listeChild3 as $liste){
       $arrayInfoEtab [$liste->nodeName] = $liste->nodeValue;
        }
    //print_r($arrayInfoEtab);
        
        
    if (session_status() === PHP_SESSION_ACTIVE ){
        
        $_SESSION = $arrayInfoEtab;
    }
   // print_r($_SESSION);
    
    return  $arrayInfoEtab; 
    }
    
     
    
}
