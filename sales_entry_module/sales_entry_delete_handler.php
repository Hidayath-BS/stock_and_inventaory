<?php
require('../dbconnect.php');
$delId = $_POST["delete"];


$ondate = date("Y-m-d");

//fect sales details
$salefetchQ = "SELECT * FROM `hk_sales` WHERE id='$delId'";
$stockfetchExe = mysqli_query($conn,$salefetchQ);
while($stockRow = mysqli_fetch_array($stockfetchExe)){
    $total_amount_received = $stockRow["total_amount_received"];
    $balpre_paid = $stockRow["balance_paid"];
    $final_sales_bal = $stockRow["sales_balance"];
    $cust_id=$stockRow["person_id"];

    echo $total_amount_received."<br/>";
    echo "<br/>";
    echo $balpre_paid."<br/>";
    echo "<br/>";
    echo $final_sales_bal."<br/>";
    echo "<br/>";
    echo $cust_id."<br/>";
    echo "<br/>";
}

//fetch person balance
$fetch_Pbal="SELECT * FROM `hk_person_balance` WHERE person_id='$cust_id'";
$balfetchExe = mysqli_query($conn,$fetch_Pbal);
while ($balRow=mysqli_fetch_array($balfetchExe)) {
    $balperson_id=$balRow["person_id"];
    $balperson_amt=$balRow["balance_amount"];

    echo $balperson_id."<br/>";
    echo "<br/>";
    echo $balperson_amt."<br/>";
    echo "<br/>";
}

$calMinus = ($balperson_amt - $final_sales_bal) + $balpre_paid ;
    echo $calMinus."<br/>";
    echo "<br/>";


//customer balance update
$custbalentry = "UPDATE `hk_person_balance` SET
balance_amount='$calMinus' WHERE person_id='$cust_id'";
mysqli_query($conn,$custbalentry);

//transaction table update
$transupdate="UPDATE `hk_transaction_table` SET `transaction_active`=0 WHERE
`account_head`='SALES' AND `respective_table_id`='$delId'";
mysqli_query($conn,$transupdate);

//sales update
$saleupdate="UPDATE `hk_sales` SET `sales_active`=0 WHERE id='$delId'";
mysqli_query($conn,$saleupdate);

//expense table update
$expenseUpdate="UPDATE `hk_sales_expenses` SET `expenses_active`=0 WHERE
`sales_id`='$delId'";
mysqli_query($conn,$expenseUpdate);

//commission update
$commissionUpdate="UPDATE `hk_sales_commission` SET `commission_active`=0 WHERE
`sales_id`='$delId'";
mysqli_query($conn,$commissionUpdate);

//fetch sales products
$fetchsaleProduct = "SELECT product_id,quantity,amount FROM `hk_sales_products`
WHERE sales_id='$delId'";
$array = array();
$i = 0;
$fetchsaleProductExe = mysqli_query($conn,$fetchsaleProduct);
while($fetchsaleProductRow = mysqli_fetch_assoc($fetchsaleProductExe)){
     // $array[] = $fetchsaleProductRow;
     $product_id = $fetchsaleProductRow['product_id'];
     $quantity = $fetchsaleProductRow['quantity'];

     $amount = $fetchsaleProductRow['amount'];
     // $quantity_type_id[]=$fetchsaleProductRow['quantity_type_id'];

     $stockUpdate="UPDATE `hk_stocks` SET `quantity`=`quantity`+'$quantity' WHERE
     `product_id`='$product_id'";
     echo $stockUpdate."<br>";
     $i++;

     if(mysqli_query($conn,$stockUpdate)){

        echo "success";
     }
     else{
        echo "soory";
     }

     // insert to stock tracker

     $stockTracker = "INSERT INTO `hk_stock_tracker`(`product_id`, `date`, `particulars`, `add_stock`, `sub_stock`, `amount`)
      VALUES ('$product_id','$ondate','DELETE SALES','$quantity','0','$amount')";

      if(mysqli_query($conn,$stockTracker)){
        echo "Succedss";
      }else{
        echo "Failed to insert in to stock tracker".mysqli_error($conn);
      }

}
// print_r($array); // show all array data

$detailsQ = "SELECT HKS.bill_number, HKS.total_amount,HKS.total_amount_received,HKST.sales_transaction_type,HKPR.first_name,HKPR.last_name,HKS.person_id as person_id FROM hk_sales AS HKS
left JOIN hk_sales_transaction_type AS HKST ON HKST.id = HKS.sales_transaction_type_id
left JOIN hk_persons AS HKPR ON HKPR.id = HKS.person_id WHERE HKS.id = '$delId'";

$detailsExe = mysqli_query($conn,$detailsQ);
while($detailsRow = mysqli_fetch_array($detailsExe)) {
  // code...
  $bill_number = $detailsRow["bill_number"];
  $person_name = $detailsRow["first_name"]." ".$detailsRow["last_name"];
  $person_id = $detailsRow["person_id"];
  $total_amount = $detailsRow["total_amount"];
  $total_amount_received = $detailsRow["total_amount_received"];
  $sales_transaction_type = $detailsRow["sales_transaction_type"];



}

$bal = $total_amount-$total_amount_received;


if($sales_transaction_type == "CREDIT"){
// credit sales entry
  // 2 entries in cash book
  $update1 = "UPDATE hk_cash_book SET active = 0 WHERE particulars LIKE '%CR S To $person_name, Bill No: $bill_number'";
  if(mysqli_query($conn,$update1)){
    echo "Success update1<br>";
  }else{
    echo "Sorry".mysqli_error($conn);
  }

  $update2 = "UPDATE hk_cash_book SET active = 0 WHERE particulars LIKE '%To $person_name account being the CR S, Bill No: $bill_number'";

  if(mysqli_query($conn,$update2)){
    echo "Success update2<br>";
  }else{
    echo "Sorry".mysqli_error($conn);
  }


  // 1 entry in sales_account
  $update4 = "UPDATE hk_sales_account SET active = 0 WHERE bill_number =$bill_number AND person_id = $person_id";

  if(mysqli_query($conn,$update4)){
    echo "success deleted from purchase account";
  }
  else{
    echo "Failure".mysqli_error($conn);
  }




  // 1 entry in particular account
  $update3 = "UPDATE hk_account_$person_id SET active = 0 WHERE particulars LIKE '%CR S on Bill No: $bill_number'";
  if(mysqli_query($conn,$update3)){
    echo "Sucess in updaing accont <br>";
  }else{
    echo "Failure in Updating account <br>";
  }




}else{
  if($bal == 0){
    // fullcash entry
    // cashbook entry and sales_account entry
    $cash_update1 = "UPDATE hk_cash_book SET active = 0 WHERE particulars
    LIKE '%Cash S To $person_name Bill No: $bill_number'";

    if(mysqli_query($conn,$cash_update1)){
      echo "Sccess deleting cash book entry";
    }else{
      echo "Sorry".mysqli_error($conn);
    }

    // 1 entry in sales_account
    $update4 = "UPDATE hk_sales_account SET active = 0 WHERE bill_number =$bill_number AND person_id = $person_id";

    if(mysqli_query($conn,$update4)){
      echo "success deleted from purchase account";
    }
    else{
      echo "Failure".mysqli_error($conn);
    }



  }else{
    // partial cash entry
    // 3 cash book entries
    // deletes cash type entry in cash book
    $part_update1 = "UPDATE hk_cash_book SET active = 0 WHERE particulars LIKE '%Cash S To $person_name Bill No: $bill_number'";

    if(mysqli_query($conn,$part_update1)){
      echo "Success in deleting cash type entry";
    }
    else{
      echo "Sorry".mysqli_error($conn);
    }


    // credit type entry
    $part_update2 = "UPDATE hk_cash_book SET active = 0 WHERE particulars LIKE '%CR S To $person_name, Bill No: $bill_number'";

    if(mysqli_query($conn,$part_update2)){
      echo "Success in deleting credit type entry in cash book";
    }else{
      echo "Sorry".mysqli_error($conn);
    }


    // to account entry in cash book
    $part_update3 = "UPDATE hk_cash_book SET active = 0 WHERE particulars LIKE '%To $person_name account being the CR S, Bill No: $bill_number'";

    if(mysqli_query($conn,$part_update3)){
      echo "Success deleting to account";
    }else{
      echo "Sorry".mysqli_error($conn);
    }


    // 1 person account
      // deletes from person account
      $part_update5 = "UPDATE hk_account_$person_id SET active = 0 WHERE particulars LIKE '%CR S on Bill No: $bill_number'";

      if(mysqli_query($conn,$part_update5)){
        echo "Successin deleting data from particular account";
      }else{
        echo "Sorry".mysqli_error($conn);
      }




    // 1 sales account

    $part_update4 = "UPDATE hk_sales_account SET active = 0 WHERE bill_number =$bill_number AND person_id = $person_id";

    if(mysqli_query($conn,$part_update4)){
      echo "Success in deleting purchase account enty";
    }else{
      echo "Sorry".mysqli_error($conn);
    }







  }
}



header('Location: ../sales_entry_list.php');

?>
