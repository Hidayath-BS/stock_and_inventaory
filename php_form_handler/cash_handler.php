<?php
require('../dbconnect.php');
$particulars = ucwords($_POST["particulars"]);
$amount = $_POST["cashamount"];
date_default_timezone_set("Asia/calcutta");
$date = date('Y-m-d');

if(isset($_POST["expense"])){
  $query = " INSERT INTO `hk_cash_table` (`date`,`particulars`,`amount`,`transaction_type`) VALUES
   ('$date','$particulars','$amount','EXPENSES')";

  if(mysqli_query($conn,$query)){
      echo "success";
      header("Location: ../cashbook_list.php");
  }
  else{
      echo "sorry".mysqli_error($conn);
  }
}
else if(isset($_POST["income"])){
  $query = " INSERT INTO `hk_cash_table` (`date`,`particulars`,`amount`,`transaction_type`) VALUES
   ('$date','$particulars','$amount','INCOME')";

  if(mysqli_query($conn,$query)){
      echo "success";
      header("Location: ../cashbook_list.php");
  }
  else{
      echo "sorry".mysqli_error($conn);
  }
}




?>
