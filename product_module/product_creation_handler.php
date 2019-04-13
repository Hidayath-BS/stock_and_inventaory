<?php
session_start();
require('../dbconnect.php');
$name = ucwords($_POST["name"]);
$product_type = ucwords($_POST["product_type"]);
$quantity_type = ucwords($_POST["quantity_type"]);



echo "<br>".$name."<br>";
$query = " INSERT INTO `hk_products`(`name`, `type`,`quantity_type`) VALUES
 ('$name','$product_type','$quantity_type')";

if(mysqli_query($conn,$query)){
    echo "Product update success";
    $last_id = mysqli_insert_id($conn);

    // $query1 = "SELECT id from `hk_quantity_type`" ;
    // $exe = mysqli_query($conn,$query1);
    // while($row = mysqli_fetch_array($exe)){
      // $quantity_type_id = $row["id"];
      $query2 = " INSERT INTO `hk_stocks` (`product_id`) VALUES ('$last_id')";

      if(mysqli_query($conn,$query2)){
          echo "Stocks Update success";
          $_SESSION['message']="Product has been added successfully";
          header("Location: ../product_list.php");
      }
      else{
        $_SESSION['message']="Sorry!!!".mysqli_error($conn);
          echo "sorry".mysqli_error($conn);
      }
    // }

}
else{
    echo "sorry".mysqli_error($conn);
}


?>
