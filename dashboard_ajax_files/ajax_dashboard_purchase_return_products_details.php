<?php
include_once("../dbconnect.php");
if($_REQUEST['empid']) {
	$sql = "SELECT sum(HKRP.quantity) as quantity,HKP.name,HKP.type,HKP.quantity_type
          FROM hk_purchases_return AS HKPR
          LEFT JOIN hk_purchase_return_products AS HKRP ON HKRP.purchase_return_id = HKPR.id
          LEFT JOIN `hk_products` AS HKP ON HKP.id = HKRP.product_id
          WHERE HKPR.date = '".$_REQUEST['empid']."' GROUP BY HKRP.product_id";


	$resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));

	$data = array();
$i=0;
	while( $rows = mysqli_fetch_assoc($resultset) ) {
		$data[$i] = $rows;
        $i++;
	}
	echo json_encode($data);
} else {
	echo 0;
}
?>
