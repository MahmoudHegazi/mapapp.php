<?php
    header('Content-Type: application/json; charset=utf-8'); // ausgabe von json liefert und keine HTML

    $verbindung = include ('db.inc.php');
    $helfer_table    = "helfer";
    $hilfesuchender_table    = "hilfesuchender";
    $anfragen    = "anfragen";



    /* !-------------Return Query Stament for needer-----------------  */

    // this function will take the hilfeart string and return the valid query
    // the target is select needers that have the same hilfeart
    // why that cus needer only provide 1 single need, and helper provide spreated ,

    function createQuery($hilfeart_string){


    $str = $hilfeart_string;


    // explode will create a spreated List which can be used to get spreated hilfeart items
    // Garten, hushrat  result ('Garten', ' hushrat')
    $helpList = explode(",",$str);
    // check the count of the list if 1 create Select with 1 condition
    // if 2 with 2 conditions if 3 with 3 else return all rows (this case if error)
    $countHelps = count($helpList);



    if ($countHelps == 3){

      // create the OR statment conditions to generate the final sql statment
      // Graten, Hushrult, Elnkaufen
      $hilfeartInditfer = "'" . trim($helpList[0]) . "' OR hilfeart='" . trim($helpList[1]) . "' OR hilfeart='" . trim($helpList[2]) . "'";

      $statment = "SELECT * FROM hilfesuchender WHERE avail_status=0 AND hilfeart=$hilfeartInditfer";
      return $statment;

    } else if ($countHelps == 2) {

      // create the OR statment conditions to generate the final sql statment if 2 items
      // Graten, Elnkaufen

      $hilfeartInditfer = "'" . trim($helpList[0]) . "' OR hilfeart='" . trim($helpList[1]) . "'";
      $statment = "SELECT * FROM hilfesuchender WHERE avail_status=0 AND hilfeart=$hilfeartInditfer";
      return $statment;

    } else if ($countHelps == 1){

        // create statment without or if 1 only
      // Graten
      $hilfeartInditfer = "'" . trim($helpList[0]) . "'";
      $statment = "SELECT * FROM hilfesuchender WHERE avail_status=0 AND hilfeart=$hilfeartInditfer";
      return $statment;
    } else {
      // else select all row for needers and in all cases return the query statment
      $statment = "SELECT * FROM hilfesuchender WHERE avail_status=0";
      return $statment;
    };


    };


    /* end */

    // Bei mysqli_query & close müssen die Verbindungsparameter (gepeichert in $verbindung von pgdb.inc.php) immer übergeben werden

    // we defined Associative array which has two arrays one for hilfesuchender
    // and the other one for helfer , to be able in client side (javaScript) to get the spreate data for each table / instead of create 2 pages
    $messages = array(
        "hilfesuchender"=>[],
        "helfer"=>[],
        "anfragen"=>[],
        "status"=>false,
        "message"=>"",
        "anfragen_length"=>0,
        "hilfesuchender_length"=>0
      );

    if (isset($_GET['helferid'])) {

      // helfer
      // helfer data (we got the selected helfer data we only need this one for each page not all helfer )
      $hefler_id = $_GET['helferid'];
      $sql = "SELECT * FROM $helfer_table WHERE id=$hefler_id LIMIT 1"; // LIMIT 1 will return always 1 result any way ID not duplicated cus Auto inserment
      $result = mysqli_query($verbindung, $sql);

      if($result){

          while($row = mysqli_fetch_assoc($result)) { // wir haben in row alle eintraege in einzelne zeile //hier wir speichern alle in associativz array
              $message = array(
                  "id"=>$row['id'],
                  "vn"=>$row['vn'],
                  "nn"=>$row['nn'],
                  "long"=>floatval($row['longitude']), // floatval nur zum sichern dass um zahlen handelt
                  "lat"=>floatval($row['latitude']),
                  "hilfeart"=>$row['hilfeart'],
                  "garten"=>0,  // this is 0 value and we will validte it in the create sql function if provided garten will be 1
                  "elnkaufen"=>0,
                  "haushalt"=>0
                );
            array_push($messages['helfer'],$message); // array_push — Fügt ein oder mehr Elemente an das Ende eines Arrays an

          }
      };



    // needer

    // here we check if we found helfer in order to not get any error when we query database empty filed may break the code
    if (count($messages['helfer']) == 1) {

      // this will return the helfer (hilfeart) string which may one value or more spreated by qoma
      $helfer_hilfeart =  $messages['helfer'][0]['hilfeart'];


      // strops check if sup string in a string if it true asgin the garten value in the above step to 1 else  leave it as it 0
      if (strpos($helfer_hilfeart, 'Garten') !== false) {
           $messages['helfer'][0]['garten'] = 1;
       };

       // check if Elnkaufen inside the helfer hilfeart string if true change the above helfer['elnkaufen'] to 1

       if (strpos($helfer_hilfeart, 'Elnkaufen') !== false) {
            $messages['helfer'][0]['elnkaufen'] = 1;
        };

        // check if Haushalt inside the helfer hilfeart string if true change the above helfer['Haushalt'] to 1
        if (strpos($helfer_hilfeart, 'Haushalt') !== false) {
             $messages['helfer'][0]['haushalt'] = 1;
         };
      // call the function to return the valid query we need and give it the helfer object to validte which help provide dynamic

    $sql = createQuery($helfer_hilfeart);
    $result = mysqli_query($verbindung, $sql);
    // anfragen data

    if($result){
        // check if there are array not empty to apply count method which is count the rows number
        while($row = mysqli_fetch_assoc($result)) { // wir haben in row alle eintraege in einzelne zeile //hier wir speichern alle in associativz array
            $message = array(
                "id"=>$row['id'],
                "vn"=>$row['vn'],
                "nn"=>$row['nn'],
                "long"=>floatval($row['longitude']), // floatval nur zum sichern dass um zahlen handelt (the data gotten from db is string) we convert to float
                "lat"=>floatval($row['latitude']),
                "hilfeart"=>$row['hilfeart'],
                "dringlichkeit"=>intval($row['dringlichkeit']),
                'avail_status'=>$row['avail_status']
              );
                array_push($messages['hilfesuchender'], $message); // array_push — Fügt ein oder mehr Elemente an das Ende eines Arrays an
                $messages['hilfesuchender_length'] += 1;

        }

        // we send the total count of helfer to the client in order to certen the count not nesessery but it send a clear data to client / would help in big data


    };

  };
   // end of needer query

      $helferid = $_GET['helferid'];


      // we use inner JOIN to return the values from anfragen and hilfesuchender to get the hilfesuchender name and anfragen id and status
      $sql = "SELECT DISTINCT *, anfragen.id as anfrgid  FROM $anfragen INNER JOIN $hilfesuchender_table ON anfragen.hilfesuchender_id = hilfesuchender.id WHERE anfragen.helfer_id = $helferid";
      $result = mysqli_query($verbindung, $sql);
      if($result){
          while($row = mysqli_fetch_assoc($result)) { // wir haben in row alle eintraege in einzelne zeile //hier wir speichern alle in associativz array
              $message = array(
                  "id"=>$row['anfrgid'],
                  "helfer_id"=>$row['helfer_id'],
                  "hilfesuchender_id"=>$row['hilfesuchender_id'],
                  "status"=>$row['status'],
                  'hilfesuchender_vn'=>$row['vn'],
                  'hilfesuchender_nn'=>$row['nn'],
                  'hilfesuchender_long'=>$row['longitude'],
                  'hilfesuchender_lat'=>$row['latitude'],
                  'hilfesuchender_dringlichkeit'=>$row['dringlichkeit'],
                  'hilfesuchender_hilfeart'=>$row['hilfeart'],
                  'avail_status'=>$row['avail_status']
                );
                  array_push($messages['anfragen'],$message); // array_push — Fügt ein oder mehr Elemente an das Ende eines Arrays an
          };


        // we came to last sql which will get the help needer name using his id

        // here every thing good and we got all our data without problems we can send it back and make status true
        // status used to tell the client side what status of server response is every thing ok or not
        if ($messages["helfer"]) {
          // if we found the helfer return true else it will log the message helfer deleted this if user enter the helferid manual
        $messages['status'] = true;
        $messages['message'] = 'Erfolgreiche Anfrage';
      } else {
        $messages['status'] = false;
        $messages['message'] = 'helfer deleted or not found';
      }

    } else {
        // here if there are db problem in anfragen
        $messages['status'] = false; // send status false back to js to know there are a problem
        $messages['message'] = 'DB Problem'; // define the problem message in client side to be able know the resut

    }


  } else {
    // if the helfer id not send we return flase in response it will not happend unless there are error in MHS-helfer-render
    $messages['status'] = false; // send status false back to js to know there are a problem
    $messages['message'] = 'Fehler beim Senden der Daten helfer - Daten fehlerhaft'; // define the problem message in client side to be able know the resut

  }
    mysqli_close ($verbindung);
    $json = json_encode($messages); // json_encode — Liefert die JSON-Darstellung eines Wertes also wir wandeln unsere array zum json um
    print($json);
?>
