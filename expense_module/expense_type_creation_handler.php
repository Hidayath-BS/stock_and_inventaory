<?php
require('../dbconnect.php');

$expenses_type = ucwords($_POST["expenses_type"]);

$expenseCreateQuery = "insert into `hk_expenses_type`(`expenses_type`) values ('$expenses_type')";

if(mysqli_query($conn,$expenseCreateQuery)){
    header('Location: ../expense_type_list.php');
    
}
else{
    echo "sorry".mysqli_query($conn);
}



?>
