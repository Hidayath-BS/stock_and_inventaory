<?php

require('../dbconnect.php');
$transName = strtoupper($_POST["transName"]);
$transAddQuerry = "insert into `hk_purchase_transaction_type`(`purchase_transaction_type`) values('$transName')";

if(mysqli_query($conn,$transAddQuerry)){
    header('Location: ../purchase_transaction_type_list.php');
}
else{
    echo mysqli_error($conn);
}

?>
