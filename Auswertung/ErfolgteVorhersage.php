<!-- Jonas Schirm -->
<?php
    session_start();
    include_once '../classes/auswertung.php';
    include_once '../includes/dbh.inc.php';
<<<<<<< HEAD
    if ((empty($_SESSION['mid']))) {
=======

    if (empty($_SESSION['mid'])) {
>>>>>>> 34ca4268e559eb60dd6806f905f69a8d7adb65e0
        header('Location: ../Anmeldung/Marktanmeldung.php');
        exit;
    }
?>

<?php
if(isset($_POST['vorhersage'])){
    $eingabe_datum = date('Y-m-d');
    $eingabe_datum_arr = explode('-', $eingabe_datum);
    $kategorie = $_POST['kategorie'];

    $akJahr   = idate('Y');
	$akWoche  = date('W');
    $timestamp_montag  = date("Y-m-d", strtotime("{$akJahr}-W{$akWoche}"));
    $timestamp_sonntag = date("Y-m-d", strtotime("{$akJahr}-W{$akWoche}-7")); 

    $startdatum_time = mktime(0, 0, 0, $eingabe_datum_arr[1], $eingabe_datum_arr[2], $eingabe_datum_arr[0]);
    $auswertung = new Auswertung($startdatum_time, $kategorie, $_SESSION['mid'], $conn);
    $umsaetze = $auswertung->getGesamtumsatz($timestamp_montag, $timestamp_sonntag);
    $regressionswert = $auswertung->lineareRegression_calc($umsaetze);
    ?>

    <p> <h3>Erwarteter Umsatz für die kommende Woche:</h3> <?php echo $regressionswert?> EUR </p>
<?php } ?>