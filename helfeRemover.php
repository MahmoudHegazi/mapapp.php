<?php
header('Content-Type: application/json; charset=utf-8');
$ajaxSpeichernSummary = array(
    "message"=>"Undefiined",
    "speicherStatus"=> false,
    "deleted"=> 0,
);
    $verbindung = include ('db.inc.php');
    $table    = "helfer";

        if (isset($_POST["uid"])){
          $helferid = $_POST["uid"];
          //$helferid
          $sql = "DELETE FROM $table WHERE id=$helferid";
          $result = mysqli_query($verbindung, $sql);
          if ($result) {
            $ajaxSpeichernSummary['message'] = "es wurde gelöscht";
            $ajaxSpeichernSummary['speicherStatus'] = true;
            // the id of deleted helfer we use it to remove the helfer row from the page AJAX using deleteElement function
            $ajaxSpeichernSummary['deleted'] = $helferid;
          } else {
            $ajaxSpeichernSummary['message'] = "DB Problem";
            $ajaxSpeichernSummary['speicherStatus'] = false;
          }

        } else {
          $ajaxSpeichernSummary['message'] = "Daten fehlerhaft";
          $ajaxSpeichernSummary['speicherStatus'] = false;
        }


  $json = json_encode($ajaxSpeichernSummary);
  print($json);
  mysqli_close ($verbindung);

?>
