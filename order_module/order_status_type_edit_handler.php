<?php
require('../dbconnect.php');
$type = ucwords($_POST["type"]);
$id= $_POST["submit"];




$updatequery ="UPDATE `hk_orders_status_type` SET order_status_type='$type'  WHERE id='$id'";

//UPDATE 'hk_customers' SET 'filed_name' = 'value' WHERE id=;



if(mysqli_query($conn,$updatequery)){
    header('Location: ../order_status_type_list.php');
}
else{
    echo "sorry".mysqli_error($conn);
}


?>
