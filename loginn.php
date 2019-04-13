<?php

session_start();


?>
<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>LogIn Form</title>
  <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Arimo' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Hind:300' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>


      <link rel="stylesheet" href="css/loginn.css">

    

</head>

<body>

  <div id="login-button">
  <img src="https://dqcgrsy5v35b9.cloudfront.net/cruiseplanner/assets/img/icons/login-w-icon.png">

</div>

<div id="container">
  <h1>Log In</h1>
  <span class="close-btn">
    <img src="https://cdn4.iconfinder.com/data/icons/miu/22/circle_close_delete_-128.png">
  </span>

  <form action = "php_form_handler/login.php" method = "post">

   <input type="name" name="username" placeholder="Enter UserName" required>
    <input type="password" name="password" placeholder="Password" type="password"  required pattern=".{8,}" title="password contains atleast 8 charcters" required>
   <input type="submit" class="loginbut" name="login" value="LOGIN" href="#">
    <div id="remember-container">
      <input type="checkbox" id="checkbox-2-1" class="checkbox" checked="checked"/>
      <span id="remember">Remember me</span>
      <a href="forgott.php" id="forgotten">Forgotten password</a>
    </div>
</form>
  
</div>


  <script src='http://cdnjs.cloudflare.com/ajax/libs/gsap/1.16.1/TweenMax.min.js'></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>



    <script  src="js/loginn.js"></script>

    <?php
    
        if(isset($_SESSION['error'])){
            $error = $_SESSION['error'];
            ?>
    
    <script>alert('<?php echo $error; ?>');</script>
    
    <?php
        }
    
    ?>


</body>

</html>

<?php
unset($_SESSION['error']);
?>
