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
    private $h_titre;
    private $h_soustitre;
    private $l_infos;
    private $l_infos2;
    private $c_titre;
    private $c_objet;
    private $c_texte_p1;
    private $c_texte_p2;
    private $c_signature;
    private $f_adresses;
    private $h_titre_post_commission;
    private $h_soustitre_post_commission;
    private $l_infos_post_commission;
    private $l_infos2_post_commission;
    private $c_titre_post_commission;
    private $c_objet_post_commission;
    private $c_texte_p1_post_commission;
    private $c_texte_p2_post_commission;
    private $c_signature_post_commission;
    private $f_adresses_post_commission;
    public $today;
    public $voeux_total;
    public $voeu_avis;
    public $nom;
    public $prenom;
    public $classe;
    public $vrainom;
    public $type = 'pdfPreCommission';
    
    
    
    public function __construct($db, $tfpdf) {
        $this->db = $db;
        $this->pdf = $tfpdf;
        
    }
    public function setType ($type){
        $this->type = $type;
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
        
        //pour le courrier post-commission
        $this->h_titre_post_commission=  utf8_decode(file_get_contents('lib/tfpdf/courrier_parents/h_titre.txt'));
        $this->h_soustitre_post_commission=utf8_decode(file_get_contents('lib/tfpdf/courrier_parents/h_soustitre.txt'));
        $this->l_infos_post_commission=utf8_decode(file_get_contents('lib/tfpdf/courrier_parents/l_infos1.txt'));
        //$this->l_infos2=utf8_decode(file_get_contents('lib/tfpdf/courrier_parents/l_infos2.txt'));
        $this->c_titre_post_commission=utf8_decode(file_get_contents('lib/tfpdf/courrier_parents/c_titre.txt'));
        $this->c_objet_post_commission=utf8_decode(file_get_contents('lib/tfpdf/courrier_parents/c_objet.txt'));
        $this->c_texte_p1_post_commission=utf8_decode(file_get_contents('lib/tfpdf/courrier_parents/c_texte_p1.txt'));
        $this->c_texte_p2_post_commission = utf8_decode(file_get_contents('lib/tfpdf/courrier_parents/c_texte_p2.txt'));
        $this->c_signature_post_commission=utf8_decode(file_get_contents('lib/tfpdf/courrier_parents/c_signature.txt'));
        $this->f_adresses_post_commission=utf8_decode(file_get_contents('lib/tfpdf/courrier_parents/f_adresses.txt'));
        
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
     public function listeProfesseursEleve ($codeStructure){
         $statement = "SELECT DISTINCT `nomComplet` FROM `attribution_matieres` WHERE `Code Structure` = ? AND `matiere` != 'TUTORAT' ORDER BY `nomComplet` ASC ";
            $tabDatas = array ($codeStructure);
            $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
            
         return $tableau ;
     }
     
     public function listeElevesParDivision ($codeStructure){
               
         
            $statement = "SELECT * FROM `import_eleve_complet` WHERE `Code Structure` = ? ORDER BY `Nom de famille`,`Prénom`,`Date Naissance` ";
            $tabDatas = array ($codeStructure);
            $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
            //var_dump($tableau);
            //$listeVoeux = $this->listerVoeuxEleves($tableau);
            //$listeProf = $this->listeProfesseursEleve($this->classe);
            //var_dump($listeProf);
            foreach ($tableau as $infosEleve){
                $this->recupererDonneesListing();  //On récupère les donées pour constuire le courrier
                
                $InformationsEleve = $this->informationsEleve($infosEleve['id_eleve']);
                
///////////////////////////////////// Début de construction du courrier///////////////////////////////////////////////
                    $this->id_eleve = $InformationsEleve[0]['id_eleve'];
                    $this->nom = utf8_decode($InformationsEleve[0]['Nom de famille']) ;
                   
                    $this->prenom = utf8_decode($InformationsEleve[0]['Prénom'])  ;
                    
                    $this->classe = $InformationsEleve[0]['Code Structure'];
                    
                    
                    $this->vrainom = $this->prenom.' '.$this->nom.', classe de '.$this->classe;
                    
                    if($this->type== 'pdfPreCommission'){
                        $this->c_texte_p2 = str_replace('votre enfant',$this->prenom, $this->c_texte_p2); //pour injecter le nom de l'élève dans le courrier
                    }
                    if ($this->type == 'pdfEnvoi'){
                        $this->c_texte_p2 = str_replace('votre enfant',$this->prenom, $this->c_texte_p2_post_commission); //pour injecter le nom de l'élève dans le courrier
  
                    }                
                
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
                        $idVoeu = $voeu['id_voeu'];
                        /*$classt=  utf8_decode($classt);
                        $nom= utf8_decode($nom);
                        $acad=  utf8_decode($acad);
                        $commune= utf8_decode($commune);
                        */
                        
                        $this->voeux_total .= mb_convert_encoding(" \n- Voeu n°$classt : $nom  (Académie : $acad ; $commune.)","CP1252");
                        if ($this->type == 'pdfEnvoi')    {
                            $this->voeux_total .= $this->afficherAvisCommission($idVoeu);
                        }
                        if ($this->type == 'pdfPostCommission')    {
                            $listeProf = $this->listeProfesseursEleve($this->classe);
                            foreach ($listeProf as $prof)    {
                                //var_dump($prof['nomComplet']);
                                $this->voeux_total .= $this->afficherAvisEquipePedago($idVoeu,$prof['nomComplet']);
                            }
                            
                        }  
                    }                   
                    
                }
                if ($profilEleveCPGE != FALSE){
                    foreach ($profilEleveCPGE as $voeu){
                        $listeProf = $this->listeProfesseursEleve($this->classe);
                        $classt = $voeu['classement'];$formation = $voeu['formation'];$acad=$voeu['academie']; $commune = $voeu['commune']; $nomEtab=$voeu['nom'];
                         $idVoeu = $voeu['id_voeu'];
                        /*$classt=  utf8_decode($classt);
                        $formation= utf8_decode($formation);
                        $acad=  utf8_decode($acad);
                        $commune= utf8_decode($commune);
                        $nomEtab = utf8_decode($nomEtab);*/
                        
                        $this->voeux_total .= mb_convert_encoding(" \n- Voeu n°$classt : $formation  ($nomEtab - Académie : $acad ; $commune.)","CP1252");
                      if ($this->type == 'pdfEnvoi')    {
                            $this->voeux_total .= $this->afficherAvisCommission($idVoeu);
                        }
                      if ($this->type == 'pdfPostCommission')    {
                           foreach ($listeProf as $prof)    {
                                //var_dump($prof['nomComplet']);
                                $this->voeux_total .= $this->afficherAvisEquipePedago($idVoeu,$prof['nomComplet']);
                            }
                        }  
                    }  
                }
                
                if ($profilEleveBTS != FALSE)    {
                    foreach ($profilEleveBTS as $voeu){
                        $listeProf = $this->listeProfesseursEleve($this->classe);
                        $classt = $voeu['classement'];$secteur = $voeu['secteur'];$type =$voeu['type']; 
                         $idVoeu = $voeu['id_voeu'];
                        /*$classt=  utf8_decode($classt);
                        $secteur= utf8_decode($secteur);
                        $type=  utf8_decode($type);*/
                                                
                        $this->voeux_total .= mb_convert_encoding(" \n- Voeu n°$classt : $type  (Secteur de formation : $secteur.)","CP1252");
                        if ($this->type == 'pdfEnvoi')    {
                            $this->voeux_total .= $this->afficherAvisCommission($idVoeu);
                        }
                        if ($this->type == 'pdfPostCommission')    {
                            foreach ($listeProf as $prof)    {
                                //var_dump($prof['nomComplet']);
                                $this->voeux_total .= $this->afficherAvisEquipePedago($idVoeu,$prof['nomComplet']);
                            }
                        }  
                    }  
                }
                if ($this->type == 'pdfPreCommission')    {
                    $this->pdfVoeuxEleves();
                }
                elseif ($this->type == 'pdfEnvoi' OR $this->type == 'pdfPostCommission'){
                    $this->pdfVoeuxEleves_post_commission();
                }
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

     private function recupererAvisCommission ($idVoeu){
         $statement = "SELECT * FROM `decision_commission` WHERE `id_voeu` = ?";
         $tabDatas = array ($idVoeu);
         $tableau = $this->db->queryPDOPrepared($statement, $tabDatas);
         
         if ($tableau != NULL){ return $tableau; }
         else {return $tableau=array(); } 
         
     }
     
     private function afficherAvisCommission ($idVoeu){
         //on récupère les avis de la commission s'ils existent
        $avisCommission = $this->recupererAvisCommission($idVoeu);
        $voeu_avis = mb_convert_encoding("\n>>>>La commission n'a pas émis d'avis pour ce voeu.","CP1252");
                        if ($avisCommission != NULL){
                            foreach ( $avisCommission as $decision){
                                $decisionCommission = $decision['decision_commission'];
                                $avisCommission = $decision['avis_commission'];
                            }
                        $voeu_avis = mb_convert_encoding(" \n>>>> Avis de la commission : $decisionCommission. \n $avisCommission ","CP1252");

                        }
        return ($voeu_avis);            
     }
     
     private function recupererAvisEquipePedago ($id_voeu, $nomComplet){
         $statement = "SELECT * FROM `validations`".
               " INNER JOIN `analyse_voeux` ON `validations`.`id_voeu` = `analyse_voeux`.`id_voeu` "
               . "INNER JOIN `professeurs` ON `analyse_voeux`.`id_pedago`=`professeurs`.`id_pedago` "
               . " WHERE `validations`.`id_eleve` = ? AND `validations`.`id_voeu` = ? AND `professeurs`.`nomComplet` = ?";
       
       $tabDatas = array ($this->id_eleve, $id_voeu, $nomComplet);
        //var_dump($this->id_eleve);
        $tableau  = $this->db->queryPDOPrepared($statement, $tabDatas);
        
        
         if ($tableau != NULL){ return $tableau; }
         else {return $tableau=array(); } 
     }
     
     private function afficherAvisEquipePedago ($idVoeu, $nomComplet){
         
         
        
         $avisProfesseurs = $this->recupererAvisEquipePedago($idVoeu, $nomComplet);
         //var_dump($avisProfesseurs);
         //var_dump($nomComplet);
         $voeu_avisPedago = mb_convert_encoding("\n>>>> $nomComplet : Pas encore d'avis pour ce voeu.","CP1252");
            if ($avisProfesseurs != NULL){
                foreach ($avisProfesseurs as $avis){
                    
                    $nomComplet = $avis['nomComplet'] ;
                    //var_dump($avis);
                    $avisEmis = $avis['avis'];
                    
                    
                    $voeu_avisPedago = mb_convert_encoding(" \n>>>> $nomComplet  : $avisEmis.","CP1252");
                    
                    
                }
            }
         
        return $voeu_avisPedago;
     }
     
////////// CONSTRUCTION DES PDF //////////////////////////////////////////////////////////////////////////////////////////
     
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

 
    public function pdfVoeuxEleves_post_commission (){
        
        
        
        $this->pdf->SetAuthor('lmautun');

        //premiere page
        $this->pdf->AddPage();
        
        //$this->pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true); //pour l'UTF-8
        //$this->pdf->SetFont('DejaVu','',11);
        $this->pdf->Image('lib/fpdf/IMG/header_RF.png',90,6,30);
        $this->pdf->Image('lib/fpdf/IMG/leftcol_logo_terre.png',2,30,45);
        $this->pdf->SetFont('Arial','I',8);
        $this->pdf->SetXY(10,55);
        $this->pdf->MultiCell(35,3,"$this->l_infos_post_commission",0,'J');
        $this->pdf->SetXY(10,70);
        $this->pdf->MultiCell(35,3,"$this->l_infos2_post_commission",0,'R');

        //$this->pdf->Ln();
        $this->pdf->SetFont('Times','B',11);

        $this->pdf->SetXY(65,25);
        $this->pdf->MultiCell(80,5,"$this->h_titre_post_commission",0,'C');

        $this->pdf->SetFont('Times','',10);

        $this->pdf->SetXY(120,40);
        $this->pdf->MultiCell (90,5,"$this->h_soustitre_post_commission",'J');
        $this->pdf->SetXY(140,40);
        $this->pdf->MultiCell (90, 5, "$this->today", 'J');
        $this->pdf->SetXY(100,50);
        $this->pdf->SetFont('Times','',20);

        $this->pdf->SetXY(80,75);
        $this->pdf->MultiCell (120,8,"$this->c_titre_post_commission",0,'C');

        $this->pdf->SetFont('Times','',10);

        $this->pdf->SetXY(20,110);
        $this->pdf->MultiCell (90,5,"$this->c_objet_post_commission",'J');
        $this->pdf->SetXY(60+75,109);
        $this->pdf->MultiCell (60,7,"$this->vrainom",'J');

        $this->pdf->SetXY(20,120);
        $this->pdf->MultiCell (180,6,"$this->c_texte_p1_post_commission",'J');

        //le footer
        $this->pdf->Image('lib/fpdf/IMG/footer_pucelle.png',25,260,15);
        $this->pdf->SetXY(30,260);
        $this->pdf->SetFont('Times','',10);

        $this->pdf->MultiCell (180,5,"$this->f_adresses",0,'C');

        //deuxieme page
        $this->pdf->AddPage();
        $this->pdf->SetXY(20,15);
        $this->pdf->SetFont('Times','I',12);
        $this->pdf->MultiCell (180,7,"$this->c_texte_p2_post_commission",0,'J');
        $this->pdf->Ln(1);
        //les voeux
        $this->pdf->SetXY(20,25);
        $this->pdf->SetFont('Times','',10);
        $this->pdf->MultiCell (180,7,"$this->voeux_total",0,'J');

        //le footer
        $this->pdf->Image('lib/fpdf/IMG/footer_pucelle.png',40,265,15);
        $this->pdf->SetXY(36,270);
        $this->pdf->SetFont('Times','',10);

        $this->pdf->MultiCell (180,3,"$this->f_adresses",0,'C');


        
        }

    
    public function pdfVoeuxElevesPourTuteurs (){
        
        
        
        $this->pdf->SetAuthor('lmautun');

        //premiere page
        $this->pdf->AddPage();
        
        //$this->pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true); //pour l'UTF-8
        //$this->pdf->SetFont('DejaVu','',11);
        $this->pdf->Image('lib/fpdf/IMG/header_RF.png',90,6,30);
        $this->pdf->Image('lib/fpdf/IMG/leftcol_logo_terre.png',2,30,45);
        $this->pdf->SetFont('Arial','I',8);
        $this->pdf->SetXY(10,55);
        $this->pdf->MultiCell(35,3,"$this->l_infos_post_commission",0,'J');
        $this->pdf->SetXY(10,70);
        $this->pdf->MultiCell(35,3,"$this->l_infos2_post_commission",0,'R');

        //$this->pdf->Ln();
        $this->pdf->SetFont('Times','B',11);

        $this->pdf->SetXY(65,25);
        $this->pdf->MultiCell(80,5,"$this->h_titre_post_commission",0,'C');

        $this->pdf->SetFont('Times','',10);

        $this->pdf->SetXY(120,40);
        $this->pdf->MultiCell (90,5,"$this->h_soustitre_post_commission",'J');
        $this->pdf->SetXY(140,40);
        $this->pdf->MultiCell (90, 5, "$this->today", 'J');
        $this->pdf->SetXY(100,50);
        $this->pdf->SetFont('Times','',20);

        $this->pdf->SetXY(80,75);
        $this->pdf->MultiCell (120,8,"$this->c_titre_post_commission",0,'C');

        $this->pdf->SetFont('Times','',10);

        $this->pdf->SetXY(20,110);
        $this->pdf->MultiCell (90,5,"$this->c_objet_post_commission",'J');
        $this->pdf->SetXY(60+75,109);
        $this->pdf->MultiCell (60,7,"$this->vrainom",'J');

        $this->pdf->SetXY(20,120);
        $this->pdf->MultiCell (180,6,"$this->c_texte_p1_post_commission",'J');

        //le footer
        $this->pdf->Image('lib/fpdf/IMG/footer_pucelle.png',25,260,15);
        $this->pdf->SetXY(30,260);
        $this->pdf->SetFont('Times','',10);

        $this->pdf->MultiCell (180,5,"$this->f_adresses",0,'C');

        //deuxieme page
        $this->pdf->AddPage();
        $this->pdf->SetXY(20,15);
        $this->pdf->SetFont('Times','I',12);
        $this->pdf->MultiCell (180,7,"$this->c_texte_p2_post_commission",0,'J');
        $this->pdf->Ln(1);
        //les voeux
        $this->pdf->SetXY(20,25);
        $this->pdf->SetFont('Times','',10);
        $this->pdf->MultiCell (180,7,"$this->voeux_total",0,'J');

        //le footer
        $this->pdf->Image('lib/fpdf/IMG/footer_pucelle.png',40,265,15);
        $this->pdf->SetXY(36,270);
        $this->pdf->SetFont('Times','',10);

        $this->pdf->MultiCell (180,3,"$this->f_adresses",0,'C');


        
        }

 
    }
    

