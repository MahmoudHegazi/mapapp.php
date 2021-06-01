<?php

function show_helfer() {
   $verbindung = include ('db.inc.php');
   $table    = "helfer";

    // Bei mysqli_query & close müssen die Verbindungsparameter (gepeichert in $verbindung von pgdb.inc.php) immer übergeben werden

   $message = ""; // this vairable will hold the html final result

   $sql = "SELECT * FROM $table";
   $result = mysqli_query($verbindung, $sql);

    if($result){
        while($row = mysqli_fetch_assoc($result)) { // wir haben in row alle eintraege in einzelne zeile //hier wir speichern alle in associativz array
          $fname = $row["vn"];
          $lname = $row["nn"];
          $helferid = intval($row['id']);
           $message .=
           '<div class="helfer_row">
             <a href="MHS-helfer-dashboard.html?helferid=' . $helferid . '">' . $fname . ' ' . $lname  . '</a>
             <button class="removeBtn" data-uid="' . $helferid  . '">Loschen</button>
           </div>';
        };
    };
    echo $message;
    echo '<script type="text/javascript" src="removehelfer.js"></script>';
    mysqli_close ($verbindung);
    return true;
 }
 ?>
