window.addEventListener('DOMContentLoaded', (event) => {
   const allRemoveBtns = document.querySelectorAll(".removeBtn");

   const alertContainer = document.querySelector(".alert_message");
   const messageContentSpan = document.querySelector("#messageContent");
   const removeMessageBtn = document.querySelector("#removeMessage");

   class  HilfesuchenderRemove {
       constructor() {
           this.uid = 0;
       }
   }

   let removerObject = new HilfesuchenderRemove();

   // send the remover id to the helferemover function using JQuery AJAX
   const sendData = ()=> {
     $.ajax({
         url: ('helfeRemover.php'),
         data: {uid: removerObject.uid},
         type: 'POST',
         timeout: 1000,
         dataType: 'json',
         error: ajaxSendError,
         success: ajaxSendSuccess
     });
   };

   function ajaxSendError(err) {
     console.log("error happend In Server");
   }

   const ajaxSendSuccess= (data)=> {
     let requestStatus = data.speicherStatus;
     if (requestStatus && data.deleted != 0) {
       // select button where data-uid attribute == the deleted helfer id we send the deleted id as response for the server
       let selector = `button[data-uid='${data.deleted}']`;
       let button_helfer = document.querySelector(selector);
       // send the button to delete element function
       deleteElement(button_helfer);

       // show the alert message to notify the user who deleted (get the name of deleted from the button previousSibling  child)
       deletedMessage = "Removed Helfer With ID: " + data.deleted;
       showAlertMessage(deletedMessage);
     } else {
       console.log(data.message);
     }
   }
   // function to get the id of user and send it to the server parentNode
   const deleteFunctionHandler = (event)=>{

     let helferUID = parseInt(event.target.getAttribute("data-uid"));
     // if helferid number and > 0
     if (helferUID > 0 && Number.isInteger(helferUID)) {
       removerObject.uid = helferUID;
       sendData();
     } else {
       console.log("Error In Selected Helfer ID");
     }

   };

   const deleteElement = (elm)=>{
     // get the parent of parent (main container ) and remove his child which is the div contains the button (full row)
     elm.parentNode.parentNode.removeChild(elm.parentNode);

   }
   // add event listner on the delete button to apply the delete function when a button clicked
   allRemoveBtns.forEach( (btn)=> {
     btn.addEventListener("click", deleteFunctionHandler);
   });


   // alert part show message when delete helfer
   const showAlertMessage = (message)=> {
     alertContainer.style.display = "block";
     messageContentSpan.innerText = message;
   };



   //remove alert message  when click on x button
   const hideAlertMessage = ()=> {
     alertContainer.style.display = "none";
     messageContentSpan.innerText = "";
   };

   removeMessageBtn.addEventListener("click", hideAlertMessage);
});
