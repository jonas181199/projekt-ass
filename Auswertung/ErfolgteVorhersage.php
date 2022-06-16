<!-- Jonas Schirm -->
<?php
    session_start();
    include_once '../classes/auswertung.php';
    include_once '../includes/dbh.inc.php'
?>

<?php
if(isset($_POST['vorhersage'])){
	$kategorie = $_POST['kategorie'];
    $auswertung = new Auswertung($kategorie, $_SESSION['mid'], $conn);
    //$regression = $auswertung->getUmsatzNaechsteWoche($umsaetze[date("W Y", time())]);
    //$letzerwochentag = $auswertung->letzterWT(2022-06-13);
    //var_dump($letzerwochentag);?>

    <p>Erwarteter Umsatz für die kommende Woche: <?php $regression ?> </p>
<?php } ?>