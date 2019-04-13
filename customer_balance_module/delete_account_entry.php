<?php

session_start();

$trackerId = $_POST["submit"];
require('../dbconnect.php');

// get details from tracker entry

$trackerQ = "SELECT * FROM hk_balance_recovery_tracker WHERE id='$trackerId'";

$trackerExe = mysqli_query($conn,$trackerQ);
while($trackerRow = mysqli_fetch_array($trackerExe)){
	$cashBookId = $trackerRow["cashbook_id"];
	$personId = $trackerRow["person_id"];
	$accountId = $trackerRow["account_id"];
}




// set active =0 in cashbook

$cashBookQ = "UPDATE `hk_cash_book` SET `active`='0' WHERE `id`='$cashBookId'";

// set active = 0 in particular account 
$personAccount = "UPDATE hk_account_".$personId." SET `active`='0' WHERE `id`='$accountId'";


// set active=0 in balance recover tracker
$tracker = "UPDATE `hk_balance_recovery_tracker` SET `active`='0' WHERE `id`='$trackerId'";




echo $cashBookQ."<br>";
echo $personAccount; 

if(mysqli_query($conn,$personAccount)){
	if(mysqli_query($conn,$cashBookQ)){
		if(mysqli_query($conn,$tracker)){

			$_SESSION["message"]="Balance delete opertion successful";

			header("Location: ../customer_receivable_list.php");
		}
		else{
			$_SESSION["message"]= mysqli_error($conn);

			header("Location: ../customer_receivable_list.php");	
		}
	}
	else{
		$_SESSION["message"]= mysqli_error($conn);

		header("Location: ../customer_receivable_list.php");
	}
}
else{
	$_SESSION["message"]= mysqli_error($conn);

	header("Location: ../customer_receivable_list.php");
}



?>