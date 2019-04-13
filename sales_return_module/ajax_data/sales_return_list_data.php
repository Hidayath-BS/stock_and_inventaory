<?php

if($_POST["date"]){

require("../../dbconnect.php");

$query = "SELECT HKSR.*,HKS.bill_number as sales_bill_number,
 HKP.first_name,HKS.bill_number as sales_bill_number,
HKP.last_name,HKSTT.sales_transaction_type AS transaction_type
FROM `hk_sales_return` AS HKSR
LEFT JOIN `hk_sales` AS HKS ON HKSR.sales_id=HKS.id
LEFT JOIN `hk_persons` AS HKP ON HKS.person_id=HKP.id
LEFT JOIN `hk_sales_transaction_type` AS HKSTT ON HKSR.transaction_type_id=HKSTT.id
WHERE HKSR.date='".$_POST["date"]."'
 ORDER BY HKSR.id DESC";

function getName($person_id){
	require("../../dbconnect.php");
	$query = "SELECT first_name,last_name FROM hk_persons WHERE person_active=1 && id = $person_id";
	$exe = mysqli_query($conn,$query);
	while($row = mysqli_fetch_array($exe)){
		$name = $row["first_name"]." ".$row["last_name"];
	} 
	return $name;
}


$resultset = mysqli_query($conn, $query) or die("database error:". mysqli_error($conn));

	$data = array();
 	 $i=0;


	while( $rows = mysqli_fetch_assoc($resultset) ) {

		if($rows["first_name"]==""){
			$rows["first_name"] = getName($rows["person_id"]);
			$rows["last_name"] = "";
		}


		$data[$i] = json_encode($rows);
        $i++;
	}


	echo json_encode($data);

} else {
	echo 0;
}






 ?>
