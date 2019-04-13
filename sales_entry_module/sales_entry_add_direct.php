ex<?php
session_start();
$userid = $_SESSION['id'];
require('../dbconnect.php');

//declaring variables
date_default_timezone_set("Asia/calcutta");
$bill_date = date('Y-m-d H:i:s');
$date1 = date('Y-m-d');
$cust_name = ucwords($_POST["cust_name"]);
$bill_number = $_POST["bill_number"];
$weigh_bill_number=$_POST["weigh_number"];
$vehicle_number =$_POST["vehicle_number"];
$driver_phone = ucwords($_POST["driver_phone"]);
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
//$order_id_fetched=$_POST["order_id_fetched"];
$transType = $_POST["transType"];
$transaction_id2=$_POST["transaction_id2"];
//$stotalreceivable=$_POST["stotalreceivable"];

$ondate = $_POST["ondate"];

// comm_type

$comm_type = $_POST["comm_type"];


// crate details

$crate_count = $_POST["crate_count"]; //number of crates
$crate_amount = $_POST["crate_amount"]; // amount of crates
$crate_rate = $_POST["crate_rate"]; // crate rate





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



// cashbook voucher number

$cash_voucherQ = "SELECT max(voucher_number) as voucher FROM hk_cash_book WHERE date='$ondate'";
$cash_voucherExe = mysqli_query($conn,$cash_voucherQ);
while ($cash_voucherRow = mysqli_fetch_array($cash_voucherExe)) {
  // code...
  $cash_voucher = $cash_voucherRow["voucher"];

}
$cash_voucher++;





//radio button entry
if($transType == 1){
    $transaction_id = $_POST["stotalreceivable"];//payable amount
    $paybleAmount = $transaction_id;
    $totalPaid = $_POST["totalPaid"];//paid amount
    $duepay = $_POST["duepay"];//due payable



}
else if($transType == 2) {

    $transaction_id = $_POST["transaction_id"];//payable amount
    $paybleAmount = $transaction_id;
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




//fetch person name
$supplierQuery = "SELECT first_name, last_name FROM hk_persons WHERE id= $cust_name";
$supplierExe = mysqli_query($conn,$supplierQuery);

if(!$supplierExe){
  echo mysqqli_error($conn)."THis is error msg<br>";
}

while($supplierRow = mysqli_fetch_array($supplierExe)){
    $supplierName = $supplierRow['first_name']." ".$supplierRow['last_name'];
}

//end fetching name

//insert into order table
$insertOrderTable="INSERT INTO `hk_orders`(`person_id`,`user_id`,`date`,`status_type_id`) VALUES ('$cust_name','$userid','$date1','2')";
mysqli_query($conn,$insertOrderTable);
$last_order_id = mysqli_insert_id($conn);




//transaction table entry

$transactionEntryQ = "INSERT INTO `hk_transaction_table` (`transaction_date`, `voucher_no`, `account_head`, `particulars`, `respective_table_id`, `receipts`, `payment`,`due` ,`balance`, `transaction_active`) VALUES ('$date1', '$vouchernumber', 'SALES', 'SALES FROM : ".$supplierName."',NULL, '$totalPaid', NULL,NULL, '$duepay', '1')" ;



mysqli_query($conn,$transactionEntryQ);
$last_id_trans_table = mysqli_insert_id($conn);
//end transaction table entry




//insert to sales table

$query = "INSERT INTO `hk_sales`(`bill_number`,`bill_date`,`vehicle_number`,`order_id`,
`person_id`,`sales_transaction_type_id`,`total_amount`,`total_amount_received`,
`balance_paid`,`driver_phone`,`cheque_number`,`transaction_id`,`sales_balance`,
`transaction_table_id`,`sales_entry_date`,`crate_count`,`crate_unit_price`,`crate_total_amount`)
    VALUES ('$bill_number','$ondate','$vehicle_number','$last_order_id','$cust_name',
  '$transType','$transaction_id','$totalPaid','$balrece','$driver_phone',
  '$check_number','$transaction_id2','$duepay','$last_id_trans_table','$date1',
  '$crate_count','$crate_rate','$crate_amount')";




if(mysqli_query($conn,$query)){
    $last_id = mysqli_insert_id($conn); //fetch last inserted sales id

    
    

}
else{
    echo "sorry".mysqli_error($conn);
}

$Update_trans_table="UPDATE `hk_transaction_table` SET `respective_table_id`='$last_id' WHERE id='$last_id_trans_table'";
mysqli_query($conn,$Update_trans_table);



//print_r($sales);

foreach ($sales as $row) {
   
    $prouct_quantity = $row["'prod_id'"];
    $product_unit_price =$row["'prod_name'"];
    $unit_price_cal = $row["'quantity_entered'"];
    $prouct_id = $row["'qty_type'"];
    // $qty_type_id = $row["'qty_type_id'"];


    //insert into ordered products
    $insert_ordered_product="INSERT INTO `hk_ordered_products`
    (`order_id`,`product_id`,`quantity`)
    VALUES('$last_order_id','$prouct_id','$prouct_quantity')";


    $insertPurchaseProd = "INSERT INTO `hk_sales_products` (`sales_id`, `product_id`, `quantity`,`rate`, `amount`) VALUES ('$last_id', '$prouct_id',
     '$prouct_quantity','$product_unit_price','$unit_price_cal')";



    //update stocks

    $updateStock = "update `hk_stocks` set `quantity`= `quantity`-'$prouct_quantity' where `product_id`='$prouct_id'";


    $stockTracker = "INSERT INTO `hk_stock_tracker`(`product_id`, `date`, `add_stock`, `sub_stock`, `amount`,`particulars`)
     VALUES ('$prouct_id','$ondate','0','$prouct_quantity','$unit_price_cal','SALES')";




        

    if(mysqli_query($conn, $insertPurchaseProd)){
        mysqli_query($conn,$updateStock);
         mysqli_query($conn,$insert_ordered_product);
         mysqli_query($conn,$stockTracker);
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
$comtabentry ="INSERT INTO `hk_sales_commission` (`sales_id`,`commission_percentage`,`commission_amount` ,`paid/received`)
VALUES ('$last_id','$comm_percent','$comm_amount','$comm_type')";

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
    $expesesinsertQ = "INSERT INTO `hk_sales_expenses` (`sales_id`, `expense_type_id`, `amount`) VALUES ('$last_id', '$expenseid', '$expenseAmounnt')";

       // echo "<br> $expesesinsertQ <br>";

    mysqli_query($conn,$expesesinsertQ);

}



//customer balance entry
$custbalentry = "UPDATE `hk_person_balance`
SET balance_amount = (balance_amount -'$balrece')+'$duepay' WHERE
person_id ='$cust_name'";
mysqli_query($conn,$custbalentry);

//echo $custbalentry;

echo mysqli_error($conn);





// order status type fetch
// $queryordertype =" SELECT `id` FROM `hk_orders_status_type` WHERE type='Processing' " ;
// $otype = mysqli_query($conn,$queryordertype);
// while ($ordertype = mysqli_fetch_array($otype)){
// 	$ordesttype = $ordertype["id"];
// }
//
// //order table status update
// $update_Ostatus = " UPDATE `hk_orders` SET `status_type_id`=2 WHERE id='$order_id_fetched'";
// mysqli_query($conn,$update_Ostatus);

//fetch message sending variables
$msg_query = "SELECT `first_name`, `last_name`,`mobile_number` FROM `hk_persons` WHERE id='$cust_name' ";
$msg_queryExe = mysqli_query($conn,$msg_query);
while ($msg_queryRow = mysqli_fetch_array($msg_queryExe)){
	$customer_name = $msg_queryRow["first_name"]." ".$msg_queryRow["last_name"];
	$customer_mobile = $msg_queryRow["mobile_number"];
}

//mesaage plugin

function sales_message ($customer_mobile, $customer_name ,$transaction_id, $duepay, $date1,$driver_phone){


  $username = "ak.enterprise6874@gmail.com";
   $hash = "8f166b2804793ce6abc6a06c1a83ab96b92a37933c571dd7f12637a2c2ec36de";

  // Config variables. Consult http://api.textlocal.in/docs for more info.
  $test = "0";

  // $netamount = $transaction_id+$duepay;

  $sender = "HKHMSG"; // This is who the message appears to be from.
  $numbers = "91".$customer_mobile; // A single number or a comma-seperated list of numbers
  $message = "HI $customer_name, you have been Purchased product of Rs. $transaction_id. with balance of Rs. $duepay on $date1 From HKH. Driver Phonenumber : $driver_phone. With Regards HKH SIRSI.";

  //echo $message;

  $message = urlencode($message);
  $data = "username= ".$username." &hash= ".$hash." &message= ".$message."&sender= ".$sender."&numbers= ".$numbers." &test= ".$test;
  $ch = curl_init('http://api.textlocal.in/send/?');
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch); // This is the result from the API
  curl_close($ch);
  //echo "<br>$result";
}


//there is a problem with the message plugin please look at it

//msg plugin

//echo "<br>";



function cashBal(){
  require("../dbconnect.php");

  $cashBalQ = "SELECT balance FROM hk_cash_book ORDER BY id DESC LIMIT 1";
  $cashBalExe = mysqli_query($conn,$cashBalQ);
  while($cashBalRow = mysqli_fetch_array($cashBalExe)){
    $cashBal = $cashBalRow["balance"];
  }

  if($cashBal == ""){
    $cashBal=0;
  }

return $cashBal;

}

function cashBookEntry($particulars,$date,$cr,$dr,$balane,$cash_voucher){

  require("../dbconnect.php");

  $Query = "INSERT INTO `hk_cash_book`(`particulars`, `date`, `cr`, `dr`, `balance`,`voucher_number`)
   VALUES ('$particulars','$date','$cr','$dr','$balane','$cash_voucher')";

   if(mysqli_query($conn,$Query)){
     return true;
   }
   else{
     return false;
   }

}

function personBalance($personId){

  require("../dbconnect.php");

  $query = "SELECT balance FROM hk_account_".$personId." ORDER by id DESC LIMIT 1";
  $exe = mysqli_query($conn,$query);
  while($row = mysqli_fetch_array($exe)){
    $personBal = $row["balance"];
  }
if($personBal == ""){
  $personBal = 0;
}
return $personBal;

}

// entry to particular person account
function personAccEntry($supplier_id,$date,$particulars,$cr,$dr,$balance){

  require("../dbconnect.php");

  $query = "INSERT INTO `hk_account_".$supplier_id."`(`date`, `particulars`, `cr`, `dr`, `balance`)
   VALUES ('$date','$particulars','$cr','$dr','$balance')";

   if(mysqli_query($conn,$query)){
    return true;
  }else{
    return false;
  }

}

function salesAccEntry($date,$particulars,$invoice,$supplier_id,$credit_cash,$amount){

  require("../dbconnect.php");

  $query = "INSERT INTO `hk_sales_account`(`date`, `particulars`, `bill_number`, `person_id`, `credit/cash`, `amount`)
   VALUES ('$date','$particulars','$invoice','$supplier_id','$credit_cash','$amount')";

   if(mysqli_query($conn,$query)){
    return true;
  }else{
    return false;
  }

}

if($transType == 2){
  // this is credit  sales

$cashBalance = cashBal();

$particulars = "CR S To $supplierName, Bill No: $bill_number";
$cr = 0;
$dr = $duepay;
$balance = $cashBalance+$dr;
cashBookEntry($particulars,$ondate,$cr,$dr,$balance,$cash_voucher);


$cashBalance2 = cashBal();

$particulars2 = "To $supplierName account being the CR S, Bill No: $bill_number";

$cr2 = $duepay;
$dr2 = 0;
$balance2 = $cashBalance2-$cr2;

cashBookEntry($particulars2,$ondate,$cr2,$dr2,$balance2,$cash_voucher);


// get person balance_amount

$personBalance = personBalance($cust_name);

$person_particulars = "CR S on Bill No: $bill_number";

$person_cr= $duepay;

$person_dr = 0;

$person_balance = $personBalance-$person_cr;



// insert to particular person account
personAccEntry($cust_name,$ondate,$person_particulars,$person_cr,$person_dr,$person_balance);



$purchaseAcc_particulars = "CR S To: $supplierName";
// entry to purchase account
salesAccEntry($ondate,$purchaseAcc_particulars,$bill_number,$cust_name,'CREDIT',$duepay);



  // $duepay

}else{

   if($duepay == 0){
    //full cash sales
    $cashBalance_fullCash = cashBal();


    $particulars_fullCash = "Cash S To $supplierName Bill No: $bill_number";

    $cr_fullCash = 0;
    $dr_fullCash = $totalPaid;
    $balance_fullCash = $cashBalance_fullCash + $dr_fullCash;

    cashBookEntry($particulars_fullCash,$ondate,$cr_fullCash,$dr_fullCash,$balance_fullCash,$cash_voucher);

    $salesAcc_particulars_fullCash = "Cash S To : $supplierName";


    salesAccEntry($ondate,$salesAcc_particulars_fullCash,$bill_number,$cust_name,'CASH',$totalPaid);


   }
   else{
    // partial cash sales

    $cashBalance_partCash = cashBal();


  $particulars_partCash = "Cash S To $supplierName Bill No: $bill_number";

  $cr_partCash = 0;
  $dr_partCash = $totalPaid;
  $balance_partCash = $cashBalance_partCash + $dr_partCash;

  cashBookEntry($particulars_partCash,$ondate,$cr_partCash,$dr_partCash,$balance_partCash,$cash_voucher);

  $purchaseAcc_particulars_partCash = "Cash S To : $supplierName";


  salesAccEntry($ondate,$purchaseAcc_particulars_partCash,$bill_number,$cust_name,'CASH',$totalPaid);

  // partial part as credit purchase

$cashBalance_dueCash = cashBal();

$particulars_dueCash = "CR S To $supplierName, Bill No: $bill_number";
$cr_dueCash = 0;
$dr_dueCash = $duepay;
$balance_dueCash = $cashBalance_dueCash+$dr_dueCash;
cashBookEntry($particulars_dueCash,$ondate,$cr_dueCash,$dr_dueCash,$balance_dueCash,$cash_voucher);


$cashBalance_dueCash2 = cashBal();

$particulars_dueCash2 = "To $supplierName account being the CR S, Bill No: $bill_number";

$cr_dueCash2 = $duepay;
$dr_dueCash2 = 0;
$balance_dueCash2 = $cashBalance_dueCash2-$cr_dueCash2;

cashBookEntry($particulars_dueCash2,$ondate,$cr_dueCash2,$dr_dueCash2,$balance_dueCash2,$cash_voucher);


// get person balance_amount

$personBalance_dueCash = personBalance($cust_name);

$person_particulars_dueCash = "CR S on Bill No: $bill_number";

$person_cr_dueCash=$duepay;

$person_dr_dueCash = 0;

$person_balance_dueCash = $personBalance_dueCash-$person_cr_dueCash;



// insert to particular person account
personAccEntry($cust_name,$ondate,$person_particulars_dueCash,$person_cr_dueCash,$person_dr_dueCash,$person_balance_dueCash);



$purchaseAcc_particulars_dueCash = "CR S from: $supplierName";
// entry to purchase account
salesAccEntry($ondate,$purchaseAcc_particulars_dueCash,$bill_number,$cust_name,'CREDIT',$duepay);





   }
}


if($balrece > 0){
  // enter to cash book entry
  // enter to person account
  // debit to cash book
  $recover_CashBal = cashBal();

  $recover_particular_cashbook = "Bal recover with S from :$supplierName, Bill No: $bill_number";
  $cr_recover = $balrece;
  $dr_recover = 0;

  $balance_recover = $recover_CashBal-$cr_recover;


  cashBookEntry($recover_particular_cashbook,$ondate,$cr_recover,$dr_recover,$balance_recover,$cash_voucher);

  // get person balance
  $recover_personBal = personBalance($cust_name);


  // credit to person account
$person_particulars_recover = "Bal Clearance with purchase Bill No: $bill_number";

$person_cr_recover = 0;

$person_dr_recover = $balrece;

$person_balance_recover =  $recover_personBal+$person_dr_recover;

personAccEntry($cust_name,$ondate,$person_particulars_recover,$person_cr_recover,$person_dr_recover,$person_balance_recover);





}


// insert to crate accunt

$crateParticulars = "Crates given on Sales Bill Number: $bill_number";

$crateAccQ = "INSERT INTO `hk_crate_account`(`particulars`, `date`, `number_of_crates`, `given/return`, `amount`)
 VALUES ('$crateParticulars','$ondate','$crate_count','GIVEN','$crate_amount')";

mysqli_query($conn,$crateAccQ);


// update in crate tracker
$crateTrackerQ = "UPDATE `hk_crate_tracker` SET `number_of_crates`=`number_of_crates`+$crate_count,`amount`=`amount`+$crate_amount WHERE `person_id` = $cust_name";

mysqli_query($conn,$crateTrackerQ);


if($_POST["send_msg"] == '1'){
    sales_message ($customer_mobile, $customer_name ,$paybleAmount, $duepay, $ondate,$driver_phone);
}


header("Location: ../sales_entry_list.php");

?>
