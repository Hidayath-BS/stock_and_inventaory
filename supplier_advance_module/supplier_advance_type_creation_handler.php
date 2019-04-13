<?php
session_start();
require('../dbconnect.php');
$type=ucwords($_POST['type']);
$query = " INSERT INTO `hk_supplier_advance_type`(`supplier_advance_type`) VALUES ('$type')";
if(mysqli_query($conn,$query)){
  $_SESSION['message']="Supplier Type has been added successfully";
    header('Location: ../supplier_advance_type_list.php');
}
else{
  $_SESSION['message']="Sorry!!!".mysqli_error($conn);
    mysqli_error($conn);
    echo "not success";
}
?>
