<?php
session_start();
require('../dbconnect.php');

$cust_type = ucwords($_POST['cust_type']);
$cust_type_id = $_POST['cust_type_id'];

$query = "UPDATE `hk_person_role_type` SET `person_role_type` = '$cust_type' WHERE `id` =".$cust_type_id;

if(mysqli_query($conn,$query)){
  $_SESSION['message']="Customer Type has been edited successfully";
    header('Location: ../customer_type_list.php');
}
else{
  $_SESSION['message']="Sorry!!!".mysqli_error($conn);
    echo "sorry";
}



?>
