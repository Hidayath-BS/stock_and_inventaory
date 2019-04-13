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



session_start();


date_default_timezone_set("Asia/calcutta");
$_SESSION['timestart']=date('Y-m-d H:i:s');
session_regenerate_id(true);
 
$_SESSION['sid']= session_id();

$error = "username/passwod incorrect";
$roleError = "You don't have admin permission";

 if(isset($_POST["login"])){
     $exe = mysqli_query($conn,"SELECT HKR.role,HKU.id,HKU.username FROM `hk_users` AS HKU left JOIN hk_roles AS HKR on HKR.id = HKU.role_id WHERE HKU.username ='$username' && HKU.password ='$password'");
     $row = mysqli_fetch_array($exe);
     if(is_array($row)){

        $role = $row['role'];

        if($role == "ADMIN"){
         $_SESSION['admin_role'] = $row['role'];
         $_SESSION['admin_id'] = $row['id'];
         $_SESSION['admin_username']= $row['username'];
         
         header("Location: ../adminpermission.php");
        }else{
            $_SESSION['error'] =$roleError;   
            header("Location: ../adminlogin.php");
        }

         
     }else{
         
         $_SESSION['error'] = $error;
         
         header("Location: ../adminlogin.php");
         
     }
 }




?>
