<?php
require('../dbconnect.php');

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
$totalAmount = $_POST["totalPay"];//payable amount
$totalPaid = $_POST["totalPaid"];//paid amount
$duepay = $_POST["duepay"];//due paid

//purchase id
$purchase_id = $_POST["purchase_id"];


$chequeNumber = $_POST["chequeNumber"];
$transactionId = $_POST["transactionId"];
$location = strtoupper($_POST["location"]);
$comm_percent = $_POST["comm_percent"];
$comm_amount = $_POST["comm_amount"];
//$advance = $_POST["advance"];// advance payeble
//$advancepaid = $_POST["advancepaid"];//advance paid
$expenses;
$expensetype=1;
//$balpayble = $_POST["balpayble"];//balance payable
//$balpaid = $_POST["balpaid"];//balance paid


//stock fetch data

$stockfetchQ = "SELECT * FROM hk_purchases WHERE id='$purchase_id'";
$stockfetchExe = mysqli_query($conn,$stockfetchQ);
while($stockRow = mysqli_fetch_array($stockfetchExe)){
    $stockQuantity = $stockRow["final_quantity"];
    $cashAmount = $stockRow["amount_paid"];
    $cashID = $stockRow["cash_id"];
    $payable = $stockRow["amount_payable"];
//    $paid= $stockRow["amount_paid"];

}

$due =$payable - $cashAmount;

$updateStock = "update `hk_stocks` SET `quantity`= `quantity`-'$stockQuantity'+'$finalQunatity' where product_id='$product_id'";

//mysqli_query($conn,$updateStock);

//update cash

$updateCash = "update `hk_cash_table` SET amount='$totalPaid' where id = '$cashID'";
mysqli_query($conn,$updateCash);


//update due

$updateDue = "update `hk_supplier_due` SET due_amount = due_amount-'$due'+'$duepay' where supplier_id='$supplier_id'";

mysqli_query($conn,$updateDue);


//update commision

$updateCommisonQ = "update `hk_purchase_commission` SET commission_percentage='$comm_percent', commission_amount='$comm_amount' where purchase_id ='$purchase_id'";

mysqli_query($conn,$updateCommisonQ);

//update Expenses

 foreach ($_POST['expenses'] as $c) {

      $expenses= $c;
$expenseQuery ="update `hk_purchase_expenses` SET amount = '$expenses' where purchase_id = '$purchase_id' && expense_type_id='$expensetype'";
      if(!mysqli_query($conn,$expenseQuery)){
          echo "sorry";
      }
      $expensetype++;
  }


$upateQuery = "UPDATE `hk_purchases` SET `bill_number` = '$billNumber', `supplier_id`='$supplier_id', `product_id` = '$product_id', `purchase_transaction_type_id` = '$transType', `vehicle_number`='$vehicleNumber', `weighbill_slip_number`= '$weighBillNo', `empty_weight`='$emptyWeight', `loaded_weight`= '$loadedWeight', `net_weight`= '$netWeight', `shrink` = '$shrinkWeight', `final_quantity`='$finalQunatity', `unit_price`='$unitPrice', `amount_payable`='$totalAmount', `amount_paid`='$totalPaid', `cheque_number`='$chequeNumber', `transaction_id` = '$transactionId', `location`= '$location' WHERE `hk_purchases`.`id` = '$purchase_id'";



//$selectStock = "select `final_quantity` from `hk_purchases` where `id`='$code'";
//
//$selectExe = mysqli_query($conn,$selectStock);
////$Quantity[];
//while($selectRow = mysqli_fetch_array($selectExe)){
//    $Quantity = $selectRow['final_quantity'];
//}



//$updateStock = "UPDATE `hk_stocks` SET `quantity` = `quantity`+$finalQunatity-$Quantity WHERE `hk_stocks`.`id` ='$product_id'";


if(mysqli_query($conn,$upateQuery)){
    if(mysqli_query($conn,$updateStock)){
        header('Location: ../purchase_entry_list.php');
    }else{
        echo "Failed to update Stock";
    }

}
else{
    echo "Sorry".mysqli_error($conn);
}


?>
