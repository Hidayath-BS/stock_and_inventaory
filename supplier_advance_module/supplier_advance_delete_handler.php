<?php
require('../dbconnect.php');
 if(isset($_POST['delete']))
                    {
                        $del = $_POST['delete'];
                        $getTransactionTableId = "SELECT 	transaction_table_id FROM `hk_supplier_advances` WHERE id = '$del'";
                        $exe = mysqli_query($conn,$getTransactionTableId);
                        $row = mysqli_fetch_array($exe);
                        $transaction_table_id = $row['transaction_table_id'];

                        $delqery = " UPDATE `hk_supplier_advances` SET `supplier_advances_active`=0 WHERE id=$del";
                        if(mysqli_query($conn,$delqery)){

                          // header('Location: ../supplier_advance_list.php');
                        	echo "success";
                          $delqery = " UPDATE `hk_transaction_table` SET `transaction_active`=0 WHERE id=$transaction_table_id";
                          if(mysqli_query($conn,$delqery)){

                            header('Location: ../supplier_advance_list.php');
                          	echo "success";
                          }
                          else{
                              echo "sorry";
                          }
                        }
                        else{
                            echo "sorry";
                        }
                    }

?>
