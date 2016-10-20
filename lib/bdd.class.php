<?php

class bdd {

    protected $recherche;
    protected $fichierXML = 'datas/etablissement_superieur.xml';
    protected $connexion;
    protected $host;
    protected $dbname;
    protected $utilisateur;
    protected $passwd;
    private $dom;
    private $contenuHote;
    private $contenuDatabase;
    private $contenuUtilisateur;
    private $contenuPasswd;
    private $table;
    private $champ;
    private $valeur;
    private $infos_connexion;
    private $parsed_json;

    public function __construct() {
        /*
          $this->host = 'localhost';
          $this->dbname ='sapb3';
          $this->utilisateur='root';
          $this->passwd='';
         */
    }

    /* public function connexionBase (){
      $fichierConfig = 'admin/config.xml';
      $this->dom = simplexml_load_file($fichierConfig);


      foreach ($this->dom as $identifiantsBase){
      $identifiants = $identifiantsBase -> identifiants_base ;
      foreach ($identifiants as $datas){
      $this->host = $datas->host;
      $this->dbname = $datas->nomdb;
      $this->utilisateur = $datas->login ;
      $this->passwd = $datas->passwd;
      }
      }
      print_r($this->host);
      try{
      $this->connexion = new PDO('mysql:host='.$this->host.'; dbname='.$this->dbname.'',
      ''.$this->utilisateur.'' , ''.$this->passwd.''
      , array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
      )
      ); //création de l'objet PDO pour la connexion à la BDD

      if ($this->connexion) {echo 'connexion à la base '.$this->dbname.' OK <br />';}
      }
      catch (Exception $e){
      die('Erreur de connexion à la base de donnée : '.$e->getMessage().'<br />');
      }

      return $this->connexion ;

      }
     */

    public function getConnexion() {
        return $this->connexion; //on retourne l'objet par l'accesseur
    }

    public function connexion() {
        //////////////////récupération des infos de connexion via un xml///////////
        /* $this->dom = new DomDocument ();
          $this->dom->load('admin/config.xml'); //lecture du DOM pour la connexion à la bdd

          $this->host = $this->dom->getElementsByTagName('host')	;
          $this->dbname = $this->dom->getElementsByTagName('nomdb');
          $this->utilisateur = $this->dom->getElementsByTagName('login');
          $this->passwd = $this->dom->getElementsByTagName('passwd');

          foreach ($this->host as $h){
          $contenuHote = $h->firstChild->nodeValue;
          }
          foreach ($this->dbname as $db){
          $contenuDatabase = $db->firstChild->nodeValue;
          }
          foreach ($this->utilisateur as $u){
          $contenuUtilisateur = $u->firstChild->nodeValue;
          }
          foreach ($this->passwd as $p){
          $contenuPasswd = $p->firstChild->nodeValue;
          }
         */
        //////////////////récupération des infos de connexion via un json///////////
        $this->infos_connexion = file_get_contents('admin/config_bdd.json');
        $this->parsed_json = json_decode($this->infos_connexion);
        $contenuHote = $this->parsed_json->{'informations_base_de_donnee'}->{'identifiants_base'}->{'host'};
        $contenuDatabase = $this->parsed_json->{'informations_base_de_donnee'}->{'identifiants_base'}->{'nomdb'};
        $contenuUtilisateur = $this->parsed_json->{'informations_base_de_donnee'}->{'identifiants_base'}->{'login'};
        $contenuPasswd = $this->parsed_json->{'informations_base_de_donnee'}->{'identifiants_base'}->{'passwd'};

        /////////////////Connexion à la base////////////////////////////////////////
        try {
            $this->connexion = new PDO('mysql:host=' . $contenuHote . '; dbname=' . $contenuDatabase . '', '' . $contenuUtilisateur . '', '' . $contenuPasswd . ''
                    , array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                    )
            ); //création de l'objet PDO pour la connexion à la BDD
        } catch (Exception $e) {
            die('Erreur de connexion à la base de donnée : ' . $e->getMessage() . '<br />');
        }

        return $this->connexion;
    }

    public function lireValeurBdd($valeur) {
        try {

            $requete = $this->connexion->prepare('SELECT * FROM etablissement WHERE commune = :valeur');

            $requete->bindParam(':valeur', $valeur, PDO::PARAM_STR);


            $requete->execute();

            if ($requete->fetch()) {

                foreach ($requete as $datas) {

                    echo '<span> _ ' . $datas['id'] . $datas['nom'] . $datas['commune'] . '_ </span>';
                }
            }
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function ecrireValeurBdd() {
        
    }

}
