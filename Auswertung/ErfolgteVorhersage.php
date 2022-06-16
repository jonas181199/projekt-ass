<!-- Jonas Schirm -->
<?php
    session_start();
    include_once '../classes/auswertung.php';
    include_once '../includes/dbh.inc.php';
?>

<?php
if(isset($_POST['vorhersage'])){
    $eingabe_datum = date('Y-m-d');
    $eingabe_datum_arr = explode('-', $eingabe_datum);
    $auswertung_kat = $_POST['kategorie'];
    $startdatum_time = mktime(0,0,0,$eingabe_datum_arr[1],$eingabe_datum_arr[2],$eingabe_datum_arr[0]);
    $auswertung = new Auswertung($startdatum_time, $auswertung_kat, $_SESSION['mid'], $conn);
    $umsaetze = $auswertung->getWochenumsatz();
    $regressionswert = $auswertung->getUmsatzFolgewoche($umsaetze[date("W Y", time())]);
    ?>

    <p>Erwarteter Umsatz für die kommende Woche: <?php echo $regressionswert ?> </p>
<?php } ?>