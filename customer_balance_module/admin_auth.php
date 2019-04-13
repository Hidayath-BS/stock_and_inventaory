<?php

require('../dbconnect.php');

session_start();

function test(){
  require("../dbconnect.php");
  $username = $_POST["username"];
  $password = $_POST["password"];
  $password = sha1($password);
  $role = "";
  $query = "SELECT HKR.role,HKU.id FROM `hk_users` AS HKU left JOIN hk_roles AS HKR on HKR.id = HKU.role_id WHERE HKU.username ='$username' AND HKU.password = '$password'";
  // echo $query;

  $exe = mysqli_query($conn,$query);

 


  while($row = mysqli_fetch_array($exe)){
  	// print_r($row);

  	if(count($row)!=0){
  		$role = $row["role"];	
  	}else{
  		$role = "";
  	}
    
  }



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

echo "<br>".test();
if(test()){
	$_SESSION['message'] = "admin authentication successful";
  $_SESSION['date'] = $_POST["date"];
	$_SESSION["auth_balance"] = true;
	header("location: ../customer_receivable_list.php");
}else{
	$_SESSION["auth_balance"] = false;
	$_SESSION['message'] = "username / password missmatch";
	header("location: ../admin_auth_login.php");
}

?>