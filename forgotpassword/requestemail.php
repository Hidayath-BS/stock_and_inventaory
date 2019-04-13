<?php

session_start();

$error = "username/Registered Phone Number is Incorrect";

require('../dbconnect.php');

     if(isset($_GET["email"]) && isset($_GET['phone'])){
         
         $email =$_GET['email'];
         $phone = $_GET['phone'];
         
         $authQuery = "SELECT * from hk_users WHERE username = '$email' && mobile_number= '$phone'";
         
         $authExe = mysqli_query($conn,$authQuery);
         $row = mysqli_fetch_array($authExe);
         if(!is_array($row)){
         echo "sorry";
         $_SESSION['error1'] = $error;
         header('Location: ../forgott.php');
        }
         else if(is_array($row)){
             
             $username =  $row["username"]; 
         }
             
             
         
?>
<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Reset Password form</title>
  <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Arimo' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Hind:300' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
  
  
      <link rel="stylesheet" href="../css/reset.css">

  
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

  <form style="text-align:center" method="post" action="updatepass.php">
      <input type="text" name="username" value="<?php echo $username; ?>" style="display:none">
    <input type="password" id="pass1" name="pass1" placeholder="Enter new password"  type="password"  required pattern=".{8,}" title="password contains atleast 8 charcters" required>
       <input type="password" onblur="passcheck()" id="pass2" placeholder="Enter confirm password" type="password"  required pattern=".{8,}" title="password contains atleast 8 charcters" required>
<!--      <a input type="submit" class="jk" href="loginn.html">SUBMIT</a>-->
     
<!--   <input type="submit" id="submit" class="resetbut" value="SUBMIT">-->
      <button type="submit" class="btn btn-default resetbut" id="submit">SUBMIT</button>

</form>
</div>
 
  <script src='http://cdnjs.cloudflare.com/ajax/libs/gsap/1.16.1/TweenMax.min.js'></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

  

    <script  src="../js/loginn.js"></script>

    <script>
        function passcheck(){
            var pass2 = $("#pass2").val();
            var pass1 = $("#pass1").val();
            if(pass1 != pass2){
            var pass1 = $("#pass1").val();
            $("#submit").attr("disabled", "disabled");
                alert('Please enter same passwords');
        }
            else{
                $("#submit").removeAttr("disabled");
            }
            
            
        }
        
        
    </script>


</body>

</html>




<?php
 
       }
 
  
  
?>
            
         
       
