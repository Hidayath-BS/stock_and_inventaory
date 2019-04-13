<?php
require('../dbconnect.php');

$expenses_type = ucwords($_POST["expenses_type"]);
$expenses_code = $_POST["expenses_code"];

//$expenseCreateQuery = "insert into `hk_expenses_type`(`expenses_type`) values ('$expenses_type')";

$updateQuery = "UPDATE `hk_expenses_type` SET `expenses_type` = '$expenses_type' WHERE `hk_expenses_type`.`id` = '$expenses_code'";

if(mysqli_query($conn,$updateQuery)){
    header('Location: ../expense_type_list.php');
    
}
else{
    echo "sorry".mysqli_query($conn);
}



?>
