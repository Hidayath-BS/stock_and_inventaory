<?php

if($_POST["date"]){

require("../../dbconnect.php");

$query = "SELECT SUM(cr) as credits, SUM(dr) as debits FROM `hk_cash_book` WHERE date < '".$_POST["date"]."' AND `active`='1'";

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
