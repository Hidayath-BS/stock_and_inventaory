<?php
session_start();
require('../dbconnect.php');
$balance_amount = $_POST["remainingBalance"];
$paid_amount = $_POST["enterd_amount"];
$id= $_POST["id"];
$date = $_POST["date"];
$supplierName = $_POST["supplier_name"];
$remarks = $_POST["particulars"];

if($remarks == ""){
  $remarks = "Balance Recovered";
}

$cashBalQ = "SELECT balance FROM hk_cash_book ORDER BY id DESC LIMIT 1";
$cashBalExe = mysqli_query($conn,$cashBalQ);
while($cashBalRow = mysqli_fetch_array($cashBalExe)){
  $cashBal = $cashBalRow["balance"];
}

if($cashBal == ""){
  $cashBal=0;
}

$particulars = "Received Balance Amount From : $supplierName";
$cr = $paid_amount;
$dr = 0;
$balance = $cashBal - $cr;
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
  $cr_person = 0;
  $dr_person = $paid_amount;
  $balance_person = $personBal + $dr_person;

  $query = "INSERT INTO `hk_account_".$id."`(`date`, `particulars`, `cr`, `dr`, `balance`)
  VALUES ('$date','$particulars_person','$cr_person','$dr_person','$balance_person')";

  if(mysqli_query($conn,$query)){
      $_SESSION['message']="Balance cleared successfully";
    echo "Suucess Person Entry";
    header('Location: ../supplier_receivable_list.php');
  }else{
      $_SESSION['message']="Sorry!!!".mysqli_error($conn);
    echo "Failure Person Entry";
  }



}
else{
  echo "Faliure cash book entry";
}





// $getVoucherNo = "SELECT MAX(voucher_no) FROM `hk_transaction_table` WHERE 1";
// $exe = mysqli_query($conn,$getVoucherNo);
// $row = mysqli_fetch_array($exe);
// $voucherNo = $row['MAX(voucher_no)'];
// $voucherNo= $voucherNo+1;
//
// $getSupplierNamequery = "SELECT HKPB.*,HKP.first_name,HKP.last_name
//                           FROM `hk_person_balance` AS HKPB
//                           LEFT JOIN `hk_persons` AS HKP ON HKPB.person_id = HKP.id
//                           WHERE HKPB.id = '$id'";
//
// $exe = mysqli_query($conn,$getSupplierNamequery);
// $editRow = mysqli_fetch_array($exe);
// $supplierName = $editRow['first_name']." ".$editRow['last_name'];
//
// $particulars = "Received Balance Amount from ". $supplierName;
//
// $updateCashTablequery = "INSERT INTO `hk_transaction_table` (`transaction_date`,`voucher_no`,`account_head`,`particulars`,`receipts`)
//                           VALUES ('$date','$voucherNo','SUPPLIER BALANCE','$particulars','$paid_amount')";
//
//  if(mysqli_query($conn,$updateCashTablequery)){
//    $lastTransactionId = mysqli_insert_id($conn);
//
//    $insertquery = "INSERT INTO `hk_person_balance_tracker` (`balance_id`,`amount`,`transaction_table_id`)
//                    VALUES ('$id','$paid_amount','$lastTransactionId')";
//
//      if(mysqli_query($conn,$insertquery)){
//
//        $updatequery = "UPDATE `hk_person_balance` SET balance_amount = '$balance_amount' WHERE id = '$id'";
//
//          if(mysqli_query($conn,$updatequery)){
//            header('Location: ../supplier_receivable_list.php');
//        }
//        else{
//            echo "sorry".mysqli_error($conn);
//        }
//    }
//    else{
//        echo "sorry".mysqli_error($conn);
//    }
//
//
//  }
//  else{
//      echo "sorry".mysqli_error($conn);
//  }



?>
