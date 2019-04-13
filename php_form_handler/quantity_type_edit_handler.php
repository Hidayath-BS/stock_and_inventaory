<?php

require('../dbconnect.php');
$qty_type = $_POST["qty_type"];
$qty_id = $_POST["qty_id"];

$query =  "UPDATE `hk_quantity_type` SET `quantity_type`='$qty_type' WHERE id='$qty_id'";

if(mysqli_query($conn,$query)){
    header('Location: ../quantity_type_list.php');
//    echo "success";
}
else{
    echo mysqli_error($conn);
}
?>
