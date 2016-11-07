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
    public function recupererDonneesListing(){
        /* Attention à l'encodage des fichiers! (ce n'est pas de l'unicode) */
        $this->today = date('j/n/Y');
        $this->h_titre=file_get_contents('lib/tfpdf/courrier/h_titre.txt');
        $this->h_soustitre=file_get_contents('lib/tfpdf/courrier/h_soustitre.txt');
        $this->l_infos=file_get_contents('lib/tfpdf/courrier/l_infos1.txt');
        $this->l_infos2=file_get_contents('lib/tfpdf/courrier/l_infos2.txt');
        $this->c_titre=file_get_contents('lib/tfpdf/courrier/c_titre.txt');
        $this->c_objet=file_get_contents('lib/tfpdf/courrier/c_objet.txt');
        $this->c_texte_p1=file_get_contents('lib/tfpdf/courrier/c_texte_p1.txt');
        $this->c_texte_p2 =file_get_contents('lib/tfpdf/courrier/c_texte_p2.txt');
        $this->c_signature=file_get_contents('lib/tfpdf/courrier/c_signature.txt');
        $this->f_adresses=file_get_contents('lib/tfpdf/courrier/f_adresses.txt');
        
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
         
         $this->recupererDonneesListing();  //On récupère les donées pour constuire le courrier
         
         $statement = "SELECT * FROM `import_eleve_complet` WHERE `Code Structure` = ? ORDER BY `Nom de famille`,`Prénom`,`Date Naissance` ";
            $tabDatas = array ($codeStructure);
            $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
            
            //$listeVoeux = $this->listerVoeuxEleves($tableau);
            foreach ($tableau as $infosEleve){
                
                $profilEleveComplet = $this->listerVoeuxIndividuels($infosEleve['id_eleve']);
                //var_dump($profilEleveComplet[0]);
                if ($profilEleveComplet != FALSE){
                    $this->id_eleve = $profilEleveComplet[0]['id_eleve'];
                    $this->nom = $profilEleveComplet[0]['Nom de famille'];
                    $this->prenom = $profilEleveComplet[0]['Prénom'];
                    $this->classe = $profilEleveComplet[0]['Code Structure'];
                    $this->vrainom = $this->prenom.' '.$this->nom.', classe de '.$this->classe;
                    $this->c_texte_p2 = str_replace('votre enfant',$this->vrainom, $this->c_texte_p2); //pour injecter le nom de l'élève dans le courrier
                    
                    /*Construction de la liste des voeux
                      atention : il faut changer l'encodage à cause de FPDF et insérer des sauts de ligne html forcés !!*/
                   
                    foreach ($profilEleveComplet as $voeu){
                        $classt = $voeu['classement'];$nom = $voeu['nom'];$acad=$voeu['academie']; $commune = $voeu['commune'];
                        $this->voeux_total .= mb_convert_encoding(" \n- Voeux n°$classt : $nom  (Académie : $acad ; $commune .)","CP1252");
                       
                    }
                    
                    
                    $this->pdfVoeuxEleves();
                }
                else{
                    
                    
                }
                
            }     
           $this->pdf->Output();
            
           
     }
     
     public function listerVoeuxIndividuels ($id_eleve){
                $statement = "SELECT DISTINCT * FROM `validations` "
                    . "INNER JOIN etablissement ON `validations`.`id_etab`=`etablissement`.`id_etab` "
                    . "INNER JOIN `import_eleve_complet` ON `validations`.`id_eleve`=`import_eleve_complet`.`id_eleve`  WHERE `validations`.`id_eleve`= ? ORDER BY `validations`.`classement` ASC";
                $tabDatas = array ($id_eleve);
               return  $tableau = $this->db->queryPDOPrepared($statement, $tabDatas);
     
                
     }
     


    public function pdfVoeuxEleves (){
        
        
        
        $this->pdf->SetAuthor('lmautun');

        //premiere page
        $this->pdf->AddPage();
        
        //$this->pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true); //pour l'UTF-8
        //$this->pdf->SetFont('DejaVu','',11);
        $this->pdf->Image('lib/tfpdf/IMG/header_RF.png',90,6,30);
        $this->pdf->Image('lib/tfpdf/IMG/leftcol_logo_terre.png',2,30,45);
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

        $this->pdf->MultiCell (180,3,"$this->f_adresses",0,'C');

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
        $this->pdf->Image('lib/tfpdf/IMG/footer_pucelle.png',40,265,15);
        $this->pdf->SetXY(42,270);
        $this->pdf->SetFont('Times','',10);

        $this->pdf->MultiCell (180,3,"$this->f_adresses",0,'C');


        
        }

 
    }
    

