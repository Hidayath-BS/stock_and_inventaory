<?php

require('../dbconnect.php');
date_default_timezone_set("Asia/calcutta");
$date = date('Y-m-d H:i:s');
$supplier_id = $_POST["supplier_id"];
$product_id = $_POST["product_id"];
$billNumber = $_POST["billNumber"];
$unitPrice = $_POST["unitPrice"];
$transType = $_POST["transType"];
$vehicleNumber = strtoupper($_POST["vehicleNumber"]);
$weighBillNo = $_POST["weighBillNo"];
$loadedWeight = $_POST["loadedWeight"];
$emptyWeight = $_POST["emptyWeight"];
$netWeight = $_POST["netWeight"];
$shrinkWeight = $_POST["shrinkWeight"];
$finalQunatity = floatval($_POST["finalQunatity"]);


$transMethod = $_POST["transMethod"];


$chequeNumber = $_POST["chequeNumber"];
//$transactionId = $_POST["transactionId"];
$location = strtoupper($_POST["location"]);
$comm_percent = $_POST["comm_percent"];
$comm_amount = $_POST["comm_amount"];
$advance = $_POST["advance"];// advance payeble
$advancepaid = $_POST["advancepaid"];//advance paid
$expenses;
$expensetype=1;
$balpayble = $_POST["balpayble"];//balance payable
$balpaid = $_POST["balpaid"];//balance paid


//supplier name fetch
$suppliername = "select first_name,mobile_number from hk_suppliers where id= $supplier_id";

$supplierExe = mysqli_query($conn,$suppliername);
while($supplierRow = mysqli_fetch_array($supplierExe)){
    $suplierName = $supplierRow["first_name"];
    
}

if($transType == 1){
    $totalAmount = $_POST["totalPay"];//payable amount
    $totalPaid = $_POST["totalPaid"];//paid amount
    $duepay = $_POST["duepay"];//due payable
}
else if($transType == 2) {
    $totalAmount = $_POST["totalPay"];//payable amount
    $totalPaid = 0;//paid amount
    $duepay = $totalAmount;//due payable
    $transMethod =1;
    
}


//cash table entry
$cashtableEntry = "INSERT INTO `hk_cash_table` (`date`, `particulars`, `amount`, `transaction_type`) VALUES ('$date', 'Paid to :$suplierName', '$totalPaid', 'EXPENSES')";

if(mysqli_query($conn,$cashtableEntry)){
    $last_cash_id = mysqli_insert_id($conn);
}




$purchaseQuery = "INSERT INTO `hk_purchases` (`bill_number`, `bill_date`, `supplier_id`, `product_id`, `purchase_transaction_type_id`, `payment_transaction_method` , `vehicle_number`, `weighbill_slip_number`, `empty_weight`, `loaded_weight`, `net_weight`, `shrink`, `final_quantity`, `unit_price`, `amount_payable`, `amount_paid`, `cheque_number`,  `location`,`cash_id`) VALUES ('$billNumber', '$date', '$supplier_id', '$product_id', '$transType','$transMethod', '$vehicleNumber', '$weighBillNo','$emptyWeight', '$loadedWeight',  '$netWeight', '$shrinkWeight', '$finalQunatity', '$unitPrice', '$totalAmount','$totalPaid', '$chequeNumber', '$location','$last_cash_id')";

$updateStock = "update hk_stocks SET quantity = quantity+$finalQunatity WHERE product_id='$product_id'";


// due upate query

$dueUpdator = "update `hk_supplier_due` set due_amount=due_amount+'$duepay' where supplier_id='$supplier_id'";

mysqli_query($conn,$dueUpdator);




//cash tbale  entry



$balance = $advance-$advancepaid;
//check advance status
$advanceupdateState = "update `hk_supplier_advances` SET supplier_advances_active=0 where supplier_id='$supplier_id'";
if($advance > $advancepaid){
    require('../dbconnect.php');
    
    echo $balance."<br>".$supplier_id."<br>";
    
    
    $balanceQuery = "UPDATE  hk_supplier_balance SET balance_amount = balance_amount+'$balance' WHERE supplier_id='$supplier_id'";
    
    
    if(mysqli_query($conn,$balanceQuery)){
        mysqli_query($conn,$advanceupdateState);
    }else{
        mysqli_error($conn);
    }
}


else if($advance==$advancepaid){
    $balance_add = "update `hk_supplier_balance` SET `balance_amount`=balance_amount+0 where supplier_id='$supplier_id'";
    if(mysqli_query($conn,$balance_add)){
        mysqli_query($conn,$advanceupdateState);
    }
}


echo $balance;



if($balpayble>=$balpaid){
    $bal = $balpayble-$balpaid;
    $updateBalance = "update `hk_supplier_balance` SET `balance_amount`=`balance_amount`-'$balpaid' where supplier_id='$supplier_id'";
    mysqli_query($conn,$updateBalance);
}









//due table update


if(mysqli_query($conn,$purchaseQuery)){
    $last_id = mysqli_insert_id($conn);

    
    //select product details

$selProductdetails = "SELECT hkpu.bill_date,hkpu.final_quantity, hkp.name,hkp.type,hkqt.quantity_type, hkpu.amount_paid, hks.first_name, hks.last_name, hkptt.purchase_transaction_type, hks.mobile_number from hk_purchases AS hkpu left JOIN hk_products as hkp ON hkp.id= hkpu.product_id left JOIN hk_quantity_type as hkqt on hkqt.id = hkp.quantity_type_id left JOIN hk_suppliers as hks on hks.id = hkpu.supplier_id left JOIN hk_purchase_transaction_type as hkptt on hkptt.id = hkpu.purchase_transaction_type_id WHERE hkpu.id = '$last_id'";
    
    $selProductExe = mysqli_query($conn,$selProductdetails);
    while($selProductRow = mysqli_fetch_array($selProductExe)){
          $phoneNumbers = $selProductRow["mobile_number"];
          $supplierName = $selProductRow["first_name"].' '.$selProductRow["last_name"];
          $amount = $selProductRow["amount_paid"];
          $purchaseTransType = $selProductRow["purchase_transaction_type"];
          $productName = $selProductRow["name"]." ".$selProductRow["type"];
           $quantity = $selProductRow["final_quantity"];
           $quantityType = $selProductRow["quantity_type"];
        $msgDate = $selProductRow["bill_date"];
        
    }


    
    
    
    //mesaage plugin

$username = "bertinmendonsa29@gmail.com";
	$hash = "63c3d48dfade199d51169386ad0e48187e9a2d766402a88d23441c5530062261";

	// Config variables. Consult http://api.textlocal.in/docs for more info.
	$test = "0";



    $sender = "TXTLCL"; // This is who the message appears to be from.
	$numbers = "91".$phoneNumbers; // A single number or a comma-seperated list of numbers
	$message = "HI '$supplierName', you have been credited with RS.'$amount' on '$purchaseTransType' purchase of '$productName' of '$quantity'.'$quantityType' on '$msgDate'.";

echo $message;

    	$message = urlencode($message);
$data = "username= ".$username." &hash= ".$hash." &message= ".$message."&sender= ".$sender."&numbers= ".$numbers." &test= ".$test;
	$ch = curl_init('http://api.textlocal.in/send/?');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch); // This is the result from the API
	curl_close($ch);
    
    
    //msg plugin
    
    
    require('../dbconnect.php');
    if(mysqli_query($conn,$updateStock)){
 header('Location: ../purchase_entry_list.php');
        $commisionQuery = "insert into `hk_purchase_commission`(`purchase_id`,`commission_percentage`,`commission_amount`) values('$last_id','$comm_percent','$comm_amount')";
        if(mysqli_query($conn,$commisionQuery)){
            if (isset($_POST['expenses'])) {

  foreach ($_POST['expenses'] as $c) {

      $expenses= $c;
      $expenseQuery = "insert into `hk_purchase_expenses`(`purchase_id`,`expense_type_id`,`purchase_expense_amount`) values('$last_id','$expensetype','$expenses')";
      if(!mysqli_query($conn,$expenseQuery)){
          echo "sorry";
      }
      $expensetype++;
  }
                header('Location: ../purchase_entry_list.php');
}


        }
    }
    else{
        echo "Failed to update Stock";
    }

}
else{
    echo mysqli_error($conn);
}





?>
