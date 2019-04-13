<?php
session_start();
require('../dbconnect.php');
$type = ucwords($_POST["type"]);
$id = $_POST["code"];


$updatequery ="UPDATE `hk_supplier_advance_type` SET supplier_advance_type='$type' WHERE id='$id'";

//UPDATE 'hk_customers' SET 'filed_name' = 'value' WHERE id=;



if(mysqli_query($conn,$updatequery)){
  $_SESSION['message']="Supplier Type has been edited successfully";
    header('Location: ../supplier_advance_type_list.php');
}
else{
    $_SESSION['message']="Sorry!!!".mysqli_error($conn);
    echo "sorry".mysqli_error($conn);
}


?>
