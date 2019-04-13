<?php
require('../dbconnect.php');
$type= ucwords($_POST['type']);
$query = " INSERT INTO `hk_sales_transaction_type`(`sales_transaction_type`) VALUES ('$type')";
if(mysqli_query($conn,$query)){
    header('Location: ../sales_transaction_type_list.php');
}
else{
    mysqli_error($conn);
    echo "not success";
}
?>
