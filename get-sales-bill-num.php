<?php

require('dbconnect.php');
$id = $_GET['id'];

   $u_sales_q =   "SELECT HKS.id as sales_id,HKS.bill_date,HKS.bill_number,HKPE.first_name,HKSP.quantity,
                  HKSP.rate,HKSP.amount,HKPE.last_name,HKP.id AS product_id,
                  HKP.name,HKP.type,HKP.quantity_type ,HKPE.id AS person_id
                  FROM `hk_sales` AS HKS
                  LEFT JOIN `hk_persons` AS HKPE ON HKS.person_id=HKPE.id
                  LEFT JOIN `hk_sales_products` AS HKSP ON HKSP.sales_id = HKS.id
                  LEFT JOIN `hk_products` AS HKP ON HKSP.product_id=HKP.id
                  WHERE HKS.bill_number = '$id'" ;

   $exe = mysqli_query($conn,$u_sales_q);


   $json = [];
   $i=0;
   while($row =mysqli_fetch_array($exe)){
        $json[$i] = $row;
        $i++;
   }


   echo json_encode($json);
?>
