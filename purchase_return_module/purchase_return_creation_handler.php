<?php
require('../dbconnect.php');
date_default_timezone_set("Asia/calcutta");
$date = date('Y-m-d H:i:s');
$purchaseid = $_POST["purchaseid"];
$puchase_return_bill_num = $_POST["purbill"];
$return_qty = $_POST["returnqty"];
$transaction_type = $_POST["purtransactype"];
$amount_recievable = $_POST["amount_recievable"];
$amount_recieved = $_POST["amount_recieved"];
$cheque_no = strtoupper($_POST["cheque_num"]);
$transaction_id = $_POST["transaction_id"];
$product_id = $_POST["product_id"];
$sname = $_POST["sname"];
$supplier_id = $_POST["supplier_id"];
$balance = $_POST["purbalance"];


echo $sname;
$purchaseReturnQuery = "INSERT INTO `hk_purchases_return`( `date`, `purchase_id`, `purchase_return_bill_number`,
`purchase_return_quantity`, `transaction_type_id`,`return_amount`,`amount_recieved`, `cheque_number`, `transaction_id`)
VALUES ('$date','$purchaseid','$puchase_return_bill_num','$return_qty' ,'$transaction_type',
'$amount_recievable','$amount_recieved','$cheque_no','$transaction_id' )";

      if(mysqli_query($conn,$purchaseReturnQuery)){
         echo "success3";

     }
     else{
         echo "sorry".mysqli_error($conn);
     }

$updateStock = "update `hk_stocks` SET quantity = quantity-$return_qty WHERE product_id='$product_id'";
if(mysqli_query($conn,$updateStock)){
   echo "success1";
   // header("Location: user_list.php");
}
else{
   echo "sorry".mysqli_error($conn);
}
$updateCash = "INSERT INTO `hk_cash_table`( `date`, `particulars`,`amount`, `transaction_type`)
 VALUES ('$date','Recieved From :$sname for Purchase Return','$amount_recieved','INCOME')";
 if(mysqli_query($conn,$updateCash)){
    echo "success2";
    // header("Location: user_list.php");
}
else{
    echo "sorry".mysqli_error($conn);
}
$updateBalance = "UPDATE `hk_supplier_balance` SET balance_amount=balance_amount+$balance WHERE supplier_id='$supplier_id'";
 if(mysqli_query($conn,$updateBalance)){
    echo "success";
   header("Location: ../purchase_return_list.php");
}
else{
    echo "sorry".mysqli_error($conn);
}
?>
