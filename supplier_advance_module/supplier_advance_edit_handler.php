<?php
require('../dbconnect.php');

$advance_type_id = $_POST["advance_type_id"];
$amount = $_POST["amount"];
$id = $_POST["code"];

$getTransactionTableId = "SELECT 	transaction_table_id FROM `hk_supplier_advances` WHERE id = '$id'";
$exe = mysqli_query($conn,$getTransactionTableId);
$row = mysqli_fetch_array($exe);
$transaction_table_id = $row['transaction_table_id'];

$updatequery ="UPDATE `hk_supplier_advances`
              SET advance_type_id='$advance_type_id',
              amount='$amount' WHERE id='$id'";


if(mysqli_query($conn,$updatequery)){

    $updatequery ="UPDATE `hk_transaction_table`
                  SET payment='$amount'
                   WHERE id='$transaction_table_id'";


    if(mysqli_query($conn,$updatequery)){

        header('Location: ../supplier_advance_list.php');
    }
    else{
        echo "sorry".mysqli_error($conn);
    }
}
else{
    echo "sorry".mysqli_error($conn);
}


?>
