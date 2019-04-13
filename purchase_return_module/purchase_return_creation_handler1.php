<?php


date_default_timezone_set("Asia/calcutta");
$date = date('Y-m-d');

require('../dbconnect.php');

$purchaseid = $_POST["purchaseid"];

echo "purchase id $purchaseid <br>";

$person_name = $_POST["person_name"];

echo "$person_name <br>";

$person_id = $_POST["person_id"];

$purbill = $_POST["purbill"];

$transType = $_POST["transType"];


$ondate  = $_POST["ondate"];

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



$transInsertQ = "INSERT INTO `hk_transaction_table` (`transaction_date`, `voucher_no`, `account_head`, `particulars`, `respective_table_id`, `receipts`, `payment`, `due`, `balance`, `transaction_active`) VALUES ('$ondate', '$voucherNumber', 'PURCHASE RETURN', 'RECEIVED FROM $person_name', NULL, '$totalPaid', NULL, NULL, '$duepay', '1')";


mysqli_query($conn,$transInsertQ);
$last_trans_id = mysqli_insert_id($conn);

echo "<br>$transInsertQ<br>";
echo "<br>$last_trans_id<br>";


$chequeNumber = $_POST["chequeNumber"];
$transaction_id = $_POST["transaction_id"];



//purchase return table insert query

$purchaseReturnQuery = "INSERT INTO `hk_purchases_return` (`date`, `purchase_id`, `return_amount`, `transaction_type_id`, `cheque_number`, `transaction_id`, `purchase_return_bill_number`, `purchase_return_active`, `amount_recieved`, `balance`, `transaction_table_id`)
 VALUES ('$ondate', '$purchaseid', '$totalPay', '$transType', NULL, NULL, '$purbill', '1', '$totalPaid', '$duepay', '$last_trans_id')";


echo "<br> $purchaseReturnQuery <br>";


mysqli_query($conn,$purchaseReturnQuery);
$last_purchase_return_id = mysqli_insert_id($conn);

echo "<br> $last_purchase_return_id<br>";




//transaction tbale update


$updateTrans = "update `hk_transaction_table` set `respective_table_id`= $last_purchase_return_id";

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

    $rate = $returnRow['rate'];
    $amount = $returnRow['amount'];

    //insert purchase return products

    $purchaseReturnProductsQ = "INSERT INTO `hk_purchase_return_products` (`purchase_return_id`, `product_id`, `quantity`, `rate`, `amount`) VALUES ('$last_purchase_return_id', '$productid', '$quantity', '$rate', '$amount')";

    echo "<br>$purchaseReturnProductsQ<br>";

    mysqli_query($conn,$purchaseReturnProductsQ);


    //update stock

    $updateStockQ = "update hk_stocks set `quantity` = `quantity`-$quantity where `product_id`=$productid ";


    echo "<br>$updateStockQ<br>";

    mysqli_query($conn,$updateStockQ);



}

//balance tableupdate

$balanceUpdate = "UPDATE `hk_person_balance` SET `balance_amount` = `balance_amount`+'$duepay' WHERE `person_id` = $person_id";

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

function purchaseReturnAccEntry($ondate,$particulars,$invoice,$supplier_id,$credit_cash,$amount){

  require("../dbconnect.php");

  $query = "INSERT INTO `hk_purchase_return_account`(`date`, `particulars`, `bill_number`, `person_id`, `credit/cash`, `amount`)
   VALUES ('$ondate','$particulars','$invoice','$supplier_id','$credit_cash','$amount')";

   if(mysqli_query($conn,$query)){
    return true;
  }else{
    return false;
  }

}




$supplierName = "abc";


if($transType == 2){
  // for credit purchase return

  $cashBalance = cashBal();

  $particulars = "Credit Purchase Return To $person_name, Bill No: $purbill";
  $cr = 0;
  $dr = $totalPay;
  $balance = $cashBalance+$dr;
  cashBookEntry($particulars,$ondate,$cr,$dr,$balance);


  $cashBalance2 = cashBal();

  $particulars2 = "To $person_name account being the Credit Purchase Return, Bill No: $purbill";

  $cr2 = $totalPay;
  $dr2 = 0;
  $balance2 = $cashBalance2-$cr2;

  cashBookEntry($particulars2,$ondate,$cr2,$dr2,$balance2);


  // get person balance_amount

  $personBalance = personBalance($person_id);

  $person_particulars = "Credit Purchase Return on Bill No: $purbill";

  $person_cr= $totalPay;

  $person_dr = 0;

  $person_balance = $personBalance-$person_cr;



  // insert to particular person account
  personAccEntry($person_id,$ondate,$person_particulars,$person_cr,$person_dr,$person_balance);



  $purchaseAcc_particulars = "Credit Purchase Return To: $person_name";
  // entry to purchase account
  purchaseReturnAccEntry($ondate,$purchaseAcc_particulars,$purbill,$person_id,'CREDIT',$totalPay);



    // $duepay


}else if($transType == 1){
  // for cash purchase return
    if($duepay == 0){
      // full cash purchase return

      $cashBalance_fullCash = cashBal();


      $particulars_fullCash = "Cash Purchase Return To $person_name Bill No: $purbill";

      $cr_fullCash = 0;
      $dr_fullCash = $totalPaid;
      $balance_fullCash = $cashBalance_fullCash + $dr_fullCash;

      cashBookEntry($particulars_fullCash,$ondate,$cr_fullCash,$dr_fullCash,$balance_fullCash);

      $purchaseReturnAcc_particulars_fullCash = "Cash Purchase Return To : $person_name";


      purchaseReturnAccEntry($ondate,$purchaseReturnAcc_particulars_fullCash,$purbill,$person_id,'CASH',$totalPaid);




    }else{
      // partial cash return

      $cashBalance_partCash = cashBal();


    $particulars_partCash = "Cash Purchase Return To $person_name Bill No: $purbill";

    $cr_partCash = 0;
    $dr_partCash = $totalPaid;
    $balance_partCash = $cashBalance_partCash + $dr_partCash;

    cashBookEntry($particulars_partCash,$ondate,$cr_partCash,$dr_partCash,$balance_partCash);

    $purchaseAcc_particulars_partCash = "Cash Purchase Returns To : $person_name";


    purchaseReturnAccEntry($ondate,$purchaseAcc_particulars_partCash,$purbill,$person_id,'CASH',$totalPaid);

    // partial part as credit purchase

  $cashBalance_dueCash = cashBal();

  $particulars_dueCash = "Credit Purchase Return To $person_name, Bill No: $purbill";
  $cr_dueCash = 0;
  $dr_dueCash = $duepay;
  $balance_dueCash = $cashBalance_dueCash+$dr_dueCash;
  cashBookEntry($particulars_dueCash,$ondate,$cr_dueCash,$dr_dueCash,$balance_dueCash);


  $cashBalance_dueCash2 = cashBal();

  $particulars_dueCash2 = "To $person_name account being the credit Purchase Return, Bill No: $purbill";

  $cr_dueCash2 = $duepay;
  $dr_dueCash2 = 0;
  $balance_dueCash2 = $cashBalance_dueCash2-$cr_dueCash2;

  cashBookEntry($particulars_dueCash2,$ondate,$cr_dueCash2,$dr_dueCash2,$balance_dueCash2);


  // get person balance_amount

  $personBalance_dueCash = personBalance($person_id);

  $person_particulars_dueCash = "Credit Purchase Return on Bill No: $purbill";

  $person_cr_dueCash=$duepay;

  $person_dr_dueCash = 0;

  $person_balance_dueCash = $personBalance_dueCash-$person_cr_dueCash;



  // insert to particular person account
  personAccEntry($person_id,$ondate,$person_particulars_dueCash,$person_cr_dueCash,$person_dr_dueCash,$person_balance_dueCash);



  $purchaseAcc_particulars_dueCash = "Credit Purchase Return To: $person_name";
  // entry to purchase account
  purchaseReturnAccEntry($ondate,$purchaseAcc_particulars_dueCash,$purbill,$person_id,'CREDIT',$duepay);






    }

}



header('Location: ../purchase_return_list.php');

?>
