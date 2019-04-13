<?php
require('../dbconnect.php');

//declaring variables

$bill_number = $_POST["bill_number"];
date_default_timezone_set("Asia/calcutta");
$bill_date = date('Y-m-d H:i:s');
$date1 = date('Y-m-d');
$loading_location = ucwords($_POST["loading_location"]);
$sales_transaction_type_id = $_POST["sales_transaction_type_id"];
$cust_name = ucwords($_POST["cust_name"]);
$order_id = $_POST["order"];
$unit_price = $_POST["unit_price"];
$total_amount = $_POST["total_amount"];
$total_amount_received = $_POST["total_amount_received"];
$duepay = $_POST["duepay"];
$check_number = $_POST["check_number"];
$transaction_id = $_POST["transaction_id"];
$comm_percent = $_POST["comm_percent"];
$comm_amount = $_POST["comm_amount"];
$sale_quantity = $_POST["sale_quantity"];
$vehicle_number =$_POST["vehicle_number"];
$expenses;
$expensetype=1;

//purchase id
$saleIid = $_POST["saleIid"];

//echo $duepay."<br/>";



//fect sales details
$salefetchQ = "SELECT * FROM `hk_sales` WHERE id='$saleIid'";
$stockfetchExe = mysqli_query($conn,$salefetchQ);
while($stockRow = mysqli_fetch_array($stockfetchExe)){
    $cashID = $stockRow["cash_id"];
    //echo $balpre_paid = $stockRow["sales_balance"]."<br/>";
}

//cash update
$updateCash = "UPDATE `hk_cash_table` SET amount='$total_amount_received' WHERE id = '$cashID'";
mysqli_query($conn,$updateCash);




//fetch cust_balance details
$balfetchQ = "SELECT * FROM hk_customer_balance WHERE customer_id='$cust_name'";
$balfetchExe = mysqli_query($conn,$balfetchQ);
while($balRow = mysqli_fetch_array($balfetchExe)){
   // echo $balexist = $balRow["balance_amount"]."<br/>";
    
}

$balcal =  $balexist - $balpre_paid;
//echo $balcal."<br/>";

$balcalfinal =  $balcal + $duepay;
//echo $balcalfinal;


//customer balance update
$custbalentry = "UPDATE `hk_customer_balance` SET balance_amount = '$balcalfinal' WHERE customer_id='$cust_name'";
mysqli_query($conn,$custbalentry);

//update commision
$updateCommisonQ = "UPDATE `hk_sales_commission` SET commission_percentage='$comm_percent', commission_amount='$comm_amount' where sales_id ='$saleIid'";
mysqli_query($conn,$updateCommisonQ);




foreach ($_POST['expenses'] as $c) {
    
      $expenses= $c;
$expenseQuery ="UPDATE `hk_sales_expenses` SET sales_expense_amount = '$expenses' WHERE sales_id = '$saleIid' && expense_type_id='$expensetype'";
      if(!mysqli_query($conn,$expenseQuery)){
          echo "sorry";
      }
      $expensetype++;
  }


//insert to sales table
$query = "UPDATE `hk_sales` SET `bill_number`='$bill_number',`vehicle_number`='$vehicle_number',
`sales_transaction_type_id`='$sales_transaction_type_id',`unit_price`='$unit_price',`total_amount`='$total_amount',
  `total_amount_received`='$total_amount_received',`loading_location`='$loading_location',
  `cheque_number`='$check_number',`transaction_id`='$transaction_id',`sales_balance`='$duepay' 
  WHERE id='$saleIid'";

if(mysqli_query($conn,$query)){
	header('Location: ../salesentry_list.php');
  //echo "success";
    
}
else{
    echo "sorry".mysqli_error($conn);
}

?>
