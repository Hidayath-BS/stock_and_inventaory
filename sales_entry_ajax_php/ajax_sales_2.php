
<?php
include_once("../dbconnect.php");
if($_REQUEST['empid']) {
	$id = $_REQUEST['empid'];
	$sql = "SELECT HKO.*,HKP.name,HKP.type FROM `hk_orders` as HKO left join `hk_products` as HKP on HKO.product_id = HKP.id WHERE orders_active = 1 && HKO.id='$id'";
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
