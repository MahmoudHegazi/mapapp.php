<!DOCTYPE html>
<html lang="en">
<head>
  <title>MHS-helfer-login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="stylesheet.css" />
  <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/2.2.3/jquery.min.js"></script>

</head>
<body>


     <div class="supplier_title">
       <h3>MHS Helfer Login</h3>
    </div>
   <div class="main_helfer">
        <div class="helfer_title">
           <p>Als Helfer einloggen</p>
        </div>
        <div class="alert_message">
          <span id="messageContent"></span><button id="removeMessage">X</button>
        </div>
      <!-- Example of HTML  row send from the helfere,pver
        <div class="helfer_row">
           <a>Some thing</a>
           <button>Click</button>
        </div>
      -->
      <?php include("MHS-helferlogin-render.php"); show_helfer();?>

   </div>
   <div class="helfer_footer">
     <a href="MHS-portal.html">Back To Portal</a>
   </div>



</body>
</html>
