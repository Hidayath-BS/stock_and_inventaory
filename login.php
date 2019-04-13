<?php
require('../dbconnect.php');
$username = $_POST["username"];
$password = $_POST["password"];

// To protect MySQL injection 

$username = stripslashes($username);
$password = stripslashes($password);
//$username = mysql_real_escape_string($username);
//$password = mysql_real_escape_string($password);

$password = sha1($password);

//echo "$username<br>";
//echo "$password<br>";
//
//$query = "SELECT first_name FROM `hk_users` WHERE username = '$username' AND password = '$password'";
//
//if(mysqli_query($conn,$query)){
//  $exe = mysqli_query($conn,$query);
//  while($row = mysqli_fetch_array($exe)){
//    echo "name = ".$row['first_name'];
//    session_start();
//    $_SESSION['username'] = $username;
////      header('Location: ../index.php');
//  }
//}
//else{
//    echo "sorry".mysqli_error($conn);
//}



// example


session_start();


date_default_timezone_set("Asia/calcutta");
$_SESSION['timestart']=date('Y-m-d H:i:s');
session_regenerate_id(true);
$_SESSION['username']= $_POST['username'];
$_SESSION['sid']= session_id();


 if(isset($_POST["login"])){
     $exe = mysqli_query($conn,"SELECT HKR.role,HKU.id FROM `hk_users` AS HKU left JOIN hk_roles AS HKR on HKR.id = HKU.role_id WHERE HKU.username ='$username' && HKU.password ='$password'");
     $row = mysqli_fetch_array($exe);
     if(is_array($row)){
         $_SESSION['role'] = $row['role'];
         $_SESSION['id'] = $row['id'];
         
         //userlog code
         
         $userlogQuery = "INSERT INTO `hk_user_log` (`user_name`, `session_id`, `login_time`) VALUES ('".$_SESSION['username']."', '".$_SESSION['sid']."', '".$_SESSION['timestart']."')";
         
         mysqli_query($conn,$userlogQuery);
         
         
         header("Location: ../index.php");
     }else{
         header("Location: ../loginn.php");
         
     }
 }




?>
