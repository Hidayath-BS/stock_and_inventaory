<?php
session_start();

require('../dbconnect.php');

$name = $_POST["name"];
$quantity = $_POST["quantity"];
$date=date("Y-m-d");
$paticulars=$_POST["particulars"];
// echo "$name, $type, $quantity";

$stockUpdateQuery = "UPDATE hk_stocks SET quantity = quantity+$quantity WHERE product_id =$name";

echo "<br>$stockUpdateQuery";

if(mysqli_query($conn,$stockUpdateQuery)){
  $addStockQuery="INSERT INTO `hk_stock_tracker`(`product_id`, `date`, `add_stock`, `sub_stock`, `amount`,`particulars`)
  VALUES ('$name','$date','$quantity','0','0','$paticulars')";
if( mysqli_query($conn,$addStockQuery)){
  $_SESSION['message']="Stock has been added successfully";
}
else{
    $_SESSION['message']="Sorry!!!".mysqli_error($conn);

}
    header('Location: ../add_stock.php');
}else{
    echo mysqli_error($conn);
}

?>
