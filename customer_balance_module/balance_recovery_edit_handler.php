<?php
require('../dbconnect.php');
session_start();
$date = $_POST["date"];
$amount = $_POST["amount"];
$Remarks = $_POST["Remarks"];
$person_id = $_POST["person_id"];
$account_id = $_POST["account_id"];
$cash_id = $_POST["cash_id"];

// account update
$accountUpdateQ = "UPDATE hk_account_".$person_id." SET `date`='$date',`particulars`='$Remarks',`dr`='$amount' WHERE `id`='$account_id'";
// cashbook update

$cashbookUpdateQ = "UPDATE `hk_cash_book` SET `date`='$date',`cr`='$amount' WHERE `id`='$cash_id'";

if(mysqli_query($conn,$accountUpdateQ)){
	if(mysqli_query($conn,$cashbookUpdateQ)){
		$_SESSION["message"]="Balance Recovery Edited Successfully";
		header("Location: ../customer_receivable_list.php");
	}	
	else{
		$_SESSION["message"]="Something Went wrong ".mysqli_error($conn);
		header("Location: ../customer_receivable_list.php");
	}	
	}
	else{
		$_SESSION["message"]="Something Went wrong ".mysqli_error($conn);
		header("Location: ../customer_receivable_list.php");
	}



 ?>