<?php

if($_POST["date"]){

require("../dbconnect.php");

$startdateTime = date('Y-m-d H:i:s',strtotime($_POST["date"]));


$endDate = date('Y-m-d H:i:s',strtotime($_POST["date"].'+ 1 day'));

$query = "SELECT * FROM `hk_user_log` WHERE login_time BETWEEN '$startdateTime' AND '$endDate'";


$resultset = mysqli_query($conn, $query) or die("database error:". mysqli_error($conn));

	$data = array();
 	 $i=0;


	while( $rows = mysqli_fetch_assoc($resultset) ) {
		$data[$i] = json_encode($rows);
        $i++;
	}


	echo json_encode($data);

} else {
	echo 0;
}






 ?>
