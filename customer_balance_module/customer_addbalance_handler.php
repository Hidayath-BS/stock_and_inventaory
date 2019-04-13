<?php
session_start();
require('../dbconnect.php');

$customer_id = $_POST["customer_id"];
$date = $_POST["date"];
$particulars = $_POST["particulars"];
$amount = $_POST["amount"];
$personBal = 0;
$cashBal=0;

$query = "SELECT first_name,last_name FROM hk_persons WHERE id = '$customer_id'";
$cashBalExe = mysqli_query($conn,$query);
while($row = mysqli_fetch_array($cashBalExe)){
  $customerName = $row["first_name"]." ".$row["last_name"];
}


$cashBalQ = "SELECT balance FROM hk_cash_book ORDER BY id DESC LIMIT 1";
$cashBalExe = mysqli_query($conn,$cashBalQ);
while($cashBalRow = mysqli_fetch_array($cashBalExe)){
  $cashBal = $cashBalRow["balance"];
}

if($cashBal == ""){
  $cashBal=0;
}

$particulars = $particulars." ".$customerName;
$cr = 0;
$dr = $amount;
$balance = $cashBal + $dr;
$Query = "INSERT INTO `hk_cash_book`(`particulars`, `date`, `cr`, `dr`, `balance`)
VALUES ('$particulars','$date','$cr','$dr','$balance')";

if(mysqli_query($conn,$Query)){
  echo "Success cash book entry";

  $query1 = "SELECT balance FROM hk_account_".$customer_id." ORDER BY id DESC LIMIT 1";
  echo $query1;
  $exe = mysqli_query($conn,$query1);
  while($row = mysqli_fetch_array($exe)){
    $personBal = $row["balance"];
  }
  if($personBal == ""){
    $personBal = 0;
  }
echo  mysqli_error($conn);
  // $particulars_person = "Received Due Amount";
  $cr_person = $amount;
  $dr_person = 0;
  $balance_person = $personBal - $cr_person;

  $query = "INSERT INTO `hk_account_".$customer_id."`(`date`, `particulars`, `cr`, `dr`, `balance`)
  VALUES ('$date','$particulars','$cr_person','$dr_person','$balance_person')";

  if(mysqli_query($conn,$query)){
      $_SESSION['message']="Balance added successfully";
    echo "Suucess Person Entry";
    header('Location: ../add_customer_balance.php');
  }else{
      $_SESSION['message']="Sorry!!!".mysqli_error($conn);
    echo "Failure Person Entry";
  }


}
else{
  echo "Failure Cash Book Entry";
}



// $getVoucherNo = "SELECT MAX(voucher_no) FROM `hk_transaction_table`";
// $exe = mysqli_query($conn,$getVoucherNo);
// $row = mysqli_fetch_array($exe);
// $voucherNo = $row['MAX(voucher_no)'];
// $voucherNo= $voucherNo+1;
//
// echo "<br>$voucherNo";
//
// $getCustomerrNamequery = "SELECT HKPB.*,HKP.first_name,HKP.last_name
//                           FROM `hk_person_balance` AS HKPB
//                           LEFT JOIN `hk_persons` AS HKP ON HKPB.person_id = HKP.id
//                           WHERE HKPB.id = '$cust_id'";
//
// $exe = mysqli_query($conn,$getCustomerrNamequery);
// $editRow = mysqli_fetch_array($exe);
// $customerName = $editRow['first_name']." ".$editRow['last_name'];
//
// echo "<br>$customerName";
//
//
// $particulars = "Opening balance of ". $customerName;
//
// $updateCashTablequery = "INSERT INTO `hk_transaction_table` (`transaction_date`,`voucher_no`,`account_head`,`particulars`,`balance`)
//      VALUES ('$date','$voucherNo','CUSTOMER BALANCE','$particulars','$amount')";
//
// echo "<br>$updateCashTablequery";
//
//
//  if(mysqli_query($conn,$updateCashTablequery)){
//  	$last_trans_id = mysqli_insert_id($conn);
//
//  	// update person balance table
//  	// we have person id here so where clause will be wrt person id in person_balance table
//
//  	$updatePersonBalance = "UPDATE hk_person_balance SET `balance_amount` = `balance_amount`+'$amount' WHERE `person_id` = '$cust_id'";
//  	if(mysqli_query($conn,$updatePersonBalance)){
//
//  		// get balance id form person_balance table using where clause person_id
//
//  		$balQuery = "SELECT id FROM hk_person_balance WHERE person_id='$cust_id'";
//
//  		$balExe = mysqli_query($conn,$balQuery);
//  		$balrow = mysqli_fetch_array($balExe);
//
//  		// insert entry in person balance tracker table
//  		$amount = -1 * abs($amount);
//  		$insQbaltracker = "INSERT INTO `hk_person_balance_tracker`(`balance_id`, `amount`, `transaction_table_id`) VALUES ('".$balrow["id"]."','$amount','$last_trans_id')";
//  		if(mysqli_query($conn,$insQbaltracker)){
//  			header("Location:../customer_receivable_list.php");
//  		}else{
//  			echo "Sorry ".mysqli_error($conn);
//  		}
//
//  	}
//  	else{
//
//  		echo mysqli_error($conn);
//  	}
//
//
//
//
//
//
//  }
//  else{
//  	echo mysqli_error($conn);
//  }

?>
