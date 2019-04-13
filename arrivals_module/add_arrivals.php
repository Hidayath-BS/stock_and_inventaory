<?php

require('../dbconnect.php');

session_start();

// date
$ondate = $_POST["ondate"];
// supplier Id 
$supplier_id = $_POST["supplier_id"];

// weighbill Slip Number
$slipnumber = $_POST["slipnumber"];

$rate = $_POST["rate"];

$quantity = $_POST["quantity"];

$advance = $_POST["advance"];

$amountPayable = $rate * $quantity;

$amountBalance = $amountPayable - $advance;


$query = "INSERT INTO `hk_arrivals`(`date`, `weigh_bill_number`, `supplier_id`, `rate`, `quantity`, `advance`, `amount_payable`, `amount_paid`, `amount_balance`, `payment_status`, `active`)
 VALUES ('$ondate','$slipnumber','$supplier_id','$rate','$quantity','$advance','$amountPayable','$advance','$amountBalance',0,1)";

$cashQuery = "INSERT INTO `hk_cash_book`(`voucher_number`, `particulars`, `date`, `cr`, `dr`, `active`) 
VALUES (0,'PAID AS ADVANCE , WEIGH BILL Num : $slipnumber','$ondate',0,$advance,1)";

$personAccQuery = "INSERT INTO `hk_account_".$supplier_id."`(`date`, `particulars`, `cr`, `dr`, `active`) 
VALUES ('$ondate','PAID AS ADVANCE , WEIGH BILL NUM : $slipnumber',$advance,0,1)";



// echo $query;

if(mysqli_query($conn, $query)){
    $arrivalsid = mysqli_insert_id($conn);

    if(mysqli_query($conn,$cashQuery)){
        $cashEntryid = mysqli_insert_id($conn);
        if(mysqli_query($conn,$personAccQuery)){
            $personAccountid = mysqli_insert_id($conn);
            $mapQuery = "INSERT INTO `hk_arrivals_transaction_map`(`arrival_id`, `person_id`, `person_account_id`, `cash_id`, `amount`, `active`, `date`) VALUES ($arrivalsid,$supplier_id, $personAccountid, $cashEntryid,$advance,1 , '$ondate')";
            if(mysqli_query($conn, $mapQuery)){
                $_SESSION['message']="Arrivals Added Successfully";
                header("Location: ../arrivalsentry.php");
            }else{
                $_SESSION['message']="Sorry!!!".mysqli_error($conn);
                header("Location: ../arrivalsentry.php");
            }
            
        }else{
            $_SESSION['message']="Sorry!!!".mysqli_error($conn);
            header("Location: ../arrivalsentry.php");
        }

    }else{
        $_SESSION['message']="Sorry!!!".mysqli_error($conn);
        header("Location: ../arrivalsentry.php");
    }
    // echo "<br>Success";
    
}else{
    // echo "<br>Failure".mysqli_error($conn);
    $_SESSION['message']="Sorry!!!".mysqli_error($conn);
    header("Location: ../arrivalsentry.php");
}


?>