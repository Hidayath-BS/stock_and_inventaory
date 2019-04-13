<?php

require("../dbconnect.php");



function test($username){
	require("../dbconnect.php");
	$password = $_POST["password"];
	$password = sha1($password);
	$query = "SELECT HKR.role,HKU.id FROM `hk_users` AS HKU left JOIN hk_roles AS HKR on HKR.id = HKU.role_id WHERE HKU.username ='$username' AND HKU.password ='$password'";
	echo $query;

	$exe = mysqli_query($conn,$query);

	echo "<br> hi ";

	while($row = mysqli_fetch_array($exe)){
		$role = $row["role"];
	}

echo "<br> $role";
if($role == ""){
	return false;
}else{
	if($role == "ADMIN"){
		return true;
	}else{
		return false;
	}
}
}

$account = $_POST["account"];

$fromdate = $_POST["fromdate"];
$todate = $_POST["todate"];


session_start();
$username = $_SESSION['username'];
$password = $_POST["password"];



echo "$fromdate<br>$todate<br>$password<br>";

if(test($username)){

	$query = "UPDATE hk_account_$account SET active = 0 WHERE date BETWEEN '$fromdate' AND '$todate'";
	if(mysqli_query($conn,$query)){
		$_SESSION['msg'] = "successfully closed account from $fromdate to $todate";	
	}else{
		$_SESSION['msg'] = "something went wrong";	
	}

	// echo "<br> $query";
	
	header("Location: ../close_account.php");
}else{
	echo "Sorry something went wrong";
	$_SESSION['msg'] = "Sorry something went wrong/ you are not ADMIN to do this";
	header("Location: ../close_account.php");
}


?>