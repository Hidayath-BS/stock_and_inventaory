

<?php
include_once("dbconnect.php");
if($_REQUEST['empid']) {
	$sql = "SELECT hksp.amount,hksp.quantity,hkp.name,hkp.type,hksp.rate
	FROM hk_sales_return_products as hksp
	left JOIN hk_products AS hkp on hkp.id = hksp.product_id 
left JOIN hk_sales_return as hksr on hksr.id = hksp.sales_return_id
WHERE hksp.sales_return_id ='".$_REQUEST['empid']."'";


//$sql = "SELECT hkrp.amount,hkrp.quantity,hkp.name,hkp.type,hkrp.rate FROM hk_purchase_return_products as hkrp left JOIN hk_products AS hkp on hkp.id = hkrp.product_id left
//JOIN hk_quantity_type as hkqt on hkqt.id = hkrp.quantity_type_id left JOIN hk_purchases_return as hkpr on hkpr.id = hkrp.purchase_return_id WHERE hkrp.purchase_return_id =9";

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
