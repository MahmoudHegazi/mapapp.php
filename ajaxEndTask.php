<?php

header('Content-Type: application/json; charset=utf-8');
// Speichern der Daten
// Annahme es geht alles gut :-)
$ajaxSpeichernSummary = array(
    "message"=>"Undefiined",
    "speicherStatus"=> false,
    "requestUpdatedId"=> 0,
);
    $verbindung = include ('db.inc.php');
    $table    = "anfragen";

    if (isset($_GET["taskid"])){
        $task_id = $_GET["taskid"];
        // change the ticket status to 2
        $sql = "UPDATE `$table` set `status`=2 WHERE id=$task_id";
        $result = mysqli_query($verbindung, $sql);
        if($result){
            $ajaxSpeichernSummary['message'] = "Daten wurden Erfolgreich gespeichert";
            $ajaxSpeichernSummary['speicherStatus'] = true;
            $ajaxSpeichernSummary['requestUpdatedId'] = $task_id;
        }
        else {
            $ajaxSpeichernSummary['message'] = "Daten wurden nicht gespeichert - DB Problem";
            $ajaxSpeichernSummary['speicherStatus'] = false;
            $ajaxSpeichernSummary['requestUpdatedId'] = $task_id;
        }

    }else {
        $ajaxSpeichernSummary['message'] = "Daten wurden nicht gespeichert - Daten fehlerhaft";
        $ajaxSpeichernSummary['speicherStatus'] = false;
        $ajaxSpeichernSummary['requestUpdatedId'] = $task_id;
    }
    $json = json_encode($ajaxSpeichernSummary);

    print($json);
    mysqli_close ($verbindung);

?>
