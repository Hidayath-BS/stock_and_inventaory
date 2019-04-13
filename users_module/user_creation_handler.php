<?php
session_start();
require('../dbconnect.php');
$first_name =ucwords($_POST['first_name']);
$last_name =ucwords($_POST['last_name']);
$username =$_POST['username'];
$password =sha1($_POST['password']);
$role_id =$_POST['role_id'];
$mobile_number =$_POST['mobile_number'];
$email = $_POST['email'];

$query = " INSERT INTO `hk_users` (`first_name`, `last_name`, `username`, `password`, `role_id`, `mobile_number`,`email`) VALUES ('$first_name','$last_name','$username','$password','$role_id','$mobile_number','$email')";

if(mysqli_query($conn,$query)){
    echo "success";
      $_SESSION['message']="User has been added successfully";
    header("Location: ../user_list.php");
}
else{
    $_SESSION['message']="Sorry!!!".mysqli_error($conn);
    echo "sorry".mysqli_error($conn);
}


?>
