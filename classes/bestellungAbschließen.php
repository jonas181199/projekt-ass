<!--  -->
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

        function bestellpositionEinfuegen($gname, $hname, $menge, $position){

            //Prüfen, ob Lagerbestand noch ausreicht (z.B.: Falls parallele Zugriffe, im Normalfall kein Problem, da schon geprüft)
            $sqlbestand = $this->conn->query("select bestand from lager where gname = '$gname' AND ghersteller = '$hname' AND mid = '$this->mid'");
            while($s = $sqlbestand->fetch_object()){
                $bestand = $s->bestand - $menge;
            }
            if($bestand < 0){
                break;
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