<!-- Julian Alber -->
<?php
    include_once '../includes/dbh.inc.php'
?>

<?php 
    class anmeldungKundeKl{

        private $email;
        private $kkennwort;
        private $conn;

        function __construct($email, $kkennwort, $conn){
            $this->email     = $email;
            $this->kkennwort = $kkennwort;
            $this->conn      = $conn;
        }

        //Diese Funktion prüft, ob der Benutzer sämtliche erforderliche Felder befüllt hat
        function alleFelderBelegt(){
            if(!isset($this->email)       || strlen($this->email) == 0 || 
               !isset($this->kkennwort) || strlen($this->kkennwort) == 0){
              
                return false;
            } 
            return true;
        }
        //Diese Funktion prüft, ob die eingegebene E-mail in der DB existiert
        function emailPruefen(){
            $emails = $this->conn->query("select email from kunde");
            while(($s = $emails->fetch_object()) != false){
               if($s->email == $this->email){
                  return true;
               }
            }
            return false;
        }
        //Diese Funktion prüft, ob die eingegebene E-mail und Passwort übereinstimmen
        function kennwortPruefen(){
            $kunde = $this->conn->query("select kkennwort from kunde where email = '$this->email'");
            $s = $kunde->fetch_object();
            if(password_verify($this->kkennwort, $s->kkennwort) == false){
                return false;
            } 
            return true;
        }
    }
?>