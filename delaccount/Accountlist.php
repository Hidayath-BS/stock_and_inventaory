<?php

if($_POST["acctype"]){

require("../dbconnect.php");

$query = "SELECT id,first_name,last_name FROM `hk_persons` WHERE person_type_id =".$_POST["acctype"]." AND person_active = 1 ORDER BY `first_name`";

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