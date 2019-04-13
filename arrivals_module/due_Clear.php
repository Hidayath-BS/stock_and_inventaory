<?php
require('../dbconnect.php');

session_start();


$ondate = $_POST["ondate"];
$payAmount = $_POST["payAmount"];
$particulars = $_POST["particulars"];
$arrival = $_POST["arrival"];

$arrivalsget = "SELECT HKA.* , HKP.first_name, HKP.last_name FROM `hk_arrivals` as HKA left join hk_persons AS HKP ON HKP.id = HKA.supplier_id WHERE HKA.id = $arrival";

$exe = mysqli_query($conn, $arrivalsget);

$row = mysqli_fetch_array($exe);

$supplierName = $row["first_name"]." ".$row["last_name"];

$supplier_id = $row["supplier_id"];

$balanceAmount = $row["amount_balance"];



// Insert to cash account

$cashQuery = "INSERT INTO `hk_cash_book`(`voucher_number`, `particulars`, `date`, `cr`, `dr`, `active`) 
VALUES (0,'DUE CLEARED TO $supplierName','$ondate',0,$payAmount,1)";

// insert to person account
$personAccQuery = "INSERT INTO `hk_account_".$supplier_id."`(`date`, `particulars`, `cr`, `dr`, `active`) 
VALUES ('$ondate','DUE CLEARED TO $supplierName',$payAmount,0,1)";


// Update due Amount in arrivals

if($balanceAmount == $payAmount){
    $status = 2;
}else{
    $status = 1;
}

$updateArrivals = "UPDATE `hk_arrivals` SET `amount_paid`=`amount_paid`+$payAmount,`amount_balance`=`amount_balance`-$payAmount,`payment_status`=$status WHERE `id`= $arrival";

if(mysqli_query($conn, $cashQuery)){
    $cashId = mysqli_insert_id($conn);

    if(mysqli_query($conn, $personAccQuery)){
        $accid = mysqli_insert_id($conn);
        if(mysqli_query($conn, $updateArrivals)){
            
            // Insert Arrival Transaction Map

            $mapQuery = "INSERT INTO `hk_arrivals_transaction_map`(`arrival_id`, `person_id`, `person_account_id`, `cash_id`, `amount`, `active`, `date`)
             VALUES ($arrival,$supplier_id, $accid, $cashId,$payAmount,1 , '$ondate')";

             if(mysqli_query($conn, $mapQuery)){
                $_SESSION['message']="Due Cleared Successfully";
                header("Location: ../arrivalsList.php");
             }else{
                $_SESSION['message']="Sorry!!!".mysqli_error($conn);
            header("Location: ../arrivalsList.php");
             }


        }else{
            $_SESSION['message']="Sorry!!!".mysqli_error($conn);
            header("Location: ../arrivalsList.php");
        }
    }else{
        $_SESSION['message']="Sorry!!!".mysqli_error($conn);
            header("Location: ../arrivalsList.php");
    }


}else{
    $_SESSION['message']="Sorry!!!".mysqli_error($conn);
            header("Location: ../arrivalsList.php");
}





?>