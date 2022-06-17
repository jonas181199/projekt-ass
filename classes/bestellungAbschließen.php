<!-- Julian Alber -->
<!-- Beschreibung:
     Diese Klasse stellt die einzelnen Funktionen zur Verfügung, die dazu benötigt werden die eingegebene Bestellung 
     und die Eingegeben Bestellpositionen der Datenbank hinzuzufügen -->
<?php
    include_once '../includes/dbh.inc.php'
?>

<?php 
    class bestellungAbschließen{

        private $mid;
        private $conn;
        private $nbestellnr;

        function __construct($mid, $conn){
            $this->mid   = $mid;
            $this->conn  = $conn;
        }

        //Diese Funktion fügt die eingegebene Bestellung der DB hinzu
        function bestellungEinfuegen($email){           
            $bestellnr   = $this->conn->query("select count(*) as bestnr from bestellung");
            $abestellnr  = $bestellnr->fetch_object();
            $this->nbestellnr  = $abestellnr->bestnr + 1; 
            $bestdatum   = date('Y-m-d');
   
            $sql = "insert into bestellung values ('" . $this->nbestellnr. "', '" . $bestdatum. "', '" . $email. "', '" . $this->mid. "')";
            if ($this->conn->query($sql) == false){            
               return false;
            }
            return true;
        }

        //Diese Funktion fügt die einzelnen Bestellpositionen der DB hinzu, wenn der Lagerbestand noch ausreicht
        //Die Lagerbestände werden aufgrund des Triggers "lagerAktualisieren" automatisch angepasst 
        function bestellpositionEinfuegen($gname, $hname, $menge, $position){

            //Prüfen, ob Lagerbestand noch ausreicht (z.B.: Falls parallele Zugriffe), im Normalfall kein Problem, da schon geprüft)
            $sqlbestand = $this->conn->query("select bestand from lager where gname = '$gname' AND ghersteller = '$hname' AND mid = '$this->mid'");
            while($s = $sqlbestand->fetch_object()){
                $bestand = $s->bestand - $menge;
            }
            if($bestand < 0){
                return false;
            }

            //Bestellposition in die Datenbank einfügen
            $sql = "insert into bestellpos values ('" . $menge. "', '" . $position. "', '" . $this->nbestellnr. "', '" . $hname. "', '" . $gname. "')";
            if ($this->conn->query($sql) == false){
                return false;
            }
            return true;
        }
    }
?>