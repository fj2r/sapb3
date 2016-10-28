<?php

namespace lib;

class Etablissement {

    protected $dom;
    protected $racine;
    protected $fichierXML;
    protected $tag;
    protected $db;
    protected $pdo;
    protected $form;
    protected $nomTable;
    protected $maBase;
    private $UAI;
    private $type;
    private $nom;
    private $sigle;
    private $statut;
    private $tutelle;
    private $universite;
    private $adresse;
    private $cp;
    private $commune;
    private $departement;
    private $academie;
    private $region;
    private $longitude;
    private $latitude;
    private $lien;
    private $requete;
    private $datas;

    public function __construct ($db){
        
        $this->db = $db ;
        $this->pdo = $db->getPDO();
        

    }


    public function setDom (){
            $this->dom = new DomDocument();

            return $this->dom ;
    }
    public function lireXML($fichierXML) {
            $this->dom->load($fichierXML);

            $this->racine = $this->dom->documentElement ;
            echo $this->racine->nodeName.'<hr />';

    }
    public function afficherEtablissement ($attr){
            if ($this->dom->getElementsByTagName($attr)){
            $this->tag = $this->dom->getElementsByTagName($attr);
            }

            foreach ($this->tag as $liste){
                    if (@$liste->firstChild->nodeValue != NULL) {
                        if ($attr =='lien'){
                        echo '<a href="'.@$liste->firstChild->nodeValue.'" target="_blank">'.@$liste->firstChild->nodeValue.'</a>'; echo '<br />';	
                        }
                        else {
                        echo @$liste->firstChild->nodeValue; echo '<br />'; 

                        }

                    }
                    else {echo '------<br/>';}

            }


    }
    
////////////////////////////////Mutateurs///////////////////////////////////
    public function setUAI ($data){
        $this->UAI = $data;
    }
    public function setType ($data){
        $this->type= $data;
    }
    public function setNom ($data){
        $this->nom= $data;
    }
    public function setSigle ($data){
        $this->sigle= $data;
    }
    public function setStatut ($data){
        $this->statut= $data;
    }
    public function setTutelle ($data){
        $this->tutelle= $data;
    }
    public function setUniversite ($data){
        $this->universite= $data;
    }
    public function setAdresse ($data){
        $this->adresse= $data;
    }
    public function setCp ($data){
        $this->cp= $data;
    }
    public function setCommune ($data){
        $this->commune= $data;
    }
    public function setDepartement ($data){
        $this->departement= $data;
    }
    public function setAcademie ($data){
        $this->academie= $data;
    }
    public function setRegion ($data){
        $this->region= $data;
    }
    public function setLongitude ($data){
        $this->longitude= $data;
    }
    public function setLatitude ($data){
        $this->latitude= $data;
    }
    public function setLien ($data){
        $this->lien= $data;
    }
////////////////////////////////Accesseurs//////////////////////////////////

    public function getUAI (){
        return $this->UAI;
    }
    public function getType (){
        return $this->type;
    }
    public function getNom (){
        return $this->nom;
    }
    public function getSigle (){
        return $this->sigle;
    }
    public function getStatut (){
        return $this->statut;
    }
    public function getTutelle (){
        return $this->tutelle;
    }
    public function getUniversite (){
        return $this->universite;
    }
    public function getAdresse (){
        return $this->adresse;
    }
    public function getCp (){
        return $this->cp;
    }
    public function getCommune (){
        return $this->commune;
    }
    public function getDepartement (){
        return $this->departement;
    }
    public function getAcademie (){
        return $this->academie;
    }
    public function getRegion (){
        return $this->region;
    }
    public function getLongitude (){
        return $this->longitude;
    }
    public function getLatitude (){
        return $this->latitude;
    }
    public function getLien (){
        return $this->lien;
    }


    public function exportEtablissementBdd () {


        $listeChampsARemplir = array('UAI','type','nom','sigle','statut','tutelle','universite', 'adresse', 'cp', 'commune','departement',
            'academie', 'region', 'longitude_X', 'latitude_Y', 'lien');
        $champs = implode(',', $listeChampsARemplir);
        $listeValeursARemplir = array();

        $etablissementList = $this->dom->getElementsByTagName('etablissement');

        foreach ($etablissementList as $itemEtab){
            $listeValeursARemplir = array();
            $quote = '\'';

            $UAI = $itemEtab->getElementsByTagName('UAI');
            if ($UAI->length >0){
              $listeValeursARemplir [] = $quote.$UAI->item(0)->nodeValue.$quote;

            }
            else {echo 'NULL';}
            $type = $itemEtab->getElementsByTagName('type');
            if ($type->length >0){
              $listeValeursARemplir [] = $quote.addslashes(($type->item(0)->nodeValue)).$quote;

            }
            else {echo 'NULL';}
            $nom = $itemEtab->getElementsByTagName('nom');
            if ($nom->length >0){
             $listeValeursARemplir [] =  $quote.addslashes(($nom->item(0)->nodeValue)).$quote;

            }
            else {echo 'NULL';}
            $sigle = $itemEtab->getElementsByTagName('sigle');
            if ($sigle->length >0){
              $listeValeursARemplir [] = $quote.addslashes(($sigle->item(0)->nodeValue)).$quote;

            }
            else {echo 'NULL';}
            $statut = $itemEtab->getElementsByTagName('statut');
            if ($statut->length >0){
              $listeValeursARemplir [] = $quote.addslashes(($statut->item(0)->nodeValue)).$quote;

            }
            else {echo 'NULL';}
            $tutelle = $itemEtab->getElementsByTagName('tutelle');
            if ($tutelle->length >0){
              $listeValeursARemplir [] = $quote.addslashes(($tutelle->item(0)->nodeValue)).$quote;

            }
            else {echo 'NULL';}
            $universite = $itemEtab->getElementsByTagName('universite');
            if ($universite->length >0){
             $listeValeursARemplir [] =  $quote.addslashes(($universite->item(0)->nodeValue)).$quote;

            }
            else {echo 'NULL';}
            $adresse = $itemEtab->getElementsByTagName('adresse');
            if ($adresse->length >0){
             $listeValeursARemplir [] =  $quote.addslashes(($adresse->item(0)->nodeValue)).$quote;

            }
            else {echo 'NULL';}
            $cp = $itemEtab->getElementsByTagName('cp');
            if ($cp->length >0){
              $listeValeursARemplir [] = $quote.$cp->item(0)->nodeValue.$quote;

            }
            else {echo 'NULL';}
            $commune = $itemEtab->getElementsByTagName('commune');
            if ($commune->length >0){
             $listeValeursARemplir [] =  $quote.addslashes(($commune->item(0)->nodeValue)).$quote;

            }
            else {echo 'NULL';}
            $departement = $itemEtab->getElementsByTagName('departement');
            if ($departement->length >0){
             $listeValeursARemplir [] =  $quote.addslashes(($departement->item(0)->nodeValue)).$quote;

            }
            else {echo 'NULL';}
            $academie = $itemEtab->getElementsByTagName('academie');
            if ($academie->length >0){
              $listeValeursARemplir [] = $quote.addslashes(($academie->item(0)->nodeValue)).$quote;

            }
            else {echo 'NULL';}
            $region = $itemEtab->getElementsByTagName('region');
            if ($region->length >0){
             $listeValeursARemplir [] =  $quote.addslashes(($region->item(0)->nodeValue)).$quote;

            }
            else {echo 'NULL';}
            $longitude_X = $itemEtab->getElementsByTagName('longitude_X');
            if ($longitude_X->length >0){
             $listeValeursARemplir [] =  $quote.$longitude_X->item(0)->nodeValue.$quote;

            }
            else {echo 'NULL';}
            $latitude_Y = $itemEtab->getElementsByTagName('latitude_Y');
            if ($latitude_Y->length >0){
              $listeValeursARemplir [] = $quote.$latitude_Y->item(0)->nodeValue.$quote;

            }
            else {echo 'NULL';}
            $lien = $itemEtab->getElementsByTagName('lien');
            if ($lien->length >0){
             $listeValeursARemplir [] =  $quote.addslashes(($lien->item(0)->nodeValue)).$quote;

            }
            else {echo 'NULL';}

            echo $valeursChamps = implode(',', $listeValeursARemplir);


            $this->maBase->query("INSERT INTO etablissement ($champs) VALUES ($valeursChamps)");
            echo 'enregistrement fait pour : '.$champs.'/'.$valeursChamps;

        }



        echo '<br/>';
        /*foreach ($listeChampsARemplir as $element){
            $this->tag = $this->dom->getElementsByTagName($element);

            foreach($this->tag as $liste){

                $enregistementValeur = @$liste->firstChild->nodeValue;
                $this->maBase->query("INSERT INTO etablissement ($element) VALUES ('$enregistrementValeur')");
                echo 'enregistrement fait pour : '.$element.'/'.$enregistementValeur;

            }

        }*/


        $reponse = $this->maBase->query('SELECT * from import_eleves');
        while ( $contenu = $reponse->fetch()){
            echo $contenu['nom'].'<br/>';
        }


    // $this->maBase->query('INSERT INTO etablissement VALUES ()')
    }

    public function rechercherEtablissement ($enregistrement,$champ,$value,$champTri ='nom'){

        $statement = "SELECT $enregistrement FROM etablissement WHERE `$champ` = ? ORDER BY `$champTri` ASC ";
        $tabDatas = array ($value);
        $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
        
        return $tableau;



        }
    
    public function listerEtablissement ($enregistrement,$champ1,$champ2,$values=array(),$champTri ='nom'){

        $statement = "SELECT $enregistrement FROM etablissement WHERE `$champ1` = ? AND `$champ2`= ? ORDER BY `$champTri` ASC ";
        $tabDatas = $values;
        $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
        
        return $tableau;



        }



    public  function formEtablissement ($champ, $tri){
        
        /* recherche des valeurs d'un champ passé comme  */
        $statement = "SELECT DISTINCT $champ FROM etablissement ORDER BY `$tri` ASC ";
        $tabDatas = array ();
        $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
        
        $tableauPourOption = array();
        foreach ($tableau as $tableauFils)  {
           $tableauPourOption[] =  $tableauFils[$champ];
           
        }   
        
        
       
        return $tableauPourOption;
        

    }
}
?>