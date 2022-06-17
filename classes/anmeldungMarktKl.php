<!-- Dies ist eine gemeinsame Leistung der Gruppe -->
<!-- Beschreibung:
     Diese Klasse stellt die einzelnen Funktionen zur Verfügung, die benötigt werden die eingegebenen Daten des Nutzer 
     bei einer Marktanmeldung zu überprüfen -->
<?php
    include_once '../includes/dbh.inc.php'
?>

<?php 
    class anmeldungMarktKl{

        private $mid;
        private $mpasswort;
        private $conn;

        function __construct($mid, $mpasswort, $conn){
            $this->mid       = $mid;
            $this->mpasswort = $mpasswort;
            $this->conn      = $conn;
        }

        //Diese Funktion prüft, ob der Benutzer sämtliche erforderliche Felder befüllt hat
        function alleFelderBelegt(){
            if(!isset($this->mid)       || strlen($this->mid) == 0 || 
               !isset($this->mpasswort) || strlen($this->mpasswort) == 0){
              
                return false;
            } 
            return true;
        }
        //Diese Funktion prüft, ob die eingegebene Markt-ID in der DB existiert
        function midPruefen(){
            $mids = $this->conn->query("select mid from markt");
            while(($s = $mids->fetch_object()) != false){
               if($s->mid == $this->mid){
                  return true;
               }
            }
            return false;
        }
        //Diese Funktion prüft, ob die eingegebene Markt-ID und Passwort übereinstimmen
        function passwortPruefen(){
            $markt = $this->conn->query("select mpasswort from markt where mid = '$this->mid'");
            $s = $markt->fetch_object();
            if(password_verify($this->mpasswort, $s->mpasswort) == false){
                return false;
            } 
            return true;
        }
    }
?>