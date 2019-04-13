<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Reset Password form</title>
  <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Arimo' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Hind:300' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
  
  
      <link rel="stylesheet" href="css/reset.css">

  
</head>

<body>

  <div id="login-button">
  <img src="https://dqcgrsy5v35b9.cloudfront.net/cruiseplanner/assets/img/icons/login-w-icon.png">
 
</div>
  
<div id="container">
  <h1>Reset Password</h1>
  <span class="close-btn">
    <img src="https://cdn4.iconfinder.com/data/icons/miu/22/circle_close_delete_-128.png">
  </span>

  <form>

    <input type="password" name="pass" placeholder="Enter new password" type="password"  required pattern=".{8,}" title="password contains atleast 8 charcters" required>
       <input type="password" name="pass" placeholder="Enter confirm password" type="password"  required pattern=".{8,}" title="password contains atleast 8 charcters" required>
<!--      <a input type="submit" class="jk" href="loginn.html">SUBMIT</a>-->
     
   <input type="submit" class="resetbut" value="SUBMIT">

</form>
</div>
 
  <script src='http://cdnjs.cloudflare.com/ajax/libs/gsap/1.16.1/TweenMax.min.js'></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

  

    <script  src="js/loginn.js"></script>




</body>

</html>
