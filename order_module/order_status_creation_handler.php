<?php
require('../dbconnect.php');
$type = ucwords($_POST["type"]);

$query = " INSERT INTO `hk_orders_status_type` (`order_status_type`) VALUES
 ('$type')";

if(mysqli_query($conn,$query)){
    echo "success";
    header("Location: ../order_status_type_list.php");
}
else{
    echo "sorry".mysqli_error($conn);
}


?>
