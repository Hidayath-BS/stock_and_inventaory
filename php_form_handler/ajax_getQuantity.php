<?php
include_once("../dbconnect.php");
if($_REQUEST['empid']) {
	$sql = "SELECT HKQT.quantity_type FROM `hk_products` HKP left JOIN hk_quantity_type as HKQT on HKQT.id = HKP.quantity_type_id WHERE HKP.id =".$_REQUEST['empid'];
	$resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
	
	$data = array();
	while( $rows = mysqli_fetch_assoc($resultset) ) {
		$data = $rows;
	}
	echo json_encode($data);
} else {
	echo 0;	
}
?>
