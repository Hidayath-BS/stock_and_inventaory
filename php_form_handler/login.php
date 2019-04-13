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
function checkAccess(){
require("../dbconnect.php");
$query = "SELECT access FROM `hk_admin_access` WHERE id =1";
$exe = mysqli_query($conn,$query);
while($row = mysqli_fetch_array($exe)){
$access = $row["access"];
}
return $access;
}


session_start();


date_default_timezone_set("Asia/calcutta");
$_SESSION['timestart']=date('Y-m-d H:i:s');
session_regenerate_id(true);

$_SESSION['sid']= session_id();

$error = "username/passwod incorrect";

$error2 ="Please ask admin to grant access";

 if(isset($_POST["login"])){
     $exe = mysqli_query($conn,"SELECT HKR.role,HKU.id,HKU.username FROM `hk_users` AS HKU left JOIN hk_roles AS HKR on HKR.id = HKU.role_id WHERE HKU.username ='$username' && HKU.password ='$password' AND `users_active`=1");
     $row = mysqli_fetch_array($exe);
     $status=checkAccess();

     if(is_array($row)){
       if($status==1){
         $_SESSION['role'] = $row['role'];
         $_SESSION['id'] = $row['id'];
         $_SESSION['username']= $row['username'];
         //userlog code

         $userlogQuery = "INSERT INTO `hk_user_log` (`user_name`, `session_id`, `login_time`) VALUES ('".$_SESSION['username']."', '".$_SESSION['sid']."', '".$_SESSION['timestart']."')";

         mysqli_query($conn,$userlogQuery);


         header("Location: ../index.php");
       }
       else{
         $_SESSION['error'] = $error2;

         header("Location: ../loginn.php");
       }
     }else{

         $_SESSION['error'] = $error;

         header("Location: ../loginn.php");

     }
 }




?>
