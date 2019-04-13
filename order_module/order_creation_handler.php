<?php

session_start();
$userid = $_SESSION['id'];

require('../dbconnect.php');
date_default_timezone_set("Asia/calcutta");
$date = date('Y-m-d H:i:s');
$customer_id = $_POST["customer_id"];
//$product_id = $_POST["product_id"];
//$user_id = $_POST["user_id"];
//$quantity = $_POST["quantity"];
//$quantity_type=$_POST["quantitytype"];

echo $customer_id;


$query = " INSERT INTO `hk_orders`(`person_id`,`user_id`,`date`,`status_type_id`) VALUES
 ('$customer_id','$userid','$date',1)";
//user id should be taken from login session ID

if(mysqli_query($conn,$query)){
    $last_id=mysqli_insert_id($conn);
//    echo "success";
    header("Location: ../order_list.php");
}
else{
    echo "sorry".mysqli_error($conn);
}

//$ordered_products_query="INSERT INTO `hk_ordered_products`(`order_id`,`product_id`,`quantity`,`quantity_type_id`)
//VALUES('$last_id','$product_id','$quantity','$quantity_type')";
//mysqli_query($conn,$ordered_products_query);




$order = array();
$order = $_POST["order"];
$order = array_map('array_filter',$order);
$order = array_filter($order);
//print_r($order);

foreach ($order as $orderone) {
    $product_id = $orderone["'prod_id'"];
    $quantity = $orderone["'quantity_entered'"];
    // $quantity_type =$orderone["'qty_type_id'"];

    $ordered_products_query="INSERT INTO `hk_ordered_products`(`order_id`,`product_id`,`quantity`)
VALUES('$last_id','$product_id','$quantity')";
mysqli_query($conn,$ordered_products_query);
  }

?>
