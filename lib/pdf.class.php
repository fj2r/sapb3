<?php

/*
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

namespace lib;

/**
 * Description of pdf
 *
 * @author fred
 */
class pdf {
    
    public $pdf;
    public $db;

    public $id_eleve;
    public $h_titre;
    public $h_soustitre;
    public $l_infos;
    public $l_infos2;
    public $c_titre;
    public $c_objet;
    public $c_texte_p1;
    public $c_texte_p2;
    public $c_signature;
    public $f_adresses;
    public $today;
    public $voeux_total;
    public $nom;
    public $prenom;
    public $classe;
    public $vrainom;
    
    
    
    
    public function __construct($db, $tfpdf) {
        $this->db = $db;
        $this->pdf = $tfpdf;
        
    }
    private function recupererDonneesListing(){
        /* Attention à l'encodage des fichiers! (ce n'est pas de l'unicode) */
        $this->today = date('j/n/Y');
        $this->h_titre=  utf8_decode(file_get_contents('lib/tfpdf/courrier/h_titre.txt'));
        $this->h_soustitre=utf8_decode(file_get_contents('lib/tfpdf/courrier/h_soustitre.txt'));
        $this->l_infos=utf8_decode(file_get_contents('lib/tfpdf/courrier/l_infos1.txt'));
        //$this->l_infos2=utf8_decode(file_get_contents('lib/tfpdf/courrier/l_infos2.txt'));
        $this->c_titre=utf8_decode(file_get_contents('lib/tfpdf/courrier/c_titre.txt'));
        $this->c_objet=utf8_decode(file_get_contents('lib/tfpdf/courrier/c_objet.txt'));
        $this->c_texte_p1=utf8_decode(file_get_contents('lib/tfpdf/courrier/c_texte_p1.txt'));
        $this->c_texte_p2 = utf8_decode(file_get_contents('lib/tfpdf/courrier/c_texte_p2.txt'));
        $this->c_signature=utf8_decode(file_get_contents('lib/tfpdf/courrier/c_signature.txt'));
        $this->f_adresses=utf8_decode(file_get_contents('lib/tfpdf/courrier/f_adresses.txt'));
        
    }
     public function listerVoeuxEleves ($listeEleves){
         $tableau = array();
         foreach ($listeEleves as $infosEleve){
            $statement = "SELECT DISTINCT * FROM `validations` "
                    . "INNER JOIN etablissement ON `validations`.`id_etab`=`etablissement`.`id_etab` "
                    . "INNER JOIN `import_eleve_complet` ON `validations`.`id_eleve`=`import_eleve_complet`.`id_eleve`  WHERE `validations`.`id_eleve`= ? AND `import_eleve_complet`.`Code Structure`=? ORDER BY `validations`.`classement` ASC";
            $tabDatas = array ($infosEleve['id_eleve'], $infosEleve[35]);
            $tableau [] = $this->db->queryPDOPrepared($statement, $tabDatas);
                   
         }
         
         return $tableau;
        
              
  
     }
     
     public function listeElevesParDivision ($codeStructure){
               
         
            $statement = "SELECT * FROM `import_eleve_complet` WHERE `Code Structure` = ? ORDER BY `Nom de famille`,`Prénom`,`Date Naissance` ";
            $tabDatas = array ($codeStructure);
            $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
            //var_dump($tableau);
            //$listeVoeux = $this->listerVoeuxEleves($tableau);
            foreach ($tableau as $infosEleve){
                $this->recupererDonneesListing();  //On récupère les donées pour constuire le courrier
                
                $InformationsEleve = $this->informationsEleve($infosEleve['id_eleve']);
                
///////////////////////////////////// Début de construction du courrier///////////////////////////////////////////////
                    $this->id_eleve = $InformationsEleve[0]['id_eleve'];
                    $this->nom = utf8_decode($InformationsEleve[0]['Nom de famille']) ;
                   
                    $this->prenom = utf8_decode($InformationsEleve[0]['Prénom'])  ;
                    
                    $this->classe = $InformationsEleve[0]['Code Structure'];
                    $this->vrainom = $this->prenom.' '.$this->nom.', classe de '.$this->classe;
                    
                    $this->c_texte_p2 = str_replace('votre enfant',$this->prenom, $this->c_texte_p2); //pour injecter le nom de l'élève dans le courrier
                                    
                
                    $this->voeux_total = ''; //réinitialisation des voeux
/////////////////////////////////////traitement des voeux classiques///////////////////////////////////////////////////
                $profilEleveComplet = $this->listerVoeuxIndividuels($infosEleve['id_eleve']);
/////////////////////////////////////traitement des voeux type CPGE///////////////////////////////////////////////////
                $profilEleveCPGE = $this->listerVoeuxCPGE($infosEleve['id_eleve']);
                              
/////////////////////////////////////traitement des voeux type BTS///////////////////////////////////////////////////
                $profilEleveBTS = $this->listerVoeuxBTS($infosEleve['id_eleve']);
 
                
                if ($profilEleveComplet != FALSE ){
                   
                    /*Construction de la liste des voeux
                      atention : il faut changer l'encodage à cause de FPDF et insérer des sauts de ligne html forcés !!*/
                    
                    foreach ($profilEleveComplet as $voeu){
                        $classt = $voeu['classement'];$nom = $voeu['nom'];$acad=$voeu['academie']; $commune = $voeu['commune'];
                        /*$classt=  utf8_decode($classt);
                        $nom= utf8_decode($nom);
                        $acad=  utf8_decode($acad);
                        $commune= utf8_decode($commune);
                        */
                        $this->voeux_total .= mb_convert_encoding(" \n- Voeux n°$classt : $nom  (Académie : $acad ; $commune.)","CP1252");
                       
                    }                   
                    
                }
                if ($profilEleveCPGE != FALSE){
                    foreach ($profilEleveCPGE as $voeu){
                        $classt = $voeu['classement'];$formation = $voeu['formation'];$acad=$voeu['academie']; $commune = $voeu['commune']; $nomEtab=$voeu['nom'];
                        /*$classt=  utf8_decode($classt);
                        $formation= utf8_decode($formation);
                        $acad=  utf8_decode($acad);
                        $commune= utf8_decode($commune);
                        $nomEtab = utf8_decode($nomEtab);*/
                        
                        $this->voeux_total .= mb_convert_encoding(" \n- Voeux n°$classt : $formation  ($nomEtab - Académie : $acad ; $commune.)","CP1252");
                       
                    }  
                }
                
                if ($profilEleveBTS != FALSE)    {
                    foreach ($profilEleveBTS as $voeu){
                        $classt = $voeu['classement'];$secteur = $voeu['secteur'];$type =$voeu['type']; 
                        /*$classt=  utf8_decode($classt);
                        $secteur= utf8_decode($secteur);
                        $type=  utf8_decode($type);*/
                                                
                        $this->voeux_total .= mb_convert_encoding(" \n- Voeux n°$classt : $type  (Secteur de formation : $secteur.)","CP1252");
                       
                    }  
                }
                    
                $this->pdfVoeuxEleves();
                $this->voeux_total = '';
                }   

                

           $this->pdf->Output();
            
           
     }
     
     public function informationsEleve ($id_eleve){
         $statement =" SELECT * FROM `import_eleve_complet` WHERE `import_eleve_complet`.`id_eleve`= ?";
         $tabDatas=array($id_eleve);
         $infosEleve = $this->db->queryPDOPrepared($statement,$tabDatas);
         return $infosEleve;
     }
     
     public function listerVoeuxIndividuels ($id_eleve){
                
                $statement = "SELECT DISTINCT * FROM `validations` "
                    . "INNER JOIN etablissement ON `validations`.`id_etab`=`etablissement`.`id_etab` "
                    . "INNER JOIN `import_eleve_complet` ON `validations`.`id_eleve`=`import_eleve_complet`.`id_eleve`  WHERE `validations`.`id_eleve`= ? ORDER BY `validations`.`classement` ASC";
                $tabDatas = array ($id_eleve);
             
          $tableau = $this->db->queryPDOPrepared($statement, $tabDatas);
          
          if ($tableau != NULL){ return $tableau; }
          else {return $tableau=array(); }   
     
                
     }
     public function listerVoeuxCPGE ($id_eleve){
         $tableau=array();
                $statement = "SELECT DISTINCT * FROM `validations` "
                    . "INNER JOIN etablissement_cpge ON `validations`.`id_etab`=`etablissement_cpge`.`id_etab` "
                    . "INNER JOIN `import_eleve_complet` ON `validations`.`id_eleve`=`import_eleve_complet`.`id_eleve`  WHERE `validations`.`id_eleve`= ? ORDER BY `validations`.`classement` ASC";
                $tabDatas = array ($id_eleve);
          $tableau = $this->db->queryPDOPrepared($statement, $tabDatas);
         if ($tableau != NULL){ return $tableau; }
          else {return $tableau=array(); } 
                
     }
     public function listerVoeuxBTS ($id_eleve){
         $tableau=array();
                $statement = "SELECT DISTINCT * FROM `validations` "
                    . "INNER JOIN filieres_bts ON `validations`.`id_etab`=`filieres_bts`.`id_etab` "
                    . "INNER JOIN `import_eleve_complet` ON `validations`.`id_eleve`=`import_eleve_complet`.`id_eleve`  WHERE `validations`.`id_eleve`= ? ORDER BY `validations`.`classement` ASC";
                $tabDatas = array ($id_eleve);
          $tableau = $this->db->queryPDOPrepared($statement, $tabDatas);
          if ($tableau != NULL){ return $tableau; }
          else {return $tableau=array(); } 
                
     }


    public function pdfVoeuxEleves (){
        
        
        
        $this->pdf->SetAuthor('lmautun');

        //premiere page
        $this->pdf->AddPage();
        
        //$this->pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true); //pour l'UTF-8
        //$this->pdf->SetFont('DejaVu','',11);
        $this->pdf->Image('lib/fpdf/IMG/header_RF.png',90,6,30);
        $this->pdf->Image('lib/fpdf/IMG/leftcol_logo_terre.png',2,30,45);
        $this->pdf->SetFont('Arial','I',8);
        $this->pdf->SetXY(10,55);
        $this->pdf->MultiCell(35,3,"$this->l_infos",0,'J');
        $this->pdf->SetXY(10,70);
        $this->pdf->MultiCell(35,3,"$this->l_infos2",0,'R');

        //$this->pdf->Ln();
        $this->pdf->SetFont('Times','B',11);

        $this->pdf->SetXY(65,25);
        $this->pdf->MultiCell(80,5,"$this->h_titre",0,'C');

        $this->pdf->SetFont('Times','',10);

        $this->pdf->SetXY(120,40);
        $this->pdf->MultiCell (90,5,"$this->h_soustitre",'J');
        $this->pdf->SetXY(140,40);
        $this->pdf->MultiCell (90, 5, "$this->today", 'J');
        $this->pdf->SetXY(100,50);
        $this->pdf->SetFont('Times','',20);

        $this->pdf->SetXY(80,75);
        $this->pdf->MultiCell (120,8,"$this->c_titre",0,'C');

        $this->pdf->SetFont('Times','',10);

        $this->pdf->SetXY(20,110);
        $this->pdf->MultiCell (90,5,"$this->c_objet",'J');
        $this->pdf->SetXY(60+75,109);
        $this->pdf->MultiCell (60,7,"$this->vrainom",'J');

        $this->pdf->SetXY(20,120);
        $this->pdf->MultiCell (180,6,"$this->c_texte_p1",'J');

        //le footer
        $this->pdf->Image('lib/fpdf/IMG/footer_pucelle.png',25,260,15);
        $this->pdf->SetXY(30,260);
        $this->pdf->SetFont('Times','',10);

        $this->pdf->MultiCell (180,5,"$this->f_adresses",0,'C');

        //deuxieme page
        $this->pdf->AddPage();
        $this->pdf->SetXY(20,15);
        $this->pdf->MultiCell (180,7,"$this->c_texte_p2",0,'J');
        $this->pdf->Ln(1);
        //les voeux
        $this->pdf->SetXY(20,75);
        $this->pdf->SetFont('Times','',10);
        $this->pdf->MultiCell (180,7,"$this->voeux_total",0,'J');

        //le footer
        $this->pdf->Image('lib/fpdf/IMG/footer_pucelle.png',40,265,15);
        $this->pdf->SetXY(42,270);
        $this->pdf->SetFont('Times','',10);

        $this->pdf->MultiCell (180,3,"$this->f_adresses",0,'C');


        
        }

 
    }
    

