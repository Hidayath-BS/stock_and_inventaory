<?php

require('../dbconnect.php');

$del = $_POST["delete"];

$ondate = date("Y-m-d");

//SELECT TRANSACTION_TABLE_ID AND BALANCE_RECEIVED AT PERTICULAR PURCHASE ENTRY
$pickpurchaseData = "SELECT transaction_table_id,balance_received,person_id FROM hk_purchases WHERE id=$del";
echo "$pickpurchaseData<br>";

$purchaseExe = mysqli_query($conn,$pickpurchaseData);
while($purchaseRow = mysqli_fetch_array($purchaseExe)){
    $transTableId = $purchaseRow["transaction_table_id"];
    $balanceReceived = $purchaseRow["balance_received"];
    $personID = $purchaseRow["person_id"];
}


//SELECT TRANSACTION_BALANCE FROM TRANSACTIO_TABLE
$pickTransBalance = "SELECT balance,due FROM hk_transaction_table WHERE id=$transTableId";

echo "$pickTransBalance<br>";

$TransBalanceExe = mysqli_query($conn,$pickTransBalance);
while($TransBalanceRow = mysqli_fetch_array($TransBalanceExe)){
    $TransBalance = $TransBalanceRow["balance"];
    $TransDue = $TransBalanceRow["due"];
}
echo "BALANCE IS :$TransBalance<br>";


//UPDATE PURCHASE TABLE ACTIVE 0

$purchaseActive = "UPDATE hk_purchases SET `purchases_active`=0 WHERE `id`=$del";

echo "$purchaseActive<br>";
mysqli_query($conn,$purchaseActive);


//GET STOCK  DETAILS

$stockDetails = "SELECT final_quantity,product_id,amount FROM hk_purchased_products WHERE purchase_id = $del";

$ProductDetils = array();
$ProductCount = 0;

$stockExe = mysqli_query($conn,$stockDetails);

while($stockRow = mysqli_fetch_array($stockExe)){
    $ProductDetils[$ProductCount]['product_id']=$stockRow['product_id'];
    $ProductDetils[$ProductCount]['amount']=$stockRow['amount'];
    $ProductDetils[$ProductCount]['final_quantity']=$stockRow['final_quantity'];
    $ProductCount++;
}

print_r($ProductDetils);

foreach($ProductDetils as $products){
    $pro = $products['product_id'];
    $amount = $products['amount'];
    $final_qty = $products['final_quantity'];

    //UPDATE STOCK QUERY

    $updateStockQ = "UPDATE `hk_stocks` SET `quantity` = `quantity`-$final_qty WHERE product_id = '$pro'";
    echo "<br>$updateStockQ<br>";


    // insert to stock tracker

    $stockTracker = "INSERT INTO `hk_stock_tracker`(`product_id`, `date`, `particulars`, `add_stock`, `sub_stock`, `amount`)
     VALUES ('$pro','$ondate','DELETE','0','$final_qty','$amount')";

     if(mysqli_query($conn,$stockTracker)){
       echo "Succedss";
     }else{
       echo "Failed to insert in to stock tracker".mysqli_error($conn);
     }

    mysqli_query($conn,$updateStockQ);
}


//UPDATE TRANSACTION_TABLE

$updateTransTable = "UPDATE `hk_transaction_table` SET `transaction_active` =0 WHERE `id`=$transTableId";

echo "<br>$updateTransTable";

mysqli_query($conn,$updateTransTable);

//UPDATE EXPENSES ON PERTICULAR PURCHASE ACTIVE=0

$updateExpenseTable = "UPDATE `hk_purchase_expenses` SET `expenses_active`=0 WHERE `purchase_id`=$del";

echo "<br>$updateExpenseTable";

mysqli_query($conn,$updateExpenseTable);

//UPDATE COMMISSION ON PERTICULAR PURCHASE COMMISSION_ACTIVE=0

$updateCommTable = "UPDATE `hk_purchase_commission` SET `commission_active` = 0 WHERE `purchase_id`=$del";

echo "<br>$updateCommTable";
mysqli_query($conn,$updateCommTable);

//UPDATE BALANCE OF SUPPLIER

$updateBalance = "UPDATE `hk_person_balance` SET `balance_amount`= `balance_amount`-$TransBalance+$balanceReceived WHERE `person_id`= $personID";

echo "<br>$updateBalance";

mysqli_query($conn,$updateBalance);

//UPDATE DUE OF SUPPLIER

$udateDue = "UPDATE `hk_person_due` SET `due_amount`= `due_amount`-$TransDue WHERE person_id=$personID";

echo "<br>$udateDue";

mysqli_query($conn,$udateDue);


// get billnumber, transaction type and person name from hk_purchase

$detailsQ = "SELECT HKP.bill_number,HKPT.purchase_transaction_type,HKP.amount_payable,HKP.amount_paid, HKPR.first_name,HKPR.last_name,HKPR.id as person_id FROM hk_purchases as HKP
left JOIN hk_persons as HKPR ON HKPR.id = HKP.person_id
left JOIN hk_purchase_transaction_type AS HKPT ON HKPT.id = HKP.purchase_transaction_type_id
WHERE HKP.id ='$del'";

$detailsExe = mysqli_query($conn,$detailsQ);

while($detailsRow = mysqli_fetch_array($detailsExe)){
  $bill_number = $detailsRow["bill_number"];
  $person_name = $detailsRow["first_name"]." ".$detailsRow["last_name"];
  $trans_type = $detailsRow["purchase_transaction_type"];
  $person_id = $detailsRow["person_id"];
  $amount_payable = $detailsRow["amount_payable"];
  $amount_paid = $detailsRow["amount_paid"];
}

$dueAmount = $amount_payable - $amount_paid;

if($trans_type == "CREDIT"){
// credit type of purchase


  // update cashbook entry based on particular entered(1)
  $update1 = "UPDATE hk_cash_book SET active = 0 WHERE particulars LIKE '%CR P From $person_name, Bill No: $bill_number'";
  if(mysqli_query($conn,$update1)){
    echo "Success update1<br>";
  }else{
    echo "Sorry".mysqli_error($conn);
  }




  // update cashbook entry based on particular entered(2)

  $update2 = "UPDATE hk_cash_book SET active = 0 WHERE particulars LIKE '%from $person_name account being the CR P, Bill No: $bill_number'";

  if(mysqli_query($conn,$update2)){
    echo "Success update2<br>";
  }else{
    echo "Sorry".mysqli_error($conn);
  }

// update person Account
  $update3 = "UPDATE hk_account_$person_id SET active = 0 WHERE particulars LIKE '%CR P on Bill No: $bill_number'";
  if(mysqli_query($conn,$update3)){
    echo "Sucess in updaing accont <br>";
  }else{
    echo "Failure in Updating account <br>";
  }

// update purchase account table

  $update4 = "UPDATE hk_purchase_account SET active = 0 WHERE bill_number =$bill_number AND person_id = $person_id";

  if(mysqli_query($conn,$update4)){
    echo "success deleted from purchase account";
  }
  else{
    echo "Failure".mysqli_error($conn);
  }

}else{
  // cash type of purchase

// first know weather its a fullcash or partial cash purchase_entry
if($dueAmount == 0){
  // full cash purchase
  // entries will be in cash and purchase account
  $cash_update1 = "UPDATE hk_cash_book SET active = 0 WHERE particulars
  LIKE '%Cash P From $person_name Bill No: $bill_number'";

  if(mysqli_query($conn,$cash_update1)){
    echo "Sccess deleting cash book entry";
  }else{
    echo "Sorry".mysqli_error($conn);
  }


// delete from purchase account

  $cash_update2 = "UPDATE hk_purchase_account SET active = 0 WHERE bill_number =$bill_number AND person_id = $person_id";

  if(mysqli_query($conn,$cash_update2)){
    echo "Success deleting from purchase account";
  }else{
    echo "Sorry".mysqli_error($conn);
  }

}else{

// partial cash  purchase

// entries will be in cash_book(3), purchase_account(1), individal account(1)

// deletes cash type entry in cash book
$part_update1 = "UPDATE hk_cash_book SET active = 0 WHERE particulars LIKE '%Cash P From $person_name Bill No: $bill_number'";

if(mysqli_query($conn,$part_update1)){
  echo "Success in deleting cash type entry";
}
else{
  echo "Sorry".mysqli_error($conn);
}

$part_update2 = "UPDATE hk_cash_book SET active = 0 WHERE particulars LIKE '%CR P From $person_name, Bill No: $bill_number'";

if(mysqli_query($conn,$part_update2)){
  echo "Success in deleting credit type entry in cash book";
}else{
  echo "Sorry".mysqli_error($conn);
}

$part_update3 = "UPDATE hk_cash_book SET active = 0 WHERE particulars LIKE '%from $person_name account being the CR P, Bill No: $bill_number'";

if(mysqli_query($conn,$part_update3)){
  echo "Success deleting to account";
}else{
  echo "Sorry".mysqli_error($conn);
}


// purchase account delete entry

$part_update4 = "UPDATE hk_purchase_account SET active = 0 WHERE bill_number =$bill_number AND person_id = $person_id";

if(mysqli_query($conn,$part_update4)){
  echo "Success in deleting purchase account enty";
}else{
  echo "Sorry".mysqli_error($conn);
}

// Delete from particular person account
  $part_update5 = "UPDATE hk_account_$person_id SET active = 0 WHERE particulars LIKE '%CR P on Bill No: $bill_number'";

  if(mysqli_query($conn,$part_update5)){
    echo "Successin deleting data from particular account";
  }else{
    echo "Sorry".mysqli_error($conn);
  }


}



}





header('Location: ../purchase_entry_list.php');


?>
