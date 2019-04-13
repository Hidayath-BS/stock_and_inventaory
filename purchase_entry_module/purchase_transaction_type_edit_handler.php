<?php

require('../dbconnect.php');
$id = $_POST["transId"];
$name = $_POST["transName"];

$query =  "UPDATE `hk_purchase_transaction_type` SET `purchase_transaction_type` = '$name' WHERE `hk_purchase_transaction_type`.`id` = '$id'";

if(mysqli_query($conn,$query)){
    header('Location: ../purchase_transaction_type_list.php');
}
else{
    echo mysqli_error($conn);
}
?>
