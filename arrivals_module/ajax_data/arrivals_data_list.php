<?php

if($_POST["date"]){

require("../../dbconnect.php");

$query = "SELECT AKA.*, HKP.first_name, HKP.last_name FROM `hk_arrivals` AS AKA left JOIN hk_persons AS HKP ON HKP.id = AKA.supplier_id WHERE AKA.date = '".$_POST["date"]."' && AKA.active = 1";

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
