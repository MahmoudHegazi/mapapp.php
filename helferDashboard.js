
/* global map vars */
var map;
var markerLayer = null;
var helferMarker = null;
var neederMarker = null;



    window.addEventListener("load",()=>{
      // simple javaScript code to get the quer parameter value in the url in order to send to to the php the helfer id and get the data

      const urlParams = new URLSearchParams(window.location.search);
      const helferIDParameter = urlParams.get('helferid');

      // check if helfer not empty or not provided  (We start apply any code from here when helferid paramter sent with url )
      if (helferIDParameter) {

        // clas for send data to ajaxSendDbData.php
        class  Hilfer {
            constructor() {
                this.uid = 0;
            }
        };

        hilferObject = new Hilfer();




        // asgin the helferid value we got from the url to the helfe class in order to send it to the ajaxSendDbData.php and get the data
        hilferObject.uid = helferIDParameter;

        // function will send a get request to ajaxSendDbData.php page to get the data for the page ,(helfer, needers, anfrugun)
        const sendHelferData = ()=> {
          $.ajax({
              url: ('ajaxSendDBData.php'),
              data: {helferid: hilferObject.uid},
              type: 'GET',
              timeout: 1000,
              dataType: 'json',
              error: helferAjaxSendError,
              success: helferAjaxSendSuccess
          });
        };

        const helferAjaxSendError = (error) => {
          console.log("error");
        };

        const helferAjaxSendSuccess = (data) => {

          // if status == true thats mean no problem in Database, and in helferid and we found helfer
          let requestStatus = data.status;

          if (requestStatus == true){

             // when sendHelferData success we cal renderData to show the data in the tables
             renderData(data.helfer, data);
        } else {
          // if any error from request change the html body and show the message useully this will not happend unless user manual edit url
          // we need to handle everything
          document.body.innerHTML = data.message;
        }
        };



        // this function to render the helfer data inside the table
        const renderData = (helferArray, data) => {
          console.log(data);
          helferObject = helferArray[0];
          console.log(helferObject);


          // first table

          // access the helfer info table tds
          let helferID = document.getElementById("hid");
          let helferVn = document.getElementById("hvn");
          let helferNn = document.getElementById("hnn");
          let helferLong = document.getElementById("hlong");
          let helferLat = document.getElementById("hlat");
          let helferGarten = document.getElementById("hgarten");
          let helferHaushlt = document.getElementById("hhaushlt");
          let helferLnkaufen = document.getElementById("hlnkaufen");
          let helferKarts = document.getElementById("hkarts");

          helferID.innerText = helferObject.id;
          helferVn.innerText = helferObject.vn;
          helferNn.innerText = helferObject.nn;
          helferLong.innerText = helferObject.long;
          helferLat.innerText = helferObject.lat;
          helferGarten.innerText = helferObject.garten;
          helferHaushlt.innerText = helferObject.haushalt;
          helferLnkaufen.innerText = helferObject.elnkaufen;
          helferKarts.innerHTML = `<button data-long="${helferObject.long}" data-lat="${helferObject.lat}" id="showHelferBtn" >Karts</button>`;

          // call the createLongLat function without needer arugment passed to only show helfer
          document.querySelector("#showHelferBtn").addEventListener("click",createLongLat);


          // second table offen
          let offenTable = document.getElementById("offerInfo");
          let offenArray = data.hilfesuchender;
          let offenMesage = document.getElementById("offenEmptyMessage");

          // if there are offers hide the empty message and empty the text and display the table else do vice versa
          if (offenArray.length > 0){
            offenMesage.innerText = "";
            offenTable.style.display = "inline-table";
            offenArray.forEach( (item, index)=>{

                // create new tr element avail_status
                let newTr = document.createElement("tr");
                // add insrted row class to each new created row in order to remove the insrted row when change status (AJAX )
                newTr.setAttribute("class", `insrted_row`);
                // create the rows
                newTr.innerHTML = `<td>${item.id}</td><td>${item.vn}</td><td>${item.nn}</td><td>${item.long}</td>`;
                newTr.innerHTML += `<td>${item.lat}</td><td>${item.hilfeart}</td><td>${item.dringlichkeit}</td>`
                newTr.innerHTML += `<td><button class="status_btn_zero" data-helferid="${helferObject.id}" data-neederid="${item.id}" data-status="0">Status seiznen</button></td>`;
                newTr.innerHTML += `<td><button class="route_btn_offen" data-helfer-long="${helferObject.long}" data-helfer-lat="${helferObject.lat}" data-needer-long="${item.long}" data-needer-lat="${item.lat}" >Route anzeigen</button></td>`;
                // append the rows to the table
                offenTable.appendChild(newTr);
                // apply createAnfragen function on the created btn (index == the current index or order of the button which is the new btn created)
                document.querySelectorAll(".status_btn_zero")[index].addEventListener("click", createAnfragen);

                // add Event Listener on route_btn in offen table to call the createLongLat with isneeder=true which draw 2 markers
                // it will get the lat and long for both helfer and needer using the event.target which is the button and draw the markers
                document.querySelectorAll(".route_btn_offen")[index].addEventListener("click", ()=> {createLongLat(event, true, 'gold')});

            });

          } else {
            // if no data hide table show message add the empty sentense
            offenTable.style.display = "none";
            offenMesage.style.display = "block";
            offenMesage.innerText = "Keine Aufträge vorhanden";
          };


          // Bearbeitung table


          let anfragenArray = data.anfragen;
          // bearbeitungEmptyMessage
          let bearbeitungEmptyP =  document.getElementById("bearbeitungEmptyMessage");
          let bearbeitungTable =  document.getElementById("bearbeitungInfo");

          let abgeschiossenEmptyP =  document.getElementById("abgeschiossenEmptyMessage");
          let abgeschiossenTable =  document.getElementById("abgeschiossenInfo");


          bearbeitungTable.style.display = "none";
          bearbeitungEmptyP.style.display = "block";
          bearbeitungEmptyP.innerText = "Keine Aufträge vorhanden";

          // hide last  table and show the message
          abgeschiossenTable.style.display = "none";
          abgeschiossenEmptyP.style.display = "block";
          abgeschiossenEmptyP.innerText = "Keine Aufträge vorhanden";

          // if there are anfragen rows do render the tables else hide both tables bearbeitungInfo  and abgeschiossenInfo
          if (anfragenArray.length > 0) {



            let isbearbeitung = false;
            let isbearabgeschiossen = false;

            let anfragenArray = data.anfragen;
            anfragenArray.forEach( (row, i)=>{
              // case one if we found row has status 1 show the table else default is hidden

              // if we found row with status 1 set isbearbeitung to true to show the table and create tr and td items
              if (row.status == 1){
                isbearbeitung = true;
                let bearbeitungTr = document.createElement("tr");

                // add id to the button to add eventLister
                let btnStatusId = `btn_${i}`;
                let btnRouteId = `btn_route_${i}`;

                // add insrted row class to each new created row in order to remove the insrted row when change status (AJAX )
                bearbeitungTr.setAttribute("class", `insrted_row`);
                bearbeitungTr.innerHTML = `<td>${row.hilfesuchender_id}</td><td>${row.hilfesuchender_vn}</td><td>${row.hilfesuchender_nn}</td><td>${row.hilfesuchender_long}</td>`;
                bearbeitungTr.innerHTML += `<td>${row.hilfesuchender_lat}</td><td>${row.hilfesuchender_hilfeart}</td><td>${row.hilfesuchender_dringlichkeit}</td><td>${row.id}</td><td>${row.status}</td>`;
                bearbeitungTr.innerHTML += `<td><button id="${btnStatusId}" class="status_button" data-anfrugun-id="${row.id}" data-status="${row.status}">Status seiznen</button></td>`;
                bearbeitungTr.innerHTML += `<td><button id="${btnRouteId}" class="route_btn" data-helfer-long="${helferObject.long}" data-helfer-lat="${helferObject.lat}" data-needer-long="${row.hilfesuchender_long}" data-needer-lat="${row.hilfesuchender_lat}">Route anzeigen</button></td>`;
                // add the tr to table
                bearbeitungTable.appendChild(bearbeitungTr);
                // add event listener to the button with status_btn (querySelectorAll return object node list contains all btns with that class) index = current button order

                document.querySelector(`#${btnStatusId}`).addEventListener("click", closeAnfragenTicket);

                // add Event Listener on route_btn in offen table to call the createLongLat with isneeder=true which draw 2 markers
                // it will get the lat and long for both helfer and needer using the event.target which is the button and draw the markers
                // set the color want to use it
                document.querySelector(`#${btnRouteId}`).addEventListener("click", ()=> {createLongLat(event, true, 'red')});
              };

              // if we found row with status 2 set isbearabgeschiossen to true to show the table and create tr and td items
              if (row.status == 2){
                isbearabgeschiossen = true;
                let bearabgeschiossenTr = document.createElement("tr");
                // add insrted row class to each new created row in order to remove the insrted row when change status (AJAX )
                bearabgeschiossenTr.setAttribute("class", `insrted_row`);
                bearabgeschiossenTr.innerHTML = `<td>${row.hilfesuchender_id}</td><td>${row.hilfesuchender_vn}</td><td>${row.hilfesuchender_nn}</td><td>${row.hilfesuchender_long}</td>`;
                bearabgeschiossenTr.innerHTML +=`<td>${row.hilfesuchender_lat}</td><td>${row.hilfesuchender_hilfeart}</td><td>${row.hilfesuchender_dringlichkeit}</td><td>${row.id}</td><td>${row.status}</td>`;

                // add the tr to table
                abgeschiossenTable.appendChild(bearabgeschiossenTr);


              };


              // show the table according to isbearbeitung and isbearabgeschiossen

              // if isbearbeitung ==  true that's mean we found a row with status 1 so we will render the table else we hide it and show message
              // AJAX may change the tables and make some one empty
              if (isbearbeitung) {
                // show last  table and show the message
                bearbeitungTable.style.display = "inline-table";
                bearbeitungEmptyP.style.display = "none";
                bearbeitungEmptyP.innerText = "";


              } else {

                bearbeitungTable.style.display = "none";
                bearbeitungEmptyP.style.display = "block";
                bearbeitungEmptyP.innerText = "Keine Aufträge vorhanden";
              };


              // if isbearabgeschiossen ==  true that's mean we found a row with status 2 so we will render the table else we hide it and show message
              if (isbearabgeschiossen) {
                // show  third  table and show the message
                abgeschiossenTable.style.display = "inline-table";
                abgeschiossenEmptyP.style.display = "none";
                abgeschiossenEmptyP.innerText = "";

              } else {

                abgeschiossenTable.style.display = "none";
                abgeschiossenEmptyP.style.display = "block";
                abgeschiossenEmptyP.innerText = "Keine Aufträge vorhanden";
              };

            });


          } else {
            console.log('Keine Aufträge vorhanden');

            bearbeitungTable.style.display = "none";
            bearbeitungEmptyP.style.display = "block";
            bearbeitungEmptyP.innerText = "Keine Aufträge vorhanden";

            // hide last  table and show the message
            abgeschiossenTable.style.display = "none";
            abgeschiossenEmptyP.style.display = "block";
            abgeschiossenEmptyP.innerText = "Keine Aufträge vorhanden";
          };
        };
        /* end of render data function  */


        /* ----------------------------- Create New Anfragen ticket Start  with status 1 ----------------------- */
        // function to remove insrted row act like refresh
        const removeAllInsrted = ()=>{
          let insrtedRows = document.querySelectorAll(".insrted_row");
          insrtedRows.forEach( (row)=> {
             row.remove();
          });
        }

       // clas for send data to ajaxSendDbData.php
       class  Anfragen {
           constructor() {
               this.helferid = 0;
               this.neederid = 0;
           }
       };

       anfragenObject = new Anfragen();


       // function will send a get request to ajaxSendDbData.php page to get the data for the page ,(helfer, needers, anfrugun)
       const sendAnfragenData = ()=> {
         $.ajax({
             url: ('ajaxCreateAnfragen.php'),
             data: {herferid: anfragenObject.helferid, neederid: anfragenObject.neederid},
             type: 'GET',
             timeout: 1000,
             dataType: 'json',
             error: anfragenAjaxSendError,
             success: anfragenAjaxSendSuccess
         });
       };


        const anfragenAjaxSendError = (error)=>{
           console.log("error");
        };


        const anfragenAjaxSendSuccess = (data)=>{
          if (anfragenObject.helferid && anfragenObject.neederid){
            removeAllInsrted();
            sendHelferData();
           console.log(data.message, data.status, data.data[0].neederid, data.data[0].status);
         };
        };


        // function for create new anfragen when helfer click on one item in offen
        const createAnfragen = (event)=> {
          event.target.setAttribute("disabled", "disabled");
          anfragenObject.helferid = event.target.getAttribute("data-helferid");
          anfragenObject.neederid = event.target.getAttribute("data-neederid");
          sendAnfragenData();
        }


        /* --------------------- Create New Anfragen ticket end   with status 1 --------- */

        /* ---------------- FInal table change status of ticket to 2 ---------------------*/

        // clas for send data to ajaxSendDbData.php
        class  AnfragenClose {
            constructor() {
                this.taskid = 0;
            }
        };

        anfragenCloseObject = new AnfragenClose();


        // function will send a get request to ajaxSendDbData.php page to get the data for the page ,(helfer, needers, anfrugun)
        const sendCloseAnfragenRequest = ()=> {
          $.ajax({
              url: ('ajaxEndTask.php'),
              data: {taskid: anfragenCloseObject.taskid},
              type: 'GET',
              timeout: 1000,
              dataType: 'json',
              error: anfragenCloseAjaxSendError,
              success: anfragenCloseAjaxSendSuccess
          });
        };


         const anfragenCloseAjaxSendError = (error)=>{
            console.log("error");
         };

         // when request sended to ajaxEndTask.php and success update the UI
         const anfragenCloseAjaxSendSuccess = (data)=>{
           if (anfragenCloseObject.taskid){
             // clear old data
             removeAllInsrted();
             // send request to get the data and then call render data to show new data in  correct place AJAX
             sendHelferData();
            console.log("python King even without know task requirments");
          };
         };


         // function for to send Request to ajaxEndTask page and close the ticket change status to 2
         const closeAnfragenTicket = (event)=> {
           // add disabled attribute to the clicked button in case the user click duplicated not send to requests (0 error chance)
           event.target.setAttribute("disabled", "disabled");
           // get the anfrugun-id from the clicked btn
           anfragenCloseObject.taskid = event.target.getAttribute("data-anfrugun-id");
           sendCloseAnfragenRequest();
         }

        /* ---------------------------- Final table action ends ------------------------------*/




        // we call the helfer send data request
        sendHelferData();

        /* -------------------------------- MAP WORK ----------------------------- */


        let greenMarker = new L.icon({
          iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png",
          iconShadow: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
          iconSize: [25, 41],
          shadowSize: [41, 41],
          iconAnchor: [12, 41],
          popupAnchor:[1, -34]
        });


        let redMarker = new L.icon({
          iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png",
          iconShadow: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
          iconSize: [25, 41],
          shadowSize: [41, 41],
          iconAnchor: [12, 41],
          popupAnchor:[1, -34]
        });



        const initMap = ()=> {
          map = L.map('map', {
            center: [12, 13],
            zoom: 5,
          });

          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
          }).addTo(map);

        };

        initMap();

          /* Handle MAP and Market  */

          /*function to create long and lat arrays */
          function createLongLat(event, isneeder=false, color=null){
            let selectedColor;
            // if function provide a color use it else use defualt orang
            if (color) {
              selectedColor = color;
            } else {
              selectedColor = 'orange';
            };

            if (isneeder == false){
              // in this case we call handlerMarkers with the first senrio isHelfer==true && isneeder==false To show only helfer empty array to keep order
              handleMarkers([event.target.getAttribute("data-lat"), event.target.getAttribute("data-long")], [], true, false);
            } else {

              // in this case we call handleMarkers with all paramters to map the route first draw the 2 markers and then daraw the line
              //added color paramter to change the polyline color
              handleMarkers([event.target.getAttribute("data-helfer-lat"), event.target.getAttribute("data-helfer-long")], [event.target.getAttribute("data-needer-lat"), event.target.getAttribute("data-needer-long")], true, true, selectedColor);
            };
          }

          function handleMarkers(helferParamters=[], neederParameters=[], isHelfer, isneeder, color='orange'){

            // always clear layers if no markerLayer create marker layer group inital
            if (markerLayer) {
                markerLayer.clearLayers();
              } else {
                markerLayer = L.layerGroup();
              };
              // if third paramter set to true and last to false that's mean we click on Karts button only (show only helfer)
            if (isHelfer==true && isneeder==false) {
              // case first time click on show on map
              helferMarker = new L.marker( helferParamters, {icon:greenMarker, clickable:true}).bindPopup("Like Corse JS").addTo(markerLayer);
              map.addLayer(markerLayer);
            } else  {
              // if both true show helfer and needer (in function call we are not user another options only true , false or true , true)
              helferMarker = new L.marker( helferParamters, {icon:greenMarker, clickable:true}).bindPopup("Like Corse JS").addTo(markerLayer);
              neederMarker = new L.marker( neederParameters, {icon:redMarker, clickable:true}).bindPopup("Like Corse JS").addTo(markerLayer);

              let latings = [helferParamters, neederParameters];
              // draw polyline to connect the two markers together (it takes array of points long and lat ) and color
              let polyline = new L.polyline(latings, {color:color}).addTo(markerLayer);
              // this function to set the zoom on the created line
              map.fitBounds(polyline.getBounds());
              map.addLayer(markerLayer);
            }
          };
          // lat, long
          //handleMarkers([15, 12], [12, 13], true, true);

        /* --------------------------- end map -----------------------------------*/
      } else {
        // if the helfer id not provided change the inter page html and show message we did not found helfer
        document.body.innerHTML = "We did not found helfer";
      }

    });
