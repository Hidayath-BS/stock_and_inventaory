<?php
session_start();
require('../dbconnect.php');
$cust_type= ucwords($_POST['cust_type']);
$query = " INSERT INTO `hk_person_role_type`( `person_role_type`,`person_type_id`) VALUES ('$cust_type',2)";
if(mysqli_query($conn,$query)){
  $_SESSION['message']="Customer Type has been Added successfully";
    header('Location: ../customer_type_list.php');
}
else{
    $_SESSION['message']="Sorry!!!".mysqli_error($conn);
    mysqli_error($conn);
}
?>
