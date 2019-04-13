

<?php
include_once("dbconnect.php");
if($_REQUEST['empid']) {
	$sql = "SELECT hkp.name,hkp.type,hkop.quantity , hkp.quantity_type
FROM hk_ordered_products as hkop left JOIN hk_products as hkp on hkp.id = hkop.product_id
 WHERE hkop.order_id ='".$_REQUEST['empid']."'";



//$sql = "SELECT hkp.name,hkp.type,hkop.quantity , hkqt.quantity_type
//FROM hk_ordered_products as hkop left JOIN hk_products as hkp on hkp.id = hkop.product_id
//left JOIN hk_quantity_type as hkqt on hkqt.id = hkop.quantity_type_id WHERE hkop.order_id =1";

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
