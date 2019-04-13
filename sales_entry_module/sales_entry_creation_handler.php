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
$total_amount_paid = $_POST["total_amount_paid"];
$duepay = $_POST["duepay"];
$check_number = $_POST["check_number"];
$transaction_id = $_POST["transaction_id"];
$comm_percent = $_POST["comm_percent"];
$comm_amount = $_POST["comm_amount"];
$sale_quantity = $_POST["sale_quantity"];
$balance_paid = $_POST["balpaid"];
$vehicle_number =$_POST["vehicle_number"];
$expenses;
$expensetype=1;


//fetch cust name
$custnamequery = "SELECT first_name FROM `hk_customers` WHERE id='$cust_name'";
$custnameexe = mysqli_query($conn , $custnamequery);
while ($custnamerow = mysqli_fetch_array($custnameexe)){
	$custName = $custnamerow['first_name'];
}


//query for cash table 
$cashQuery = "INSERT INTO  `hk_cash_table` (`date`,`particulars`,`amount`,`transaction_type`) VALUES 
('$date1','Recived from :$custName','$total_amount_paid','INCOME')";
if(mysqli_query($conn,$cashQuery)){
	$last_cash_id = mysqli_insert_id($conn);
}



//fetch order quanntity
$orderqty = "SELECT quantity , product_id FROM `hk_orders` WHERE id='$order_id'";
$custorderqty = mysqli_query($conn,$orderqty);
while ($orderqtyrow = mysqli_fetch_array($custorderqty)){
	$orderquant = $orderqtyrow["quantity"];
	$prod_id = $orderqtyrow["product_id"];
}


//stock table update 
$stockquery = " UPDATE `hk_stocks` SET quantity= quantity - '$orderquant' WHERE product_id = '$prod_id' ";
mysqli_query($conn,$stockquery);

//insert to sales table
$query = "INSERT INTO `hk_sales` (`bill_number`,`bill_date`,`loading_location`,`sales_transaction_type_id`,`customer_id`,`order_id`,`unit_price`,`total_amount`,`total_amount_received`,`cheque_number`,`transaction_id`,
	`cash_id`,`sales_balance`,`sale_quantity`,`balance_paid`,`vehicle_number`)
	VALUES ('$bill_number','$bill_date','$loading_location','$sales_transaction_type_id','$cust_name','$order_id',
	'$unit_price','$total_amount','$total_amount_paid','$check_number','$transaction_id','$last_cash_id','$duepay','$sale_quantity','$balance_paid','$vehicle_number')";

if(mysqli_query($conn,$query)){
	$last_id = mysqli_insert_id($conn); //fetch last inserted sales id
	// header('Location: sales_entry.php');
    // echo "success" . $last_id;
    
}
else{
    echo "sorry".mysqli_error($conn);
}

//commission table update
$comtabentry ="INSERT INTO `hk_sales_commission` (`sales_id`,`commission_percentage`,`commission_amount`) 
VALUES ('$last_id','$comm_percent','$comm_amount')";
mysqli_query($conn,$comtabentry);


//expencess table entry
 if (isset($_POST['expenses'])) {
  foreach ($_POST['expenses'] as $c) {
    
      $expenses= $c;
      $expenseQuery = "insert into `hk_sales_expenses`(`sales_id`,`expense_type_id`,`sales_expense_amount`) values('$last_id','$expensetype','$expenses')";
      if(!mysqli_query($conn,$expenseQuery)){
          echo "sorry";
      }
      $expensetype++;
  }
      header('Location: ../sales_entry_list.php');
}




//customer balance entry
$custbalentry = "UPDATE `hk_customer_balance` 
SET balance_amount = (balance_amount -'$balance_paid') + ('$duepay') WHERE 
customer_id = '$cust_name'";
mysqli_query($conn,$custbalentry);


//order status type fetch
$queryordertype =" SELECT `id` FROM `hk_orders_status_type` WHERE order_status_type='Delivered' " ;
$otype = mysqli_query($conn,$queryordertype);
while ($ordertype = mysqli_fetch_array($otype)){
	$ordesttype = $ordertype["id"];
}

//order table status update
$update_Ostatus = " UPDATE `hk_orders` SET status_type_id='$ordesttype' WHERE id='$order_id'";
mysqli_query($conn,$update_Ostatus);

?>
