<?php
require('../dbconnect.php');

$salestrans_type = $_POST['type'];
$salestrans_type_id = $_POST['salestrans_type_id'];

$query = "UPDATE `hk_sales_transaction_type` SET `sales_transaction_type` = '$salestrans_type' WHERE `id` =".$salestrans_type_id;

if(mysqli_query($conn,$query)){
    header('Location: ../sales_transaction_type_list.php');
}
else{
    echo "sorry";
}



?>
