<?php
require('../dbconnect.php');
 if(isset($_POST['delete']))
                    {
                        $del = $_POST['delete'];
                        $delqery = " UPDATE `hk_persons` SET `person_active`=0 WHERE id=$del";
                        if(mysqli_query($conn,$delqery)){
                          header('Location: ../customer_list.php');
                        }
                        else{
                            echo "sorry";
                        }
                    }

?>
