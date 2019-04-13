<?php
include_once("dbconnect.php");
if($_REQUEST['empid']) {
	$id = $_REQUEST['empid'];
	$sql = "SELECT balance FROM hk_account_$id ORDER BY id DESC LIMIT 1";
	$resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));

	$data = array();
	while( $rows = mysqli_fetch_assoc($resultset) ) {
		$data = $rows;
	}
	if(empty($data) || $data['balance']>0){
		$data['balance'] = 0;
	}
	echo json_encode($data);
} else {
	echo 0;
}
?>
