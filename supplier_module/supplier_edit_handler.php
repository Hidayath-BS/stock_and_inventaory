<?php
session_start();
require('../dbconnect.php');
$id = $_POST["code"];
$supplierName =ucwords($_POST["supplierName"]);
$lastname = ucwords($_POST["lastname"]);
$supplierType = $_POST["supplierType"];
$address1 = ucwords($_POST["address1"]);
$address2 =ucwords($_POST["address2"]);
$supplierCity = $_POST["supplierCity"];
$supplierState = $_POST["supplierState"];
$pincode= $_POST["pincode"];
$supplierPhone = $_POST["supplierPhone"];
$altPhone = $_POST["altPhone"];
$emailid = $_POST["email1"];
$suppAccname = ucwords($_POST["suppAccname"]);
$suppAccountno = $_POST["suppAccountno"];
$suppBranch = ucwords($_POST["suppBranch"]);
$suppIfsc = strtoupper($_POST["suppIfsc"]);
$bankName = ucwords($_POST["bankName"]);

$query = "update `hk_persons` set first_name='$supplierName',last_name='$lastname', person_role_type_id='$supplierType', address_line_1='$address1',address_line_2='$address2', 	city_id='$supplierCity', state_id='$supplierState', mobile_number='$supplierPhone', landline_number='$altPhone', email='$emailid', ac_holders_name='$suppAccname', pincode='$pincode', bank_ac_number='$suppAccountno', branch='$suppBranch', ifsc_code='$suppIfsc',bank_id='$bankName' where id =".$id;

if(mysqli_query($conn,$query)){
  $_SESSION['message']="Supplier has been edited successfully";
    header('Location: ../supplier_list.php');
}else{
  $_SESSION['message']="Sorry!!!".mysqli_error($conn);
echo  mysqli_error($conn);
}

?>
