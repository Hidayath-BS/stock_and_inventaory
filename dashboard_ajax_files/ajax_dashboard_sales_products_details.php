

<?php
include_once("../dbconnect.php");
if($_REQUEST['empid']) {
	$sql = "SELECT sum(HKSP.quantity) as quantity,HKP.name,HKP.type,HKP.quantity_type
          FROM hk_sales AS HKS
          LEFT JOIN `hk_sales_products` AS HKSP ON HKSP.sales_id = HKS.id
          LEFT JOIN `hk_products` AS HKP ON HKP.id = HKSP.product_id
          WHERE HKS.bill_date = '".$_REQUEST['empid']."' GROUP BY HKSP.product_id";


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
