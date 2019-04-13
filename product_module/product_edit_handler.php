<?php
session_start();
require('../dbconnect.php');
$name = ucwords($_POST["name"]);
$product_type = ucwords($_POST["product_type"]);
$quantity_type = ucwords($_POST["quantity_type"]);
$id = $_POST["submit"];

$updatequery ="UPDATE `hk_products` SET name='$name', type='$product_type', quantity_type='$quantity_type' WHERE id='$id'";

if(mysqli_query($conn,$updatequery)){
    $_SESSION['message']="Product has been edited successfully";
    header('Location: ../product_list.php');
}
else{
  $_SESSION['message']="Sorry!!!".mysqli_error($conn);
    echo "sorry".mysqli_error($conn);
}


?>
