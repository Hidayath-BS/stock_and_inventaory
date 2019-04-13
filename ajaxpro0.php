<?php

   require('dbconnect.php');
  // $id = $_GET['id'];


   $u_city_q = "SELECT amount FROM `hk_supplier_advances` where id= 1" ;

   $exe = mysqli_query($conn,$u_city_q);


   $json = [];
   while($row =mysqli_fetch_array($exe)){
        $json['val'] = $row['amount'];
          $json['something'] = 'bla bla';
   }


   echo json_encode($json);
?>
