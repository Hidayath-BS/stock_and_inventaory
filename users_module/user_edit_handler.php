<?php
require('../dbconnect.php');
$first_name = ucwords($_POST["first_name"]);
$last_name = ucwords($_POST["last_name"]);
$username = $_POST["username"];
$role = $_POST["role"];
$email_id = ($_POST["email"]);
$mobile_number = $_POST["mobile_number"];
$id = $_POST["code"];
$password=$_POST["password"];
$password = sha1($password);

$updatequery ="UPDATE `hk_users` SET first_name='$first_name', last_name='$last_name', username='$username',password='$password', role_id='$role',
mobile_number='$mobile_number', email='$email_id' WHERE id='$id'";

//UPDATE 'hk_customers' SET 'filed_name' = 'value' WHERE id=;



if(mysqli_query($conn,$updatequery)){
    header('Location: ../user_list.php');
}
else{
    echo "sorry".mysqli_error($conn);
}


?>
