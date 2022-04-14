<?php

class getraenk {

    private $gname;
    private $ghersteller;
    private $kategorie;
    private $preis;


    public function __construct($gname, $ghersteller, $kategorie, $preis){
        $this->gname = $gname;
        $this->ghersteller = $ghersteller;
        $this->kategorie = $kategorie;
        $this->preis = $preis

    }

    
    

    
    public function setGname($gname){
        $this->gname = $gname
    }

    public function getGname(){
        return $this->gname;
    }

    public function setkategorie($kategorie){
        $this->kategorie = $kategorie
    }

    public function setGhersteller($ghersteller){
        $this->ghersteller = $ghersteller
    }

    public function setPreis($preis){
        $this->preis = $preis
    }


}

//Instanzierung einer Klasse
$objekt = new getraenk;
var_dump($objekt);

?>

