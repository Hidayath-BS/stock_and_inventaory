<?php
session_start();
$userid = $_SESSION['id'];
require('../dbconnect.php');

//declaring variables
$sale_id_edit=$_POST["sale_id_edit"];//sale id
$cust_name = ucwords($_POST["cust_name"]);
$bill_number = $_POST["bill_number"];
$weigh_number=$_POST["weigh_number"];
$vehicle_number =$_POST["vehicle_number"];
$driver_phone = ucwords($_POST["driver_phone"]);
$comm_percent = $_POST["comm_percent"];
$comm_amount = $_POST["comm_amount"];
$balrece=$_POST["balrece"];
$check_number = $_POST["chequeNumber"];
$transType = $_POST["transType"];
$transaction_id2=$_POST["transaction_id2"];
$order_id_fetched=$_POST["order_id_fetched"];//order id
//previous balance recovered
$pre_balreceived=$_POST["pre_balreceived"];
//previous total paid
$pre_duepay=$_POST["pre_duepay"];

//expenses array
$expenses = array();
$expenses = $_POST["expenses"];
$expenses = array_map('array_filter',$expenses);
$expenses = array_filter($expenses);


//sales array
$sales = array();
$sales = $_POST["sale"];
$sales = array_map('array_filter',$sales);
$sales = array_filter($sales);


//radio button entry
if($transType == 1){
    $transaction_id = $_POST["stotalreceivable"];//payable amount
    $totalPaid = $_POST["totalPaid"];//paid amount
    $duepay = $_POST["duepay"];//due payable



}
else if($transType == 2) {
    $transaction_id = $_POST["transaction_id"];//payable amount
    $totalPaid = 0;//paid amount
    $duepay = $transaction_id;//due payable
}



//fect sales details
$salefetchQ = "SELECT * FROM `hk_sales` WHERE id='$sale_id_edit'";
$stockfetchExe = mysqli_query($conn,$salefetchQ);
while($stockRow = mysqli_fetch_array($stockfetchExe)){
    $total_amount_received = $stockRow["total_amount_received"];
    $balpre_paid = $stockRow["balance_paid"];
    $final_sales_bal = $stockRow["sales_balance"];
    $cust_id=$stockRow["person_id"];

    //echo $total_amount_received."<br/>";
    //echo "<br/>";
    //echo $balpre_paid."<br/>";
    //echo "<br/>";
    //echo $final_sales_bal."<br/>";
    //echo "<br/>";
    //echo $cust_id."<br/>";
    //echo "<br/>";
}

//fetch person balance
$fetch_Pbal="SELECT * FROM `hk_person_balance` WHERE person_id='$cust_id'";
$balfetchExe = mysqli_query($conn,$fetch_Pbal);
while ($balRow=mysqli_fetch_array($balfetchExe)) {
    $balperson_id=$balRow["person_id"];
    $balperson_amt=$balRow["balance_amount"];

   // echo $balperson_id."<br/>";
    //echo "<br/>";
    //echo $balperson_amt."<br/>";
    //echo "<br/>";
}

// $calMinus = ($balperson_amt - $final_sales_bal) + $balpre_paid ;
//     echo $calMinus."<br/>";
//     echo "<br/>";


//customer balance update
$custbalentry = "UPDATE `hk_person_balance` SET
`balance_amount`= (`balance_amount`+'$pre_balreceived'-'$balrece'-'$pre_duepay')+'$duepay'
WHERE person_id='$cust_id'";
mysqli_query($conn,$custbalentry);

//echo "<br>".$custbalentry."<br>";




//fetch sales products
$fetchsaleProduct = "SELECT product_id,quantity FROM `hk_sales_products`
WHERE sales_id='$sale_id_edit'";
$array = array();
$i = 0;
$fetchsaleProductExe = mysqli_query($conn,$fetchsaleProduct);

if(!$fetchsaleProductExe){
  echo mysqli_error($conn)."This is error msg";
}
while($fetchsaleProductRow = mysqli_fetch_assoc($fetchsaleProductExe)){
     // $array[] = $fetchsaleProductRow;
     $product_id[] = $fetchsaleProductRow['product_id'];
     $quantity[] = $fetchsaleProductRow['quantity'];

     $stockUpdate="UPDATE `hk_stocks` SET `quantity`=`quantity`+'$quantity[$i]' WHERE
     `product_id`='$product_id[$i]'";
     //echo $stockUpdate."<br>";
     $i++;

     if(mysqli_query($conn,$stockUpdate)){
       // header('Location: ../sales_entry_list.php');
        echo "success";
     }
     else{
        echo "soory";
     }
}
// print_r($array); // show all array data


//delete ordered products
$ordered_pro_del="DELETE FROM `hk_ordered_products` WHERE order_id=$order_id_fetched";
$orderExe=mysqli_query($conn,$ordered_pro_del);

//delete sales products
$sales_pro_del="DELETE FROM `hk_sales_products` WHERE sales_id=$sale_id_edit";
$salesExe=mysqli_query($conn,$sales_pro_del);

//delete from expenses
$sales_exp_del="DELETE FROM `hk_sales_expenses` WHERE sales_id=$sale_id_edit";
$expenseExe=mysqli_query($conn,$sales_exp_del);


//fetch person name
$supplierQuery = "SELECT first_name, last_name FROM `hk_persons` WHERE id= $cust_name";
$supplierExe = mysqli_query($conn,$supplierQuery);
while($supplierRow = mysqli_fetch_array($supplierExe)){
    $supplierName = $supplierRow['first_name']." ".$supplierRow['last_name'];
}
//echo "<br>".$supplierName."<br>";
//end fetching name

//insert into order table
$insertOrderTable="UPDATE `hk_orders` SET `person_id`='$cust_name' WHERE
id=$order_id_fetched";
mysqli_query($conn,$insertOrderTable);


//transaction table entry
$transactionEntryQ = "UPDATE `hk_transaction_table` SET
`particulars`='SALES FROM : ".$supplierName."',`receipts`='$totalPaid',
`balance`='$duepay' WHERE `account_head`='SALES'
AND `respective_table_id`=$sale_id_edit";

mysqli_query($conn,$transactionEntryQ);

//insert to sales table
$query = "UPDATE `hk_sales` SET `person_id`='$cust_name',
`sales_transaction_type_id`='$transType',`total_amount`='$transaction_id',
`total_amount_received`='$totalPaid',`balance_paid`='$balrece',
`driver_phone`='$driver_phone',`cheque_number`='$check_number',
`transaction_id`='$transaction_id2',`sales_balance`='$duepay'
 WHERE id=$sale_id_edit";

//echo $query."<br>";
if(mysqli_query($conn,$query)){
    echo "Success<br>";

}
else{
    echo "sorry".mysqli_error($conn);
}

// $Update_trans_table="UPDATE `hk_transaction_table` SET `respective_table_id`='$last_id' WHERE id='$last_id_trans_table'";
// mysqli_query($conn,$Update_trans_table);



//print_r($sales);

foreach ($sales as $row) {
    echo "<br>\n".$row["'prod_id'"];
    $prouct_quantity = $row["'prod_id'"];
    $product_unit_price =$row["'prod_name'"];
    $unit_price_cal = $row["'quantity_entered'"];
    $prouct_id = $row["'qty_type'"];
    // $qty_type_id = $row["'qty_type_id'"];


    //insert into ordered products
    $insert_ordered_product="INSERT INTO `hk_ordered_products`
    (`order_id`,`product_id`,`quantity`,`quantity_type_id`)
    VALUES('$order_id_fetched','$prouct_id','$prouct_quantity','$qty_type_id')";

//echo "<br>$insert_ordered_product<br>";
    $insertPurchaseProd = "INSERT INTO `hk_sales_products` (`sales_id`, `product_id`, `quantity`,`rate`, `amount`) VALUES ('$sale_id_edit', '$prouct_id',
     '$prouct_quantity','$product_unit_price','$unit_price_cal')";

//echo "<br>$insertPurchaseProd<br>";

    //update stocks

    $updateStock = "update `hk_stocks` set `quantity`= `quantity`-'$prouct_quantity' where `product_id`='$prouct_id' && `quantity_type_id`='$qty_type_id'";

//echo "<br>$updateStock<br>";


        //echo  "<br>$insertPurchaseProd <br>";

    if(mysqli_query($conn, $insertPurchaseProd)){
        mysqli_query($conn,$updateStock);
         mysqli_query($conn,$insert_ordered_product);
    }
}

//commission table update
$comtabentry ="UPDATE `hk_sales_commission` SET `commission_percentage`='$comm_percent',
`commission_amount`='$comm_amount' WHERE `sales_id`='$sale_id_edit'";

//echo "<br>$comtabentry<br>";

mysqli_query($conn,$comtabentry);


//print_r($expenses);

foreach($expenses as $expenseRow){
    //echo "<br>".$expenseRow["id"]." ".$expenseRow["expense"];

    $expenseid = $expenseRow["id"];
    $expenseAmounnt = $expenseRow["expense"];

    if(is_null($expenseAmounnt)){
        $expenseAmounnt = 0;
    }

//    echo $expenseAmounnt." as expese amount";

    //add expense into purchase_expense table
    $expesesinsertQ = "INSERT INTO `hk_sales_expenses` (`sales_id`, `expense_type_id`, `amount`) VALUES ('$sale_id_edit', '$expenseid', '$expenseAmounnt')";

        //echo "<br> $expesesinsertQ <br>";

    mysqli_query($conn,$expesesinsertQ);

}



$detailsQ = "SELECT HKS.bill_number, HKS.total_amount,HKS.total_amount_received,HKST.sales_transaction_type,HKPR.first_name,HKPR.last_name,HKS.person_id as person_id FROM hk_sales AS HKS
left JOIN hk_sales_transaction_type AS HKST ON HKST.id = HKS.sales_transaction_type_id
left JOIN hk_persons AS HKPR ON HKPR.id = HKS.person_id WHERE HKS.id = '$sale_id_edit'";

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
  $update1 = "UPDATE hk_cash_book SET dr='$duepay',cr='0' WHERE particulars LIKE '%CR S To $person_name, Bill No: $bill_number'";
  if(mysqli_query($conn,$update1)){
    echo "Success update1<br>";
  }else{
    echo "Sorry".mysqli_error($conn);
  }

  $update2 = "UPDATE hk_cash_book SET dr='0',cr='$duepay' WHERE particulars LIKE '%To $person_name account being the CR S, Bill No: $bill_number'";

  if(mysqli_query($conn,$update2)){
    echo "Success update2<br>";
  }else{
    echo "Sorry".mysqli_error($conn);
  }


  // 1 entry in sales_account
  $update4 = "UPDATE hk_sales_account SET amount='$duepay',`credit/cash`='CREDIT' WHERE bill_number =$bill_number AND person_id = $person_id";

  if(mysqli_query($conn,$update4)){
    echo "success deleted from purchase account";
  }
  else{
    echo "Failure".mysqli_error($conn);
  }




  // 1 entry in particular account
  $update3 = "UPDATE hk_account_$person_id SET `cr`='$duepay',`dr`='0' WHERE particulars LIKE '%CR S on Bill No: $bill_number'";
  if(mysqli_query($conn,$update3)){
    echo "Sucess in updaing accont <br>";
  }else{
    echo "Failure in Updating account <br>";
  }




}else{
  if($bal == 0){
    // fullcash entry
    // cashbook entry and sales_account entry
    $cash_update1 = "UPDATE hk_cash_book SET `dr`='$totalPaid',`cr`='0' WHERE particulars
    LIKE '%Cash S To $person_name Bill No: $bill_number'";

    if(mysqli_query($conn,$cash_update1)){
      echo "Sccess deleting cash book entry";
    }else{
      echo "Sorry".mysqli_error($conn);
    }

    // 1 entry in sales_account
    $update4 = "UPDATE hk_sales_account SET `amount`='$totalPaid' WHERE bill_number =$bill_number AND person_id = $person_id";

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
    $part_update1 = "UPDATE hk_cash_book SET `dr`='$totalPaid',`cr`='0' WHERE particulars LIKE '%Cash S To $person_name Bill No: $bill_number'";

    if(mysqli_query($conn,$part_update1)){
      echo "Success in deleting cash type entry";
    }
    else{
      echo "Sorry".mysqli_error($conn);
    }


    // credit type entry
    $part_update2 = "UPDATE hk_cash_book SET `dr`='$duepay',`cr`='0' WHERE particulars LIKE '%CR S To $person_name, Bill No: $bill_number'";

    if(mysqli_query($conn,$part_update2)){
      echo "Success in deleting credit type entry in cash book";
    }else{
      echo "Sorry".mysqli_error($conn);
    }


    // to account entry in cash book
    $part_update3 = "UPDATE hk_cash_book SET `cr`='$duepay',`dr`='0' WHERE particulars LIKE '%To $person_name account being the CR S, Bill No: $bill_number'";

    if(mysqli_query($conn,$part_update3)){
      echo "Success deleting to account";
    }else{
      echo "Sorry".mysqli_error($conn);
    }


    // 1 person account
      // deletes from person account
      $part_update5 = "UPDATE hk_account_$person_id SET `cr`='$duepay',`dr`='0' WHERE particulars LIKE '%CR S on Bill No: $bill_number'";

      if(mysqli_query($conn,$part_update5)){
        echo "Successin deleting data from particular account";
      }else{
        echo "Sorry".mysqli_error($conn);
      }




    // 1 sales account

    $part_update4 = "UPDATE hk_sales_account SET `amount`='$duepay' WHERE bill_number ='$bill_number' AND person_id = '$person_id' AND particulars LIKE '%CR S from: $person_name'";

    if(mysqli_query($conn,$part_update4)){
      echo "Success in deleting purchase account enty";
    }else{
      echo "Sorry".mysqli_error($conn);
    }


    $part_update5 = "UPDATE hk_sales_account SET `amount`='$totalPaid' WHERE bill_number ='$bill_number' AND person_id = '$person_id' AND particulars LIKE '%Cash S To : $person_name'";

    if(mysqli_query($conn,$part_update5)){
      echo "Success in editing sales account";
    }else{
      echo "sorry".mysqli_error($conn);
    }



  }
}




header("Location: ../sales_entry_list.php");
?>
