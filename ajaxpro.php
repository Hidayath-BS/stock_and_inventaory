<?php

   require('dbconnect.php');
   $id = $_GET['id'];


   $u_city_q = "SELECT * FROM hk_cities WHERE state_id = '$id'" ;

   $exe = mysqli_query($conn,$u_city_q);


   $json = [];
   while($row =mysqli_fetch_array($exe)){
        $json[$row['id']] = $row['city_name'];
      
   }


   echo json_encode($json);
?>
