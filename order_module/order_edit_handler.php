<?php
require('../dbconnect.php');
$customer_id = $_POST["customer_id"];
$product_id = $_POST["product_id"];
$quantity = $_POST["quantity"];
$order_status_id = $_POST["order_status_id"];
$id= $_POST["submit"];




$updatequery ="UPDATE `hk_orders` SET customer_id = $customer_id,  product_id = $product_id,
               quantity = '$quantity', status_type_id = '$order_status_id' WHERE id='$id'";

//UPDATE 'hk_customers' SET 'filed_name' = 'value' WHERE id=;



if(mysqli_query($conn,$updatequery)){
    header('Location: ../order_list.php');
}
else{
    echo "sorry".mysqli_error($conn);
}


?>