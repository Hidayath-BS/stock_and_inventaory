<?php
require('../dbconnect.php');
$type = $_POST["type"];



$query = " INSERT INTO `hk_quantity_type` (`quantity_type`) VALUES 
 ('$type')";

if(mysqli_query($conn,$query)){
    echo "success";
    header("Location: ../quantity_type_list.php");
}
else{
    echo "sorry".mysqli_error($conn);
}


?>