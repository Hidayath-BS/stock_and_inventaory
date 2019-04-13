

<?php
include_once("dbconnect.php");
if($_REQUEST['empid']) {
	$sql = "SELECT hkp.name,hkp.type,hksp.quantity ,hkp.quantity_type, hksp.rate, hksp.amount FROM hk_sales_products as hksp left JOIN hk_products as hkp on hkp.id = hksp.product_id 
 WHERE hksp.sales_id='".$_REQUEST['empid']."'";


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