
<?php

include_once("../../dbconnect.php");
if($_POST['date']) {
	$sql = "SELECT hkpret.id,hkpret.date,hkpret.amount_recieved,
                         hkpret.cheque_number,hkpret.transaction_id,
                         hkpret.purchase_return_bill_number,hkperson.first_name,
                         hkperson.last_name,hkp.bill_number,
                         hkptt.purchase_transaction_type
                         FROM hk_purchases_return AS hkpret
                         left JOIN hk_purchases AS hkp on hkp.id = hkpret.purchase_id
                         left JOIN hk_persons AS hkperson ON hkperson.id = hkp.person_id
                         left JOIN hk_purchase_transaction_type AS hkptt ON hkptt.id = hkpret.transaction_type_id
                         WHERE hkpret.date = '".$_POST['date']."'
                         ORDER BY hkpret.id DESC";


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
