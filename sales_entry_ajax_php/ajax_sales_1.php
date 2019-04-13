<?php
	include_once("../dbconnect.php");
	$id=$_GET['id'];
	$sql = "SELECT HKO.* , HKP.name , HKP.type FROM `hk_orders` AS HKO LEFT JOIN `hk_products` As HKP ON HKO.product_id = HKP.id WHERE orders_active=1 && customer_id='$id' && HKO.status_type_id=1" ;
	$exe= mysqli_query($conn , $sql);

	$json = [];
	while ($row=mysqli_fetch_array($exe)) {
		# code...
		$json[$row['id']] = $row['name']." ".$row['type']." ".$row['quantity']." "."Kg";

	}
	echo json_encode($json);
?>
