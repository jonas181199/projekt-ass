<!-- Dies ist eine gemeinsame Leistung der Gruppe -->
<?php
    include_once '../includes/dbh.inc.php'
?>

<?php 
    class registrierungMarktKl{

        private $mid;
        private $mname;
        private $mpasswort;
        private $conn;

        function __construct($mid, $mname, $mpasswort, $conn){
            $this->mid       = $mid;
            $this->mname     = $mname;
            $this->mpasswort = $mpasswort;
            $this->conn      = $conn;
        }

        //Diese Funktion prüft, ob der Benutzer sämtliche erforderliche Felder befüllt hat
        function alleFelderBelegt(){
            if(!isset($this->mid)       || strlen($this->mid) == 0 || 
               !isset($this->mname)     || strlen($this->mname) == 0 || 
               !isset($this->mpasswort) || strlen($this->mpasswort) == 0){
            
                return false;
            } 
            return true;
        }
        //Diese Funktion prüft, ob die eingegebene Markt-ID bereits in der DB existiert
        function midPruefen(){
            $mids = $this->conn->query("select mid from markt");
            while(($s = $mids->fetch_object()) != false){
               if($s->mid == $this->mid){
                  return false;
               }
            }
            return true;
        }
        //Diese Funktion prüft, ob der eingegebene Marktname bereits in der DB existiert
        function mnamePruefen(){
            $mn = $this->conn->query("select mname from markt");
            while(($s = $mn->fetch_object()) != false){
               if($s->mname == $this->mid){
                  return false;
               }
            }
            return true;
        }
        //Diese Funktion fügt den neuen Markt der DB hinzu
        function marktHinzufuegen(){
            $sql = "insert into markt values ('" . $this->mid. "', '" . $this->mpasswort. "', '" . $this->mname. "')";
            if ($this->conn->query($sql) == false){
                return false;
            }
            else {
                return true;
            }
        }
    }
?>