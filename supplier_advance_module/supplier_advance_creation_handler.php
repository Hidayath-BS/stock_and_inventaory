<?php
session_start();
require('../dbconnect.php');
date_default_timezone_set("Asia/calcutta");
$date = $_POST["ondate"];
$supplier_id =$_POST['supplier_id'];
$advance_type_id =$_POST['advance_type_id'];
$amount =$_POST['amount'];

$advanceParticulars = $_POST["particulars"];
$cashBal = 0;
$personBal = 0;

$suppliername = "SELECT first_name,last_name from `hk_persons` WHERE id = '$supplier_id'";
$exe = mysqli_query($conn,$suppliername);
$row = mysqli_fetch_array($exe);
$sname = $row['first_name']." ".$row['last_name'];

$getVoucherNo = "SELECT MAX(voucher_no) FROM `hk_transaction_table` WHERE 1";
$exe = mysqli_query($conn,$getVoucherNo);
$row = mysqli_fetch_array($exe);
$voucherNo = $row['MAX(voucher_no)'];
$voucherNo = $voucherNo+1;
$updateCash = "INSERT INTO `hk_transaction_table`( `transaction_date`, `voucher_no`,`account_head`, `particulars`,`payment`)
               VALUES ('$date','$voucherNo','ADVANCE','PAID to $sname AS Advance ','$amount')";
if(mysqli_query($conn,$updateCash)){
  $lastTransactionId = mysqli_insert_id($conn);
    echo "success";
    $query = " INSERT INTO `hk_supplier_advances` (`person_id`, `advance_type_id`, `amount`,`transaction_table_id`,`advance_date`)
                VALUES ('$supplier_id','$advance_type_id','$amount','$lastTransactionId','$date')";

    if(mysqli_query($conn,$query)){
        echo "success";
        $lastAdvanceId = mysqli_insert_id($conn);
        $updateTransaction = "UPDATE `hk_transaction_table` SET respective_table_id = '$lastAdvanceId' WHERE id = '$lastTransactionId'";
        if(mysqli_query($conn,$updateTransaction)){

          $cashBalQ = "SELECT balance FROM hk_cash_book ORDER BY id DESC LIMIT 1";
          $cashBalExe = mysqli_query($conn,$cashBalQ);
          while($cashBalRow = mysqli_fetch_array($cashBalExe)){
            $cashBal = $cashBalRow["balance"];
          }

            if($cashBal == ""){
              $cashBal=0;
            }

            $particulars = "Supplier Advance to $sname";
            $dr = $amount;
            $cr = 0;
            $balance = $cashBal + $dr;

            $Query = "INSERT INTO `hk_cash_book` (`particulars`, `date`, `cr`, `dr`, `balance`)
            VALUES ('$particulars','$date','$cr','$dr','$balance')";



            if(mysqli_query($conn,$Query)){
              echo "<br>";
              echo "success cash book entry";

              $query = "SELECT balance FROM hk_account_".$supplier_id." ORDER by id DESC LIMIT 1";
              $exe = mysqli_query($conn,$query);
              while($row = mysqli_fetch_array($exe)){
                $personBal = $row["balance"];
              }
            if($personBal == ""){
              $personBal = 0;
            }

            $particulars_person = "Supplier Advance ".$advanceParticulars;
            $dr_person = 0;
            $cr_person = $amount;
            $balance_person = $personBal - $cr_person;
              $query = "INSERT INTO `hk_account_".$supplier_id."` (`date`, `particulars`, `cr`, `dr`, `balance`)
                       VALUES ('$date','$particulars_person','$cr_person','$dr_person','$balance_person')";

                       if(mysqli_query($conn,$query)){
                        echo "Success particular person account";

                        $particulars_advance = "Supplier Advance to $sname ".$advanceParticulars;
                        $query = "INSERT INTO `hk_supplier_advance_account` (`date`, `particulars`, `amount`)
                                 VALUES ('$date','$particulars_advance','$amount')";
                                 echo "<br>";
                                 echo "$query";
                                 if(mysqli_query($conn,$query)){
                                  echo "Success advace account entry";
                                  $_SESSION['message']="Supplier Advance Added successfully";
                                    header("Location: ../supplier_advance_list.php");
                                }else{
                                  $_SESSION['message']="Sorry!!!".mysqli_error($conn);
                                  echo "failure advance account entry";
                                }

                      }else{
                        echo "failure particular person account";
                      }

            }
       else{
         echo "failure cash book entry";
       }

        }else{
            echo "sorry".mysqli_error($conn);
        }
    }
    else{
        echo "sorry".mysqli_error($conn);
    }
  }
  else{
      echo "sorry".mysqli_error($conn);
  }




?>
