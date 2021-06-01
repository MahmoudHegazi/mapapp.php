<?php
header('Content-Type: application/json; charset=utf-8');
// Speichern der Daten
// Annahme es geht alles gut :-)
$ajaxSpeichernSummary = array(
    "message"=>"Undefiined",
    "speicherStatus"=> false
);
    $verbindung = include ('db.inc.php');
    $table    = "hilfesuchender";

    if (isset($_GET["vn"])&& isset($_GET["nn"])
        && isset($_GET["long"]) && isset($_GET["lat"])
        && isset($_GET["dringlichkeit"]) && isset($_GET["hilfeart"])){
        $vn = urldecode(htmlspecialchars($_GET["vn"]));
        $nn = urldecode(htmlspecialchars($_GET["nn"]));
        $long = htmlspecialchars($_GET["long"]);
        $lat = htmlspecialchars($_GET["lat"]);
        $hilfeart = htmlspecialchars($_GET["hilfeart"]);
        $dringlichkeit = htmlspecialchars($_GET["dringlichkeit"]);

        $sql = "INSERT INTO `$table` ( `vn`, `nn`, `longitude`, `latitude`, `hilfeart`,
        `dringlichkeit`) VALUES ( '$vn', '$nn', '$long', '$lat', '$hilfeart', '$dringlichkeit')";
        $result = mysqli_query($verbindung, $sql);
        if($result){
            $ajaxSpeichernSummary['message'] = "Daten wurden Erfolgreich gespeichert";
            $ajaxSpeichernSummary['speicherStatus'] = true;
        }
        else {
            $ajaxSpeichernSummary['message'] = "Daten wurden nicht gespeichert - DB Problem ";
            $ajaxSpeichernSummary['speicherStatus'] = false;
        }

    }else {
        $ajaxSpeichernSummary['message'] = "Daten wurden nicht gespeichert - Daten fehlerhaft";
        $ajaxSpeichernSummary['speicherStatus'] = false;
    }

    $json = json_encode($ajaxSpeichernSummary);

    print($json);
    mysqli_close ($verbindung);

?>
