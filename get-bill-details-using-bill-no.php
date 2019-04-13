<?php

   require('dbconnect.php');
   $id = $_GET['id'];

   $u_purchase_q = "SELECT hkper.first_name,hkper.last_name,hkpro.name, hkpro.type,
                    hkppro.final_quantity,hkpro.quantity_type,hkppro.rate,
                    hkppro.amount,hkpro.quantity_type,hkppro.product_id,hkpu.id,
                    hkper.id as person_id FROM hk_purchases as hkpu
                    left JOIN hk_purchased_products as hkppro ON hkppro.purchase_id = hkpu.id
                    left JOIN hk_persons as hkper on hkper.id = hkpu.person_id
                    left JOIN hk_products AS hkpro ON hkpro.id = hkppro.product_id
                    WHERE hkpu.bill_number='$id'" ;

   $exe = mysqli_query($conn,$u_purchase_q);


   $json = [];
    $i =0;
   while($row =mysqli_fetch_assoc($exe)){
        $json[$i] = $row;
       $i++;
   }


   echo json_encode($json);
?>
