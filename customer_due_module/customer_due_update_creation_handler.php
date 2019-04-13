<?php
session_start();
require('../dbconnect.php');
$due_amount = $_POST["remainingdue"];
$paid_amount = $_POST["enterd_amount"];
$id= $_POST["id"];
$date = $_POST["date"];
$customerName = $_POST["customer_name"];
$remarks = $_POST["particulars"];
$cashBal =0;
$personBal = 0;

if($remarks == ""){
  $remarks= "Clearing Due";
}

// $getVoucherNo = "SELECT MAX(voucher_no) FROM `hk_transaction_table` WHERE 1";
// $exe = mysqli_query($conn,$getVoucherNo);
// $row = mysqli_fetch_array($exe);
// $voucherNo = $row['MAX(voucher_no)'];
// $voucherNo=$voucherNo+1;
//
// $getCustomerNamequery = "SELECT HKPD.*,HKP.first_name,HKP.last_name
// FROM `hk_person_due` AS HKPD
// LEFT JOIN `hk_persons` AS HKP ON HKPD.person_id = HKP.id
// WHERE HKPD.id = '$id'";
//
// $exe = mysqli_query($conn,$getCustomerNamequery);
// $editRow = mysqli_fetch_array($exe);
// $customerName = $editRow['first_name']." ".$editRow['last_name'];
// $particulars = "Paid Due Amount to ". $customerName;

// $updateCashTablequery = "INSERT INTO `hk_transaction_table` (`transaction_date`,`voucher_no`,`account_head`,`particulars`,`payment`)
// VALUES ('$date','$voucherNo','CUSTOMER DUE','$particulars','$paid_amount')";
//
// if(mysqli_query($conn,$updateCashTablequery)){
//   $lastTransactionId = mysqli_insert_id($conn);
  // $insertquery = "INSERT INTO `hk_person_due_tracker` (`due_id`,`amount`,`transaction_table_id`)
  // VALUES ('$id','$paid_amount','$lastTransactionId')";
  //
  // if(mysqli_query($conn,$insertquery)){
    // $updatequery = "UPDATE `hk_person_due` SET due_amount = '$due_amount' WHERE person_id = '$id'";
    //
    // if(mysqli_query($conn,$updatequery)){

      $cashBalQ = "SELECT balance FROM hk_cash_book ORDER BY id DESC LIMIT 1";
      $cashBalExe = mysqli_query($conn,$cashBalQ);
      while($cashBalRow = mysqli_fetch_array($cashBalExe)){
        $cashBal = $cashBalRow["balance"];
      }

      if($cashBal == ""){
        $cashBal=0;
      }

      $particulars = "Paid Due Amount To : $customerName";
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
          echo "Success Person Entry";
          $_SESSION['message']="Due cleared successfully";
          header('Location: ../customer_payable_list.php');
        }else{
          $_SESSION['message']="Sorry!!!".mysqli_error($conn);
          echo "Failure Person Entry";
        }


      }
      else{
        echo "Failure Cash Book Entry";
      }




    // }
    // else{
    //   echo "sorry".mysqli_error($conn);
    // }
  // }
  // else{
  //   echo "sorry".mysqli_error($conn);
  // }

// }
// else{
//   echo "sorry".mysqli_error($conn);
// }



?>
