<?php


date_default_timezone_set("Asia/calcutta");
$date = date('Y-m-d');

$ondate = $_POST["ondate"];

require('../dbconnect.php');

$sales_id = $_POST["sales_id"];

echo "sale id $sales_id  <br>";

$person_name = $_POST["person_name"];

echo "$person_name <br>";

$person_id = $_POST["person_id"];

$salesbill = $_POST["salesbill"];

$transType = $_POST["transType"];

if($transType == 1){
    $totalPay = $_POST["totalPay"];
    $totalPaid = $_POST["totalPaid"];
    $duepay = $_POST["duepay"];//balance amount

    echo "if its cash payment $totalPay, $totalPaid,$duepay <br>";

}else if($transType == 2){
    $totalPay = $_POST["totalPay"];
    $totalPaid = 0;
    $duepay = $totalPay;
    echo "if its credit payment $totalPay, $totalPaid,$duepay <br>";
}


//voucher no

$selectVochurNumberQ = "select MAX(voucher_no) as voucherno from  hk_transaction_table";
echo $selectVochurNumberQ;

$voucherExe = mysqli_query($conn,$selectVochurNumberQ);

while($voucherRow = mysqli_fetch_array($voucherExe)){
    $voucherNumber = $voucherRow["voucherno"];


}
$voucherNumber +=1;

echo "<br> $voucherNumber<br>";
//transaction table



$transInsertQ = "INSERT INTO `hk_transaction_table`
                (`transaction_date`, `voucher_no`, `account_head`, `particulars`, `respective_table_id`, `receipts`, `payment`, `due`, `balance`)
                 VALUES ('$ondate', '$voucherNumber', 'SALES RETURN', 'PAID TO $person_name', NULL, NULL, $totalPaid, NULL,'$duepay')";





mysqli_query($conn,$transInsertQ);
$last_trans_id = mysqli_insert_id($conn);

echo "<br>$transInsertQ<br>";
echo "<br>$last_trans_id<br>";


$chequeNumber = $_POST["chequeNumber"];
$transaction_id = $_POST["transaction_id"];



//purchase return table insert query

$purchaseReturnQuery = "INSERT INTO `hk_sales_return`
                        (`date`, `sales_id`, `amount_to_be_paid`, `transaction_type_id`, `cheque_number`, `transaction_id`, `sales_return_bill_number`, `amount_paid`, `due`, `transaction_table_id`)
                 VALUES ('$ondate','$sales_id','$totalPay','$transType', NULL, NULL, '$salesbill', '$totalPaid', '$duepay', '$last_trans_id')";


echo "<br> $purchaseReturnQuery <br>";


mysqli_query($conn,$purchaseReturnQuery);
$last_slaes_return_id = mysqli_insert_id($conn);

echo "<br> $last_slaes_return_id<br>";

$updateTrans = "update `hk_transaction_table` set `respective_table_id`= $last_slaes_return_id";

echo $updateTrans;

mysqli_query($conn,$updateTrans);

//purchase return products





$returnproduct = array();
$returnproduct = $_POST["returnproduct"];
$returnproduct = array_map('array_filter',$returnproduct);
$returnproduct = array_filter($returnproduct);

print_r($returnproduct);

foreach($returnproduct as $returnRow){
    echo "<br>".$returnRow['pro_id']."<br>";
    $productid  = $returnRow['pro_id'];
    $quantity = $returnRow['quantity'];
    // $quantityType =  $returnRow['quantity_type'];
    $rate = $returnRow['rate'];
    $amount = $returnRow['amount'];

    //insert purchase return products

    $purchaseReturnProductsQ = "INSERT INTO `hk_sales_return_products` (`sales_return_id`, `product_id`, `quantity`, `rate`, `amount`)
     VALUES ('$last_slaes_return_id', '$productid', '$quantity', '$rate', '$amount')";

    echo "<br>$purchaseReturnProductsQ<br>";

    mysqli_query($conn,$purchaseReturnProductsQ);


    //update stock

    $updateStockQ = "update hk_stocks set `quantity` = `quantity`+$quantity where `product_id`=$productid";


    echo "<br>$updateStockQ<br>";

    mysqli_query($conn,$updateStockQ);



}

//balance tableupdate

$balanceUpdate = "UPDATE `hk_person_due` SET `due_amount` = `due_amount`+'$duepay' WHERE `person_id` = $person_id";

echo "<br>$balanceUpdate <br>";
echo "<br>$balanceUpdate <br>";

mysqli_query($conn,$balanceUpdate);







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

function cashBookEntry($particulars,$ondate,$cr,$dr,$balane){

  require("../dbconnect.php");

  $Query = "INSERT INTO `hk_cash_book`(`particulars`, `date`, `cr`, `dr`, `balance`)
   VALUES ('$particulars','$ondate','$cr','$dr','$balane')";

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
function personAccEntry($supplier_id,$ondate,$particulars,$cr,$dr,$balance){

  require("../dbconnect.php");

  $query = "INSERT INTO `hk_account_".$supplier_id."`(`date`, `particulars`, `cr`, `dr`, `balance`)
   VALUES ('$ondate','$particulars','$cr','$dr','$balance')";

   if(mysqli_query($conn,$query)){
    return true;
  }else{
    return false;
  }

}

// entry to purchase account_head

function salesReturnAccEntry($ondate,$particulars,$invoice,$supplier_id,$credit_cash,$amount){

  require("../dbconnect.php");

  $query = "INSERT INTO `hk_sales_return_account`(`date`, `particulars`, `bill_number`, `person_id(lf)`, `credit/cash`, `amount`)
   VALUES ('$ondate','$particulars','$invoice','$supplier_id','$credit_cash','$amount')";

   if(mysqli_query($conn,$query)){
    return true;
  }else{
    return false;
  }

}




if($transType == 2){
  // credit sales return
  $cashBalance = cashBal();

  $particulars = "Credit Sales Return To $person_name, Bill No: $salesbill";
  $cr = $totalPay;
  $dr = 0;
  $balance = $cashBalance-$cr;
  cashBookEntry($particulars,$ondate,$cr,$dr,$balance);


  $cashBalance2 = cashBal();

  $particulars2 = "To $person_name account being the credit Sales Return, Bill No: $salesbill";

  $cr2 = 0;
  $dr2 = $totalPay;
  $balance2 = $cashBalance2+$dr2;

  cashBookEntry($particulars2,$ondate,$cr2,$dr2,$balance2);


  // get person balance_amount

  $personBalance = personBalance($person_id);

  $person_particulars = "Credit Sales Return on Bill No: $salesbill";

  $person_cr=0;

  $person_dr = $totalPay;

  $person_balance = $personBalance+$person_dr;



  // insert to particular person account
  personAccEntry($person_id,$ondate,$person_particulars,$person_cr,$person_dr,$person_balance);



  $purchaseAcc_particulars = "Credit Sales Return To: $person_name";
  // entry to purchase account
  salesReturnAccEntry($ondate,$purchaseAcc_particulars,$salesbill,$person_id,'CREDIT',$totalPay);






}
else if($transType == 1){
  // cash sales return
if($duepay == 0){
  // full cash sales return
  $cashBalance_fullCash = cashBal();


  $particulars_fullCash = "Cash Sales Return To $person_name Bill No: $salesbill";

  $cr_fullCash = $totalPay;
  $dr_fullCash = 0;
  $balance_fullCash = $cashBalance_fullCash - $cr_fullCash;

  cashBookEntry($particulars_fullCash,$ondate,$cr_fullCash,$dr_fullCash,$balance_fullCash);

  $purchaseAcc_particulars_fullCash = "Cash Sales Return To : $person_name";

salesReturnAccEntry($ondate,$purchaseAcc_particulars_fullCash,$salesbill,$person_id,'CASH',$totalPay);




}else{

// partial cash sales return
$cashBalance_partCash = cashBal();


$particulars_partCash = "Cash Sales Return To $person_name Bill No: $salesbill";

$cr_partCash = $totalPaid;
$dr_partCash = 0;
$balance_partCash = $cashBalance_partCash - $cr_partCash;

cashBookEntry($particulars_partCash,$ondate,$cr_partCash,$dr_partCash,$balance_partCash);

$purchaseAcc_particulars_partCash = "Cash Sales Return To : $person_name";


salesReturnAccEntry($ondate,$purchaseAcc_particulars_partCash,$salesbill,$person_id,'CASH',$totalPaid);

// partial part as credit purchase

$cashBalance_dueCash = cashBal();

$particulars_dueCash = "Credit Sales Return To $person_name, Bill No: $salesbill";
$cr_dueCash = $duepay;
$dr_dueCash = 0;
$balance_dueCash = $cashBalance_dueCash-$cr_dueCash;
cashBookEntry($particulars_dueCash,$ondate,$cr_dueCash,$dr_dueCash,$balance_dueCash);


$cashBalance_dueCash2 = cashBal();

$particulars_dueCash2 = "To $person_name account being the credit Sales Return, Bill No: $salesbill";

$cr_dueCash2 = 0;
$dr_dueCash2 = $duepay;
$balance_dueCash2 = $cashBalance_dueCash2+$dr_dueCash2;

cashBookEntry($particulars_dueCash2,$ondate,$cr_dueCash2,$dr_dueCash2,$balance_dueCash2);


// get person balance_amount

$personBalance_dueCash = personBalance($person_id);

$person_particulars_dueCash = "Credit Sales Return on Bill No: $salesbill";

$person_cr_dueCash=0;

$person_dr_dueCash = $duepay;

$person_balance_dueCash = $personBalance_dueCash+$person_dr_dueCash;



// insert to particular person account
personAccEntry($person_id,$ondate,$person_particulars_dueCash,$person_cr_dueCash,$person_dr_dueCash,$person_balance_dueCash);



$purchaseAcc_particulars_dueCash = "Credit Sales Return To: $person_name";
// entry to purchase account
salesReturnAccEntry($ondate,$purchaseAcc_particulars_dueCash,$salesbill,$person_id,'CREDIT',$duepay);






}


}


















header('Location: ../sales_return_list.php');
?>
