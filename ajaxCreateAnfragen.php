<?php

header('Content-Type: application/json; charset=utf-8');
// Speichern der Daten
// Annahme es geht alles gut :-)
$ajaxSpeichernSummary = array(
    "message"=>"Undefiined",
    "speicherStatus"=> false,
    "data"=>[]
);

$lastInsretedId = null;
    $verbindung = include ('db.inc.php');
    $table    = "anfragen";
    $table2 = "hilfesuchender";
    if (isset($_GET["herferid"])&& isset($_GET["neederid"])){
        $herferuid = intval($_GET["herferid"]);
        $neederuid = intval($_GET["neederid"]);

        $sql = "INSERT INTO `$table` ( `helfer_id`, `hilfesuchender_id`, `status`) VALUES ( $herferuid, $neederuid, 1)";
        $result = mysqli_query($verbindung, $sql);
        if($result){
            $lastInsretedId = mysqli_insert_id($verbindung);

            // update the status to make it 1 to remove from offen , useully it better to have a spreate table for needer requests unlike the one offer he has 1 profile but needer can create more than 1 task
            $sql1 = "UPDATE `$table2` SET `avail_status`=1 WHERE id=$neederuid";
            $result1 = mysqli_query($verbindung, $sql1);
            // get id of last insrted row into anfragen table
            if ($result1) {
              $ajaxSpeichernSummary['message'] = "Erfolgreicher Betrieb";
              $ajaxSpeichernSummary['speicherStatus'] = true;
              array_push($ajaxSpeichernSummary['data'], array('neederid'=>$neederuid, 'status'=>0));
            } else {
              // if second query not work remove the insrted row in first query
              $finalback = "DELETE FROM `$table` WHERE id=$lastInsretedId";
              mysqli_query($verbindung, $finalback);
              $ajaxSpeichernSummary['message'] = "Daten wurden nicht gespeichert - DB Problem 2";
              $ajaxSpeichernSummary['speicherStatus'] = false;
            };
        }
        else {
            $ajaxSpeichernSummary['message'] = "Daten wurden nicht gespeichert - DB Problem 1";
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
