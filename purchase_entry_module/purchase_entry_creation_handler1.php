<?php error_reporting(E_ALL ^ E_NOTICE); ?>
<?php



require('../dbconnect.php');


date_default_timezone_set("Asia/calcutta");
$date = date('Y-m-d');



//select voucher number

$getvouchernum = "SELECT MAX(voucher_no) AS voucher FROM hk_transaction_table";

$getvocherExe = mysqli_query($conn,$getvouchernum);
while($getvocherRow = mysqli_fetch_array($getvocherExe)){
    $vouchernumber = $getvocherRow['voucher'];
}

$vouchernumber += 1;



$supplier_id = $_POST["supplier_id"];
$vehicleNumber = strtoupper($_POST["vehicleNumber"]) ;
$emptyWeight = $_POST["emptyWeight"];
$loadedWeight = $_POST["loadedWeight"];
$billNumber = strtoupper($_POST["billNumber"]);
$weighBillNo = strtoupper($_POST["weighBillNo"]);
$netWeight = $_POST["netWeight"];
$location = strtoupper($_POST["location"]);

$crate_count = $_POST["crate_count"];
$crate_rate = $_POST["crate_rate"];
$crate_amount = $_POST["crate_amount"];

$ondate = $_POST["ondate"];
$paidTo = strtoupper($_POST["paidTo"]);

$chequeNumber = $_POST["chequeNumber"];


$comm_type = $_POST["comm_type"];


$comm_percent = $_POST["comm_percent"];
$comm_amount =  $_POST["comm_amount"];
$advance = $_POST["advance"];
$advancepaid = $_POST["advancepaid"];
$balpayble = $_POST["balpayble"];
$balpaid = $_POST["balpaid"];
$transType = $_POST["transType"];

$totalPay = $_POST["totalPay"];// payable amount
//$netAmount = $_POST["netAmount"];// NET AMOUNT

//$duepay = $_POST["duepay"];//CASH PURCHASE


$totalPaid = $_POST["totalPaid"];

$expenses = array();
$expenses = $_POST["expenses"];// expenses array
$expenses = array_map('array_filter',$expenses);
$expenses = array_filter($expenses);


//product table data
$purchase = array();
$purchase = $_POST["purchase"];
$purchase = array_map('array_filter',$purchase);
$purchase = array_filter($purchase);



//select name from person table


// cashbook voucher number

$cash_voucherQ = "SELECT max(voucher_number) as voucher FROM hk_cash_book WHERE date='$ondate'";
$cash_voucherExe = mysqli_query($conn,$cash_voucherQ);
while ($cash_voucherRow = mysqli_fetch_array($cash_voucherExe)) {
  // code...
  $cash_voucher = $cash_voucherRow["voucher"];

}
$cash_voucher++;






$supplierQuery = "SELECT first_name, last_name,mobile_number FROM hk_persons WHERE id= $supplier_id";
$supplierExe = mysqli_query($conn,$supplierQuery);
while($supplierRow = mysqli_fetch_array($supplierExe)){
    $supplierName = $supplierRow['first_name']." ".$supplierRow['last_name'];
    $phoneNumbers = $supplierRow['mobile_number'];
}









if($transType == 1){
    $netAmount = $_POST["netAmount"];//payable amount
    $totalPaid = $_POST["totalPaid"];//paid amount
    $duepay = $_POST["duepay"];//due payable



}
else if($transType == 2) {
    $netAmount = $_POST["netAmount"];//payable amount
    $totalPaid = 0;//paid amount
    $duepay = $netAmount;//due payable
    $transMethod =1;

}



$balance = $advance-$advancepaid;
//transaction table

$transactionEntryQ = "INSERT INTO `hk_transaction_table` (`transaction_date`, `voucher_no`, `account_head`, `particulars`, `respective_table_id`, `receipts`, `payment`,`due` ,`balance`, `transaction_active`) VALUES ('$ondate', '$vouchernumber', 'PURCHASE', 'PAID TO ".$supplierName."', NULL, NULL, '$totalPaid', '$duepay', '$balance', '1')";


//due table update


$duetableUpdateQ = "update `hk_person_due` set due_amount = due_amount+ '$duepay' where `person_id`='$supplier_id'";



mysqli_query($conn,$duetableUpdateQ);




//check advance status
$advanceupdateState = "update `hk_supplier_advances` SET supplier_advances_active=0 where person_id='$supplier_id'";
if($advance > $advancepaid){
    require('../dbconnect.php');

    


    $balanceQuery = "UPDATE  hk_person_balance SET balance_amount = balance_amount+'$balance' WHERE person_id='$supplier_id'";




    if(mysqli_query($conn,$balanceQuery)){
        mysqli_query($conn,$advanceupdateState);
    }else{
        mysqli_error($conn);
    }
}


else if($advance==$advancepaid){
    $balance_add = "update `hk_person_balance` SET `balance_amount`=balance_amount+0 where person_id='$supplier_id'";
    
    if(mysqli_query($conn,$balance_add)){
        mysqli_query($conn,$advanceupdateState);
    }
}






if($balpayble>=$balpaid){
    $bal = $balpayble-$balpaid;
    $updateBalance = "update `hk_person_balance` SET `balance_amount`=`balance_amount`-'$balpaid' where person_id='$supplier_id'";
    mysqli_query($conn,$updateBalance);
}



echo $transactionEntryQ."<br>";


mysqli_query($conn,$transactionEntryQ);
$last_id = mysqli_insert_id($conn);


//purchase table



$purchaseEntryQ = "INSERT INTO `hk_purchases` (`bill_number`, `bill_date`, `person_id`, `purchase_transaction_type_id`, `vehicle_number`, `weighbill_slip_number`, `empty_weight`, `loaded_weight`, `net_weight`, `amount_payable`, `amount_paid`, `cheque_number`, `transaction_id`, `location`, `purchases_active`, `transaction_table_id`, `entry_date`,`paid_to`,`advance_receivable`,`advance_received`,`balance_receivable`,`balance_received`,`crate_count`, `crate_unit_price`, `crate_total_amount`)
 VALUES ('$billNumber', '$ondate', '$supplier_id', '$transType', '$vehicleNumber', '$weighBillNo', '$emptyWeight', '$loadedWeight', '$netWeight', '$netAmount', '$totalPaid', '$chequeNumber', NULL, '$location', '1', '$last_id', '$date','$paidTo','$advance','$advancepaid','$balpayble','$balpaid','$crate_count','$crate_rate','$crate_amount')";



if(mysqli_query($conn,$purchaseEntryQ)){
    $last_purchaseId = mysqli_insert_id($conn);
}
else{
  echo "<br>".mysqli_error($conn)."<br>";
}



//update transaction table

$updateTransTable = "update `hk_transaction_table` set `respective_table_id` = '$last_purchaseId' where  id = '$last_id'";



mysqli_query($conn,$updateTransTable);




foreach ($purchase as $row) {
    echo "<br>\n".$row["'id'"];
    $prouct_id = $row["'id'"];
    // $quantity_type =$row["'qauntitytype'"];
    $quantity = $row["'quantity'"];
    $shrink = $row["'shrink'"];

    $finalqunatity = $row["'finalqunatity'"];
    $unitprice = $row["'unitprice'"];
    $amount = $row["'amount'"];

    if(is_null($shrink)){
        $shrink = 0;
    }


    $insertPurchaseProd = "INSERT INTO `hk_purchased_products` (`purchase_id`, `product_id`, `quantity`, `shrink`, `final_quantity`, `rate`, `amount`)
     VALUES ('$last_purchaseId', '$prouct_id', '$quantity', '$shrink', '$finalqunatity', '$unitprice', '$amount')";

    //update stocks

    $updateStock = "update `hk_stocks` set `quantity`= `quantity`+ '$finalqunatity' where `product_id`='$prouct_id'";

    // insert to stock register tracker

    $stockTracker = "INSERT INTO `hk_stock_tracker`(`product_id`, `date`, `add_stock`, `sub_stock`, `amount`,`particulars`)
     VALUES ('$prouct_id','$ondate','$finalqunatity','0','$amount','PURCHASE')";





        

    if(mysqli_query($conn, $insertPurchaseProd)){
        if(mysqli_query($conn,$updateStock)){
            if(mysqli_query($conn,$stockTracker)){
                echo "success";
            }else{
              echo mysqli_error($conn);
            }

        }else{
          echo mysqli_query($conn);
        }


    }else{
      echo mysqli_error($conn);
    }

//    echo $row['firstname'];
//    echo $row['lastname'];
}


foreach($expenses as $expenseRow){
    echo "<br>".$expenseRow["id"]." ".$expenseRow["expense"];

    $expenseid = $expenseRow["id"];
    $expenseAmounnt = $expenseRow["expense"];

    if(is_null($expenseAmounnt)){
        $expenseAmounnt = 0;
    }

//    echo $expenseAmounnt." as expese amount";

    //add expense into purchase_expense table
    $expesesinsertQ = "INSERT INTO `hk_purchase_expenses` (`purchase_id`, `expense_type_id`, `amount`) VALUES ('$last_purchaseId', '$expenseid', '$expenseAmounnt')";

        

    mysqli_query($conn, $expesesinsertQ);

}



//commision query

$commisonQuery = "INSERT INTO `hk_purchase_commission` (`purchase_id`, `commission_percentage`, `commission_amount`,`paid/received`) VALUES ('$last_purchaseId', '$comm_percent', '$comm_amount','$comm_type')";



mysqli_query($conn,$commisonQuery);
















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

echo "cash balance=".$cashBal;
return $cashBal;

}

function cashBookEntry($particulars,$date,$cr,$dr,$balane,$cash_voucher){

  require("../dbconnect.php");

  $Query = "INSERT INTO `hk_cash_book`(`particulars`, `date`, `cr`, `dr`, `balance`,`voucher_number`)
   VALUES ('$particulars','$date','$cr','$dr','$balane','$cash_voucher')";

   if(mysqli_query($conn,$Query)){
echo "success cash book entry";

     return true;
   }
   else{
echo "failure cash book entry";

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

echo "person Balance=".$personBal;
return $personBal;

}

// entry to particular person account
function personAccEntry($supplier_id,$date,$particulars,$cr,$dr,$balance){

  require("../dbconnect.php");

  $query = "INSERT INTO `hk_account_".$supplier_id."`(`date`, `particulars`, `cr`, `dr`, `balance`)
   VALUES ('$date','$particulars','$cr','$dr','$balance')";

   if(mysqli_query($conn,$query)){
echo "success person acc entry";
    return true;
  }else{
echo "person acc failure failure";
    return false;
  }

}

// entry to purchase account_head

function purchaseAccEntry($date,$particulars,$invoice,$supplier_id,$credit_cash,$amount){

  require("../dbconnect.php");

  $query = "INSERT INTO `hk_purchase_account`(`date`, `particulars`, `bill_number`, `person_id`, `credit/cash`, `amount`)
   VALUES ('$date','$particulars','$invoice','$supplier_id','$credit_cash','$amount')";

   if(mysqli_query($conn,$query)){
	echo "purchase acc entry success";
    return true;
  }else{
		echo "purchase acc entry FAILURE";
    return false;
  }

}





if($transType == 2){
  // this is for credit purchase

  // get balance FROM cash table
  $cashBalance = cashBal();

$particulars = "CR P From $supplierName, Bill No: $billNumber";
$cr = $totalPay;
$dr = 0;
$balance = $cashBalance-$cr;
cashBookEntry($particulars,$ondate,$cr,$dr,$balance,$cash_voucher);


$cashBalance2 = cashBal();

$particulars2 = "from $supplierName account being the CR P, Bill No: $billNumber";

$cr2 = 0;
$dr2 = $totalPay;
$balance2 = $cashBalance2+$dr2;

cashBookEntry($particulars2,$ondate,$cr2,$dr2,$balance2,$cash_voucher);


// get person balance_amount

$personBalance = personBalance($supplier_id);

$person_particulars = "CR P on Bill No: $billNumber";

$person_cr=0;

$person_dr = $totalPay;

$person_balance = $personBalance+$person_dr;



// insert to particular person account
personAccEntry($supplier_id,$ondate,$person_particulars,$person_cr,$person_dr,$person_balance);



$purchaseAcc_particulars = "CR P from: $supplierName";
// entry to purchase account
purchaseAccEntry($ondate,$purchaseAcc_particulars,$billNumber,$supplier_id,'CREDIT',$totalPay);




}else{
  // this is for cash purchase

if($duepay == 0){
  // full cash cash Purchase
  $cashBalance_fullCash = cashBal();


$particulars_fullCash = "Cash P From $supplierName Bill No: $billNumber";

$cr_fullCash = $totalPay;
$dr_fullCash = 0;
$balance_fullCash = $cashBalance_fullCash - $cr_fullCash;

  cashBookEntry($particulars_fullCash,$ondate,$cr_fullCash,$dr_fullCash,$balance_fullCash,$cash_voucher);

$purchaseAcc_particulars_fullCash = "Cash P from : $supplierName";


purchaseAccEntry($ondate,$purchaseAcc_particulars_fullCash,$billNumber,$supplier_id,'CASH',$totalPay);


}else{
  // partial Cash Purchase

  // payment received as cash purchase
  $cashBalance_partCash = cashBal();


  $particulars_partCash = "Cash P From $supplierName Bill No: $billNumber";

  $cr_partCash = $totalPaid;
  $dr_partCash = 0;
  $balance_partCash = $cashBalance_partCash - $cr_partCash;

  cashBookEntry($particulars_partCash,$ondate,$cr_partCash,$dr_partCash,$balance_partCash,$cash_voucher);

  $purchaseAcc_particulars_partCash = "Cash P from : $supplierName";


  purchaseAccEntry($ondate,$purchaseAcc_particulars_partCash,$billNumber,$supplier_id,'CASH',$totalPaid);

  // partial part as credit purchase

$cashBalance_dueCash = cashBal();

$particulars_dueCash = "CR P From $supplierName, Bill No: $billNumber";
$cr_dueCash = $duepay;
$dr_dueCash = 0;
$balance_dueCash = $cashBalance_dueCash-$cr_dueCash;
cashBookEntry($particulars_dueCash,$ondate,$cr_dueCash,$dr_dueCash,$balance_dueCash,$cash_voucher);


$cashBalance_dueCash2 = cashBal();

$particulars_dueCash2 = "from $supplierName account being the CR P, Bill No: $billNumber";

$cr_dueCash2 = 0;
$dr_dueCash2 = $duepay;
$balance_dueCash2 = $cashBalance_dueCash2+$dr_dueCash2;

cashBookEntry($particulars_dueCash2,$ondate,$cr_dueCash2,$dr_dueCash2,$balance_dueCash2,$cash_voucher);


// get person balance_amount

$personBalance_dueCash = personBalance($supplier_id);

$person_particulars_dueCash = "CR P on Bill No: $billNumber";

$person_cr_dueCash=0;

$person_dr_dueCash = $duepay;

$person_balance_dueCash = $personBalance_dueCash+$person_dr_dueCash;



// insert to particular person account
personAccEntry($supplier_id,$ondate,$person_particulars_dueCash,$person_cr_dueCash,$person_dr_dueCash,$person_balance_dueCash);



$purchaseAcc_particulars_dueCash = "CR P from: $supplierName";
// entry to purchase account
purchaseAccEntry($ondate,$purchaseAcc_particulars_dueCash,$billNumber,$supplier_id,'CREDIT',$duepay);




}

  // we need to check condition for full payment and partial payment




}



if($balpaid>0){
  // so supplier is clearing the balance hear

  // enter to cash table and person account

  // debit to cash book
  $recover_CashBal = cashBal();

  $recover_particular_cashbook = "Bal recover with P from :$supplierName, Bill No: $billNumber";

  $cr_recover = 0;
  $dr_recover = $balpaid;

  $balance_recover = $recover_CashBal+$dr_recover;


  cashBookEntry($recover_particular_cashbook,$ondate,$cr_recover,$dr_recover,$balance_recover,$cash_voucher);

  // get person balance
  $recover_personBal = personBalance($supplier_id);


  // credit to person account
$person_particulars_recover = "Balance Clearance with purchase Bill No: $billNumber";

$person_cr_recover = $balpaid;

$person_dr_recover = 0;

$person_balance_recover =  $recover_personBal-$person_cr_recover;

personAccEntry($supplier_id,$ondate,$person_particulars_recover,$person_cr_recover,$person_dr_recover,$person_balance_recover);


  // each acount has to be

}
//mesaage plugin

if($_POST["send_msg"]=='1'){

  $username = "ak.enterprise6874@gmail.com";
   $hash = "8f166b2804793ce6abc6a06c1a83ab96b92a37933c571dd7f12637a2c2ec36de";

  	// Config variables. Consult http://api.textlocal.in/docs for more info.
  	$test = "0";



      $sender = "HKHMSG"; // This is who the message appears to be from.
  	$numbers = "91".$phoneNumbers; // A single number or a comma-seperated list of numbers
  	$message = "HI $supplierName, we have purchased Product of RS. $netAmount with balance of Rs. $duepay on $ondate From you with Regards HKH SIRSI.";
  echo $message;


      	$message = urlencode($message);
  $data = "username= ".$username." &hash= ".$hash." &message= ".$message."&sender= ".$sender."&numbers= ".$numbers." &test= ".$test;
  	$ch = curl_init('http://api.textlocal.in/send/?');
  	curl_setopt($ch, CURLOPT_POST, true);
  	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  	$result = curl_exec($ch); // This is the result from the API
  	curl_close($ch);

echo "<br>$result";
      //msg plugin

}

// crate account entry

$crate_account_particulars="Crate given on purchase bill number: $billNumber";



$crate_account_qry = "INSERT INTO `hk_crate_account`(`particulars`, `date`, `number_of_crates`, `given/return`, `amount`) VALUES ('$crate_account_particulars','$date','$crate_count','GIVEN','$crate_amount')";

mysqli_query($conn,$crate_account_qry);

// check if entry exists

$checkQ = "SELECT id from hk_crate_tracker WHERE person_id = $supplier_id";

$checkExe = mysqli_query($conn,$checkQ);

while($checkRow = mysqli_fetch_array($checkExe)){
  $checkId = $checkRow["id"];
}

if($checkId == ""){
  // add entry
  $crate_tracker = "INSERT INTO `hk_crate_tracker`(`person_id`, `number_of_crates`, `amount`) VALUES ('$supplier_id','$crate_count','$crate_amount')";
  mysqli_query($conn,$crate_tracker);
}else{
  // update query
  $crate_tracker = "UPDATE `hk_crate_tracker` SET `number_of_crates`=number_of_crates+$crate_count,`amount`=amount+$crate_amount WHERE `person_id` = $supplier_id";
  // echo "$crate_tracker";
  mysqli_query($conn,$crate_tracker);
}

header("Location: ../purchase_entry_list.php");



?>
