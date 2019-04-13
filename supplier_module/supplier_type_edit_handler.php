<?php
session_start();
require('../dbconnect.php');

$supp_type = ucwords($_POST['supp_type']);
$supp_type_id = $_POST['supp_type_id'];

$query = "UPDATE `hk_person_role_type` SET `person_role_type` = '$supp_type' WHERE `id` =".$supp_type_id;

if(mysqli_query($conn,$query)){
  $_SESSION['message']="Supplier Type has been edited successfully";
    header('Location: ../supplier_type_list.php');
}
else{
  $_SESSION['message']="Sorry!!!".mysqli_error($conn);
    echo "sorry";
}



?>
