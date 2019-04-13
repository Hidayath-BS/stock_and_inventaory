

<?php
include_once("dbconnect.php");
if($_REQUEST['empid']) {
	$sql = "SELECT sum(HKPP.final_quantity),HKP.name,HKP.type,HKQT.quantity_type
          FROM `hk_purchases` AS HKPR
          LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKPR.id
          LEFT JOIN `hk_products` AS HKP ON HKP.id = HKPP.product_id
          LEFT JOIN `hk_quantity_type` HKQT ON HKQT.id = HKPP.quantity_type_id
          WHERE HKPR.bill_date = '".$_REQUEST['empid']."' GROUP BY HKPP.product_id,HKPP.quantity_type_id";


//$sql = "SELECT hkp.name,hkp.type,hkpp.quantity , hkqt.quantity_type,hkpp.shrink,hkpp.final_quantity,hkpp.rate, hkpp.amount FROM hk_purchased_products as hkpp left JOIN hk_products as hkp on hkp.id = hkpp.product_id
//left JOIN hk_quantity_type as hkqt on hkqt.id = hkpp.quantity_type_id WHERE hkpp.purchase_id =1";

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
