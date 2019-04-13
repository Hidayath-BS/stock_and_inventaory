<?php
include_once("dbconnect.php");
if($_REQUEST['empid']) {
	$sql = "SELECT SUM(amount) as total FROM hk_supplier_advances where person_id='".$_REQUEST['empid']."' && `supplier_advances_active`=1";
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
