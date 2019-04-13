<?php
require('../dbconnect.php');


$del = $_POST["delete"];

//stock fetch & fetch amount from purchase table of id = del
$purchaseDetailsQ = "select * from `hk_purchases` where id ='$del'";
$purchaseDetailsExe = mysqli_query($conn,$purchaseDetailsQ);
while($purchaseRow = mysqli_fetch_array($purchaseDetailsExe)){
    $stock = $purchaseRow["final_quantity"];
    $cash_id = $purchaseRow["cash_id"];
    $product_id = $purchaseRow["product_id"];
}

//update stock

$updateStock = "update `hk_stocks` SET quantity = quantity-'$stock' where product_id='$product_id'";

mysqli_query($conn,$updateStock);


//update cash
$updateCash = "update `hk_cash_table` SET amount=0 where id = '$cash_id'";
mysqli_query($conn,$updateCash);


 if(isset($_POST["delete"]))
                    {
                        
                        $delqery = "UPDATE `hk_purchases` SET `purchases_active`=0 WHERE id='$del'";
                        if(mysqli_query($conn,$delqery)){
                          header('Location: ../purchase_entry_list.php');
                        }
                        else{
                            echo mysqli_error($conn);
                        }
                    }

    





?>