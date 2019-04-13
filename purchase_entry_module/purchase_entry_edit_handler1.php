<?php

require('../dbconnect.php');


date_default_timezone_set("Asia/calcutta");
$date = date('Y-m-d');


$transType = $_POST["transType"];//purchase transaction type
$ondate = $_POST["ondate"];//bill date

echo "<br>$ondate";
$supplier_id = $_POST["supplier_id"];//person ID
$vehicleNumber = $_POST["vehicleNumber"];//vehicleNumber

$emptyWeight = $_POST["emptyWeight"]; //emptyWeight
$loadedWeight = $_POST["loadedWeight"];//loadedWeight
$netWeight = $_POST["netWeight"];//NETWEIGHT
$weighBillNo = $_POST["weighBillNo"]; //WEIGH BILL NUMBER
$location = $_POST["location"]; //LOACATION


$transtableid = $_POST["transtableid"]; //TRANSACTION TABLE ID
$purchaseID = $_POST["purchaseID"]; // PURCHASE TABLE ID


$advance = $_POST["advance"]; //ADVANCE PAYABLE AT PERTICULAR PURCHASE  ENTRY
$advancepaid = $_POST["advancepaid"]; //ADVANCE RECEIVED CAN BE EDITED
$preadvancepaid = $_POST["preadvancepaid"]; //ADVANCE RECEIVED PREVIOUS ON SAME PURCHASE ENTRY

$balpayble = $_POST["balpayble"]; //BALANCE RECEIVABLE AT PERTICULAR PURCHASE ENTRY
$balpaid = $_POST["balpaid"]; //BALANCE RECEIVED EDITABLE
$prevbalpaid = $_POST["prevbalpaid"]; //BALANCE RECEIVED PREVIOUS ON SAME PURCHASE ENTRY

$purchase = array();
$purchase = $_POST["purchase"];
$purchase = array_map('array_filter',$purchase);
$purchase = array_filter($purchase); //FILTERED ARRAY RESULT OF PRODUCTS

print_r($purchase);

$comm_percent = $_POST["comm_percent"]; //COMMISSION PERCENTAGE
$comm_amount = $_POST["comm_amount"]; //COMMISSION AMOUNT

$expenses = array();
$expenses = $_POST["expenses"];
$expenses = array_map('array_filter',$expenses);
$expenses = array_filter($expenses);  // FILTERED EXPENSES LIST

echo "<br>";
print_r($expenses);


$prevDue = $_POST["prevDue"]; //previous due amount

if($transType == 1){
    $netAmount = $_POST["netAmount"];//payable amount
    $totalPaid = $_POST["totalPaid"];//paid amount
    $duepay = $_POST["duepay"];//due payable



}
else if($transType == 2) {
    $netAmount = $_POST["netAmount"];//payable amount
    $totalPaid = 0;//paid amount
    $duepay = $netAmount;//due payable


}

echo "<br>$netAmount,<br> $totalPaid,<br> $duepay";


$paidTo = $_POST["paidTo"]; //PAID TO NAME
$chequeNumber = $_POST["chequeNumber"]; //CHEQUE NUMBER


$balance = $advance-$advancepaid;

//update transaction  table

$updateTransTableQ = "UPDATE hk_transaction_table SET transaction_date = '$ondate', payment = $totalPaid, due=$duepay,balance=$balance WHERE id = $transtableid";

echo "<br> $updateTransTableQ <br>";

mysqli_query($conn,$updateTransTableQ);


//update purchase table

$updatePurchaseQ = "UPDATE hk_purchases SET bill_date = '$ondate', person_id =$supplier_id,purchase_transaction_type_id=$transType,vehicle_number='$vehicleNumber',weighbill_slip_number='$weighBillNo',empty_weight=$emptyWeight, loaded_weight=$loadedWeight , net_weight=$netWeight, amount_payable=$netAmount, amount_paid=$totalPaid, cheque_number='$chequeNumber',location='$location',paid_to='$paidTo' , advance_receivable=$advance,advance_received =$advancepaid, balance_receivable=$balpayble,balance_received=$balpaid WHERE id = $purchaseID";

echo "<br>$updatePurchaseQ";

mysqli_query($conn,$updatePurchaseQ);
//stock product details


$stockProduct = array();

$stockproductQ = "SELECT * FROM hk_purchased_products WHERE purchase_id = $purchaseID";

$stockprouctExe =mysqli_query($conn,$stockproductQ);
$procount = 0;
while($stockproRow = mysqli_fetch_array($stockprouctExe)){
    $stockProduct[$procount]['product_id'] = $stockproRow["product_id"];
    // $stockProduct[$procount]['quantity_type_id'] = $stockproRow["quantity_type_id"];
    $stockProduct[$procount]['final_quantity'] = $stockproRow["final_quantity"];
    $procount++;
}



//update purchase product details

$deleteProducts = "DELETE FROM hk_purchased_products WHERE purchase_id = $purchaseID";

mysqli_query($conn,$deleteProducts);

echo "<br>$deleteProducts";
//mysqli_query($conn,$deleteProducts);

foreach($purchase as $products){
    echo "<br>".$products["'id'"];
    $product_ID = $products["'id'"];
    $quantity = $products["'quantity'"];
    // $quantity_type_id = $products["'qauntitytype'"];
    $shrink = $products["'shrink'"];
    $finalqunatity = $products["'finalqunatity'"];
    $rate = $products["'unitprice'"];
    $amount = $products["'amount'"];

    $updateProductQ = "INSERT INTO `hk_purchased_products` (`purchase_id`, `product_id`, `quantity`, `shrink`, `final_quantity`, `rate`, `amount`) VALUES ( '$purchaseID', '$product_ID', '$quantity', '$shrink', '$finalqunatity', '$rate', '$amount')";
    echo "<br>$updateProductQ";

    //update stock i.e add respective quantities to stock

    $updateStock = "UPDATE `hk_stocks` SET `quantity`=`quantity`+$finalqunatity WHERE `product_id`=$product_ID";

    echo "<br>$updateStock";

    mysqli_query($conn,$updateProductQ);
    mysqli_query($conn,$updateStock);
}


//expenses  table  update

$deleteExpenses = "UPDATE hk_purchase_expenses SET expenses_active=0 WHERE purchase_id = $purchaseID";
echo "<br>$deleteExpenses";
mysqli_query($conn,$deleteExpenses);
//print_r($purchase);
foreach($expenses as $purchaseExpense){
    $expense_id = $purchaseExpense["id"];
    $expense_amount = $purchaseExpense["expense"];
    $updateExpenseQ = "INSERT INTO `hk_purchase_expenses` (`purchase_id`, `expense_type_id`, `amount`) VALUES ('$purchaseID', '$expense_id', '$expense_amount')";
    echo "<br>$updateExpenseQ";
    mysqli_query($conn,$updateExpenseQ);
}


//commission update

$updateCommissionQ = "UPDATE `hk_purchase_commission` SET `commission_percentage`='$comm_percent',`commission_amount`='$comm_amount' WHERE purchase_id='$purchaseID'";

echo "<br> $updateCommissionQ";

mysqli_query($conn,$updateCommissionQ);


//DUE AMOUNT UPDATE i.e PERSON DUE = DUE - PREVDUE+ DUE

$updatedue = "UPDATE `hk_person_due` SET `due_amount`=`due_amount`-$prevDue+$duepay WHERE `person_id`=$supplier_id";

echo "<br>$updatedue";

mysqli_query($conn,$updatedue);

//BALANCE AMOUNT UPDATE

$updateBal = "UPDATE `hk_person_balance` SET `balance_amount` = `balance_amount`+$prevbalpaid-$balpaid WHERE person_id='$supplier_id'";

echo "<br>$updateBal";

mysqli_query($conn,$updateBal);

//ADVANCE AMOUNT UPDATE IN BALANCE

$prevBalance = $advance-$preadvancepaid;//prev balance on purchase entry

if($advance > $advancepaid){
$updateAdvances = "UPDATE `hk_person_balance` SET `balance_amount` = `balance_amount`- $prevBalance + $balance WHERE person_id='$supplier_id'";

echo "<br>$updateAdvances";
    mysqli_query($conn,$updateAdvances);


}
else if($advance == $advancepaid){
    $updateAdvanceseq = "UPDATE `hk_person_balance` SET `balance_amount` = `balance_amount`+0 WHERE person_id='$supplier_id'";

    echo "<br>$updateAdvanceseq";
    mysqli_query($conn,$updateAdvanceseq);
}


//update stock
echo "<br>";
print_r($stockProduct);


//subtract the original quantity from stock and add new entries to stock

foreach($stockProduct as $stockdel){
    $stock_product_id  = $stockdel["product_id"];
    $stock_quantity_id = $stockdel["quantity_type_id"];
    $stock_final_quantity = $stockdel["final_quantity"];
    $stockDelQ = "UPDATE `hk_stocks` SET `quantity`=`quantity`-$stock_final_quantity WHERE `product_id`=$stock_product_id";
    echo "<br>$stockDelQ";

    mysqli_query($conn,$stockDelQ);

}





// get billnumber, transaction type and person name from hk_purchase

$detailsQ = "SELECT HKP.bill_number,HKPT.purchase_transaction_type,HKP.amount_payable,HKP.amount_paid, HKPR.first_name,HKPR.last_name,HKPR.id as person_id FROM hk_purchases as HKP
left JOIN hk_persons as HKPR ON HKPR.id = HKP.person_id
left JOIN hk_purchase_transaction_type AS HKPT ON HKPT.id = HKP.purchase_transaction_type_id
WHERE HKP.id ='$purchaseID'";

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
  $update1 = "UPDATE hk_cash_book SET cr='$duepay',dr='0' WHERE particulars LIKE '%CR P From $person_name, Bill No: $bill_number'";
  if(mysqli_query($conn,$update1)){
    echo "Success update1<br>";
  }else{
    echo "Sorry".mysqli_error($conn);
  }




  // update cashbook entry based on particular entered(2)

  $update2 = "UPDATE hk_cash_book SET cr='0',dr='$duepay' WHERE particulars LIKE '%from $person_name account being the CR P, Bill No: $bill_number'";

  if(mysqli_query($conn,$update2)){
    echo "Success update2<br>";
  }else{
    echo "Sorry".mysqli_error($conn);
  }

// update person Account
  $update3 = "UPDATE hk_account_$person_id SET cr='0',dr='$duepay' WHERE particulars LIKE '%CR P on Bill No: $bill_number'";
  if(mysqli_query($conn,$update3)){
    echo "Sucess in updaing accont <br>";
  }else{
    echo "Failure in Updating account <br>";
  }

// update purchase account table

  $update4 = "UPDATE hk_purchase_account SET `credit/cash`='CREDIT',`amount`='$duepay' WHERE bill_number =$bill_number AND person_id = $person_id";

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
  $cash_update1 = "UPDATE hk_cash_book SET `cr` = '$totalPaid',`dr`='0' WHERE particulars
  LIKE '%Cash P From $person_name Bill No: $bill_number'";

  if(mysqli_query($conn,$cash_update1)){
    echo "Sccess deleting cash book entry";
  }else{
    echo "Sorry".mysqli_error($conn);
  }


// delete from purchase account

  $cash_update2 = "UPDATE hk_purchase_account SET credit/cash = 'CASH', amount ='$totalPaid' WHERE bill_number =$bill_number AND person_id = $person_id";

  if(mysqli_query($conn,$cash_update2)){
    echo "Success deleting from purchase account";
  }else{
    echo "Sorry".mysqli_error($conn);
  }

}else{

// partial cash  purchase

// entries will be in cash_book(3), purchase_account(1), individal account(1)

// deletes cash type entry in cash book
$part_update1 = "UPDATE hk_cash_book SET `cr` = '$totalPaid',`dr`='0' WHERE particulars LIKE '%Cash P From $person_name Bill No: $bill_number'";

if(mysqli_query($conn,$part_update1)){
  echo "Success in deleting cash type entry";
}
else{
  echo "Sorry".mysqli_error($conn);
}

$part_update2 = "UPDATE hk_cash_book SET `cr` = '$duepay',`dr`='0' WHERE particulars LIKE '%CR P From $person_name, Bill No: $bill_number'";

if(mysqli_query($conn,$part_update2)){
  echo "Success in deleting credit type entry in cash book";
}else{
  echo "Sorry".mysqli_error($conn);
}

$part_update3 = "UPDATE hk_cash_book SET `cr` = '0',`dr`='$duepay' WHERE particulars LIKE '%from $person_name account being the CR P, Bill No: $bill_number'";

if(mysqli_query($conn,$part_update3)){
  echo "Success deleting to account";
}else{
  echo "Sorry".mysqli_error($conn);
}


// purchase account delete entry

$part_update4 = "UPDATE hk_purchase_account SET amount='$totalPaid' WHERE bill_number ='$bill_number' AND person_id = '$person_id' AND `credit/cash` = 'CASH'";

if(mysqli_query($conn,$part_update4)){
  echo "Success in deleting purchase account enty";
}else{
  echo "Sorry".mysqli_error($conn);
}

$part_update4_1 = "UPDATE hk_purchase_account SET amount='$duepay' WHERE bill_number ='$bill_number' AND person_id = '$person_id' AND `credit/cash` = 'CREDIT'";

if(mysqli_query($conn,$part_update4_1)){
  echo "Success in deleting purchase account enty";
}else{
  echo "Sorry".mysqli_error($conn);
}



// Delete from particular person account
  $part_update5 = "UPDATE hk_account_$person_id SET `cr`=0,`dr`='$duepay' WHERE particulars LIKE '%CR P on Bill No: $bill_number'";

  if(mysqli_query($conn,$part_update5)){
    echo "Successin deleting data from particular account";
  }else{
    echo "Sorry".mysqli_error($conn);
  }


}



}










header('Location: ../purchase_entry_list.php');

?>
