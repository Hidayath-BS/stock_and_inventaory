<?php
require('../dbconnect.php');
$supplierName = $_POST["supplierName"];
$supplierType = $_POST["supplierType"];
$suplierAddress = $_POST["suplierAddress"];
$supplierCity = $_POST["supplierCity"];
$supplierState = $_POST["supplierState"];
$supplierPhone = $_POST["supplierPhone"];
$altPhone = $_POST["altPhone"];
$suppAccname = $_POST["suppAccname"];
$suppAccountno = $_POST["suppAccountno"];
$suppBranch = $_POST["suppBranch"];
$suppIfsc = $_POST["suppIfsc"];

$query = "INSERT INTO hk_suppliers (name , supplier_type_id ,address ,city ,state ,phone_number, alternate_number ,bank_ac_number ,ac_holders_name ,branch ,ifsc_code) VALUES('$supplierName',$supplierType,'$suplierAddress','$supplierCity','$supplierState','$supplierPhone' ,'$altPhone','$suppAccountno','$suppAccname','$suppBranch','$suppIfsc')";

if(mysqli_query($conn,$query)){
    header('Location: ../supplier_list.php');
}

?>