<?php
require('../dbconnect.php');

//declaring variables
date_default_timezone_set("Asia/calcutta");
$bill_date = date('Y-m-d H:i:s');
$date1 = date('Y-m-d');
$cust_name = ucwords($_POST["cust_name"]);
$bill_number = $_POST["bill_number"];
$weigh_bill_number=$_POST["weigh_number"];
$vehicle_number =$_POST["vehicle_number"];
$loading_location = ucwords($_POST["loading_location"]);
$sale_quantity = $_POST["sale_quantity"];
$unit_price = $_POST["unit_price"];
$comm_percent = $_POST["comm_percent"];
$comm_amount = $_POST["comm_amount"];
$balrece=$_POST["balrece"];
$expense_amt=$_POST["expense_amt"];
//$transaction_id = $_POST["transaction_id"];
//$totalPaid=$_POST["totalPaid"];
//$duepay = $_POST["duepay"];
$check_number = $_POST["chequeNumber"];
$order_id_fetched=$_POST["order_id_fetched"];
$transType = $_POST["transType"];
$transaction_id2=$_POST["transaction_id2"];
//$stotalreceivable=$_POST["stotalreceivable"];

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
    $transMethod =1;
}


//select voucher number

$getvouchernum = "SELECT MAX(voucher_no) AS voucher FROM hk_transaction_table";

$getvocherExe = mysqli_query($conn,$getvouchernum);
while($getvocherRow = mysqli_fetch_array($getvocherExe)){
    $vouchernumber = $getvocherRow['voucher'];
}

$vouchernumber += 1;

echo $vouchernumber."<br>";


//fetch person name
$supplierQuery = "SELECT first_name, last_name FROM hk_persons WHERE id= $cust_name";
$supplierExe = mysqli_query($conn,$supplierQuery);
while($supplierRow = mysqli_fetch_array($supplierExe)){
    $supplierName = $supplierRow['first_name']." ".$supplierRow['last_name'];
}
echo "<br>".$supplierName."<br>";
//end fetching name

//transaction table entry
$transactionEntryQ = "INSERT INTO `hk_transaction_table` (`transaction_date`, `voucher_no`, `account_head`, `particulars`, `respective_table_id`, `receipts`, `payment`,`due` ,`balance`, `transaction_active`) VALUES ('$date1', '$vouchernumber', 'SALES', 'SALES FROM : ".$supplierName."',NULL, '$totalPaid', NULL,NULL, '$duepay', '1')";

echo $transactionEntryQ."<br>";

mysqli_query($conn,$transactionEntryQ);
$last_id_trans_table = mysqli_insert_id($conn);
//end transaction table entry




//insert to sales table
$query = "INSERT INTO `hk_sales`(`bill_number`,`bill_date`,`vehicle_number`,`order_id`,
`person_id`,`sales_transaction_type_id`,`total_amount`,`total_amount_received`,
`balance_paid`,`loading_location`,`cheque_number`,`transaction_id`,`sales_balance`,
`transaction_table_id`)
    VALUES ('$bill_number','$bill_date','$vehicle_number','$order_id_fetched','$cust_name',
  '$transType','$transaction_id','$totalPaid','$balrece','$loading_location',
  '$check_number','$transaction_id2','$duepay','$last_id_trans_table')";

if(mysqli_query($conn,$query)){
    $last_id = mysqli_insert_id($conn); //fetch last inserted sales id
     header('Location: ../sales_entry_list.php');
    echo "hai" . $last_id;
    echo "Success";

}
else{
    echo "sorry".mysqli_error($conn);
}

$Update_trans_table="UPDATE `hk_transaction_table` SET `respective_table_id`='$last_id' WHERE id='$last_id_trans_table'";
mysqli_query($conn,$Update_trans_table);



print_r($sales);

foreach ($sales as $row) {
    echo "<br>\n".$row["'prod_id'"];
    $prouct_quantity = $row["'prod_id'"];
    $product_unit_price =$row["'prod_name'"];
    $unit_price_cal = $row["'quantity_entered'"];
    $prouct_id = $row["'qty_type'"];
    $qty_type_id = $row["'qty_type_id'"];




    $insertPurchaseProd = "INSERT INTO `hk_sales_products` (`sales_id`, `product_id`, `quantity_type_id`, `quantity`,`rate`, `amount`) VALUES ('$last_id', '$prouct_id',
    '$qty_type_id', '$prouct_quantity','$product_unit_price','$unit_price_cal')";

    //update stocks

    $updateStock = "update `hk_stocks` set `quantity`= `quantity`-'$prouct_id' where `product_id`='$prouct_id' && `quantity_type_id`='$qty_type_id'";




        echo  "<br>$insertPurchaseProd <br>";

    if(mysqli_query($conn, $insertPurchaseProd)){
        mysqli_query($conn,$updateStock);
    }

//    echo $row['firstname'];
//    echo $row['lastname'];
}

// //fetch order quanntity
// $orderqty = "SELECT quantity , product_id FROM `hk_ordered_products` WHERE id='$order_id'";
// $custorderqty = mysqli_query($conn,$orderqty);
// while ($orderqtyrow = mysqli_fetch_array($custorderqty)){
// 	$orderquant = $orderqtyrow["quantity"];
// 	$prod_id = $orderqtyrow["product_id"];
// }


// //stock table update
// $stockquery = " UPDATE `hk_stocks` SET quantity= quantity - '$orderquant' WHERE product_id = '$prod_id' ";
// mysqli_query($conn,$stockquery);



//commission table update
$comtabentry ="INSERT INTO `hk_sales_commission` (`sales_id`,`commission_percentage`,`commission_amount`)
VALUES ('$last_id','$comm_percent','$comm_amount')";
mysqli_query($conn,$comtabentry);


print_r($expenses);

foreach($expenses as $expenseRow){
    echo "<br>".$expenseRow["id"]." ".$expenseRow["expense"];

    $expenseid = $expenseRow["id"];
    $expenseAmounnt = $expenseRow["expense"];

    if(is_null($expenseAmounnt)){
        $expenseAmounnt = 0;
    }

//    echo $expenseAmounnt." as expese amount";

    //add expense into purchase_expense table
    $expesesinsertQ = "INSERT INTO `hk_sales_expenses` (`sales_id`, `expense_type_id`, `amount`) VALUES ('$last_id', '$expenseid', '$expenseAmounnt')";

        echo "<br> $expesesinsertQ <br>";

    mysqli_query($conn, $expesesinsertQ);

}



//customer balance entry
$custbalentry = "UPDATE `hk_person_balance`
SET balance_amount = (balance_amount -'$balrece') + ('$duepay') WHERE
person_id = '$cust_name'";
mysqli_query($conn,$custbalentry);


// //order status type fetch
// $queryordertype =" SELECT `id` FROM `hk_orders_status_type` WHERE
// type='Processing' " ;
// $otype = mysqli_query($conn,$queryordertype);
// while ($ordertype = mysqli_fetch_array($otype)){
// 	$ordesttype = $ordertype["id"];
// }

//order table status update
$update_Ostatus = " UPDATE `hk_orders` SET `status_type_id`=2 WHERE id=
'$order_id_fetched'";
mysqli_query($conn,$update_Ostatus);

?>
