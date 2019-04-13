<?php

if($_POST["date"]){

require("../../dbconnect.php");

$query = "SELECT hkp.first_name,hkp.last_name,hkpu.bill_number,hkpu.bill_date,hkptt.purchase_transaction_type,hkpu.net_weight,hkpu.amount_payable,hkpu.amount_paid, hkpu.cheque_number,hkpu.transaction_id,hkpu.location,hkpu.id, hkpu.paid_to FROM hk_purchases as hkpu left JOIN hk_persons as hkp on hkp.id = hkpu.person_id
left JOIN hk_purchase_transaction_type as hkptt on hkptt.id = hkpu.purchase_transaction_type_id where hkpu.purchases_active = 1 AND hkpu.bill_date='".$_POST["date"]."' ORDER BY hkpu.id DESC";

$resultset = mysqli_query($conn, $query) or die("database error:". mysqli_error($conn));

	$data = array();
 	 $i=0;


	while( $rows = mysqli_fetch_assoc($resultset) ) {
		$data[$i] = json_encode($rows);
        $i++;
	}


	echo json_encode($data);

} else {
	echo 0;
}






 ?>
