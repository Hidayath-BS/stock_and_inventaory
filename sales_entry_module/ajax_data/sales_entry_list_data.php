<?php

if($_POST["date"]){

require("../../dbconnect.php");

$query = "SELECT hkp.first_name,hkp.last_name,hks.bill_number,hks.bill_date,hkstt.sales_transaction_type,hks.total_amount,hks.total_amount_received, hks.cheque_number,hks.transaction_id,hks.driver_phone,hks.id FROM hk_sales as hks left JOIN hk_persons as hkp on hkp.id = hks.person_id
left JOIN hk_sales_transaction_type as hkstt on hkstt.id = hks.sales_transaction_type_id where hks.sales_active = 1 AND hks.bill_date='".$_POST["date"]."' ORDER BY hks.id DESC";

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
