

<?php
include_once("dbconnect.php");
if($_REQUEST['empid']) {
	$sql = "SELECT hkp.name,hkp.type,hkpp.quantity , hkp.quantity_type,hkpp.shrink,hkpp.final_quantity,hkpp.rate, hkpp.amount FROM hk_purchased_products as hkpp left JOIN hk_products as hkp on hkp.id = hkpp.product_id 
 WHERE hkpp.purchase_id ='".$_REQUEST['empid']."'";


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