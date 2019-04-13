<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Forgot Password Form</title>
  <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Arimo' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Hind:300' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
   <link rel="stylesheet" href="css/forgott.css">

  
</head>

<body>

  <div id="login-button">
  <img src="https://dqcgrsy5v35b9.cloudfront.net/cruiseplanner/assets/img/icons/login-w-icon.png">

</div>
   
<div id="container">
  <h1>Forgot Password?</h1>
  <span class="close-btn">
    <img src="https://cdn4.iconfinder.com/data/icons/miu/22/circle_close_delete_-128.png">
  </span>

<form action="forgotpassword/requestemail.php" method="get" class="custom">

   <input type="text" name="email" placeholder="Enter your username" required>
    
    <input type="text" name="phone" placeholder="Enter Your Registered phone number" required>
<!-- <a input type="submit" class="forgottbut" href="resetpas.html">RESET</a>-->
      <input type="submit" class="forgottbut" value="RESET">

</form>
    
</div>
       
  <script src='http://cdnjs.cloudflare.com/ajax/libs/gsap/1.16.1/TweenMax.min.js'></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<?php
    
    if(isset($_SESSION['error1'])){
        $error = $_SESSION['error1'];    
    ?>
    
    <script>alert('<?php echo $error; ?>');</script>
    
    <?php
    }
    
    unset($_SESSION['error1']);
    ?>
  

    <script  src="js/forgott.js"></script>
    </body>
</html>
