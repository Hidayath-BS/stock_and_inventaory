<?php
session_start();
require("dbconnect.php");
    date_default_timezone_set("Asia/calcutta");
    $sessionend = date('Y-m-d H:i:s');
    $sessionId = $_SESSION['sid'];
    $sessionusername = $_SESSION['username'];
if(isset($_POST["logout"])){
    session_unset();
     session_destroy();
    
    $updateLoguttimeQuery = "UPDATE `hk_user_log` SET `logout_time` = '$sessionend' WHERE `hk_user_log`.`session_id` = '$sessionId' && `hk_user_log`.`user_name` = '$sessionusername'";
    
    mysqli_query($conn,$updateLoguttimeQuery);
    
    
    
     header('Location: loginn.php');
}else{
  echo "sorry";
}
 ?>
