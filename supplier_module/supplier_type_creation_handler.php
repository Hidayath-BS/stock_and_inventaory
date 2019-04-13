<?php
session_start();
require('../dbconnect.php');
$supp_type= ucwords($_POST['name']);
$query = " INSERT INTO `hk_person_role_type`( `person_role_type`,`person_type_id`) VALUES ('$supp_type',1)";
if(mysqli_query($conn,$query)){
  $_SESSION['message']="Supplier Type has been added successfully";
    header('Location: ../supplier_type_list.php');
}
else{
  $_SESSION['message']="Sorry!!!".mysqli_error($conn);
    mysqli_error($conn);
}
?>
