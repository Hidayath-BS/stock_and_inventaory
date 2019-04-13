<?php
session_start();
require('../dbconnect.php');
$due_amount = $_POST["remainingdue"];
$paid_amount = $_POST["enterd_amount"];
$id= $_POST["id"];
$date = $_POST["date"];
$supplierName = $_POST["supplier_name"];
$remarks = $_POST["particulars"];
$cashBal =0;
$personBal = 0;


if($remarks == ""){
  $remarks = "Due Cleared";
}



$cashBalQ = "SELECT balance FROM hk_cash_book ORDER BY id DESC LIMIT 1";
$cashBalExe = mysqli_query($conn,$cashBalQ);
while($cashBalRow = mysqli_fetch_array($cashBalExe)){
  $cashBal = $cashBalRow["balance"];
}

if($cashBal == ""){
  $cashBal=0;
}

$particulars = "Paid Due Amount To : $supplierName";
$cr = 0;
$dr = $paid_amount;
$balance = $cashBal + $dr;
$Query = "INSERT INTO `hk_cash_book`(`particulars`, `date`, `cr`, `dr`, `balance`)
VALUES ('$particulars','$date','$cr','$dr','$balance')";

if(mysqli_query($conn,$Query)){
  echo "Success cash book entry";

  $query = "SELECT balance FROM hk_account_".$id." ORDER by id DESC LIMIT 1";
  $exe = mysqli_query($conn,$query);
  while($row = mysqli_fetch_array($exe)){
    $personBal = $row["balance"];
  }
  if($personBal == ""){
    $personBal = 0;
  }


  $particulars_person = $remarks;
  $cr_person = $paid_amount;
  $dr_person = 0;
  $balance_person = $personBal - $cr_person;

  $query = "INSERT INTO `hk_account_".$id."`(`date`, `particulars`, `cr`, `dr`, `balance`)
  VALUES ('$date','$particulars_person','$cr_person','$dr_person','$balance_person')";

  if(mysqli_query($conn,$query)){
      $_SESSION['message']="Due cleared successfully";
    echo "Success Person Entry";
    header('Location: ../supplier_payable_list.php');
  }else{
    $_SESSION['message']="Sorry!!!".mysqli_error($conn);
    echo "Failure Person Entry";
  }


}
else{
  echo "Failure Cash Book Entry";
}





// $getVoucherNo = "SELECT MAX(voucher_no) FROM `hk_transaction_table` WHERE 1";
// $exe = mysqli_query($conn,$getVoucherNo);
// $row = mysqli_fetch_array($exe);
// $voucherNo = $row['MAX(voucher_no)'];
// $voucherNo=$voucherNo+1;
//
// $getSupplierNamequery = "SELECT HKPD.*,HKP.first_name,HKP.last_name
//                           FROM `hk_person_due` AS HKPD
//                           LEFT JOIN `hk_persons` AS HKP ON HKPD.person_id = HKP.id
//                           WHERE HKPD.id = '$id'";
//
// $exe = mysqli_query($conn,$getSupplierNamequery);
// $editRow = mysqli_fetch_array($exe);
// $supplierName = $editRow['first_name']." ".$editRow['last_name'];
// $particulars = "Paid Due Amount to ". $supplierName;
//
// $updateCashTablequery = "INSERT INTO `hk_transaction_table` (`transaction_date`,`voucher_no`,`account_head`,`particulars`,`payment`)
//                           VALUES ('$date','$voucherNo','SUPPLIER DUE','$particulars','$paid_amount')";
//
//  if(mysqli_query($conn,$updateCashTablequery)){
//    $lastTransactionId = mysqli_insert_id($conn);
//    $insertquery = "INSERT INTO `hk_person_due_tracker` (`due_id`,`amount`,`transaction_table_id`)
//                    VALUES ('$id','$paid_amount','$lastTransactionId')";
//
//      if(mysqli_query($conn,$insertquery)){
//        $updatequery = "UPDATE `hk_person_due` SET due_amount = '$due_amount' WHERE id = '$id'";
//
//          if(mysqli_query($conn,$updatequery)){
//
//
//
//
//
//        }
//        else{
//            echo "sorry".mysqli_error($conn);
//        }
//    }
//    else{
//        echo "sorry".mysqli_error($conn);
//    }
//
//  }
//  else{
//      echo "sorry".mysqli_error($conn);
//  }



?>
