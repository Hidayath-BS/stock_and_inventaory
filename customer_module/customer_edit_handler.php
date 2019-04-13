<?php
//require('../dbconnect.php');
//$name = $_POST["name"];
//$cust_type = $_POST["cust_type"];
//$address = $_POST["address"];
//$city = $_POST["city"];
//$state = $_POST["state"];
//$phone = $_POST["phone"];
//$altphone = $_POST["altphone"];
//$id = $_POST["code"];
//
//
//
//$updatequery ="UPDATE `hk_customers` SET name='$name', customer_type_id='$cust_type', address='$address',city='$city',state='$state',phone_number='$phone' WHERE id='$id'";
//
////UPDATE 'hk_customers' SET 'filed_name' = 'value' WHERE id=;
//
//
//
//if(mysqli_query($conn,$updatequery)){
//    header('Location: ../customer_list.php');
//}
//else{
//    echo "sorry".mysqli_error($conn);
//}
//

?>


<?php
session_start();
require('../dbconnect.php');
$firstName = ucwords($_POST["firstName"]);
$lstName = ucwords($_POST["lstName"]);
$custType = $_POST["custType"];
$mobileNumber = $_POST["mobileNumber"];
$altphone = $_POST["altphone"];
$emailid = $_POST["email"];
$address = ucwords($_POST["address"]);
$address2 = ucwords($_POST["address2"]);
$city = $_POST["city"];
$stateName = $_POST["stateName"];
$pincode = $_POST["pincode"];
$id = $_POST["code"];

$limit_amount = $_POST["limit_amount"];

//echo "<br>".$name."<br>";
//$query = " INSERT INTO `hk_customers`(`first_name`,`last_name`, `customer_type_id`, `address_line_1`, `address_line_2`, `city_id`, `state_id`, `mobile_number`, `alternate_number`,`pincode`) VALUES
// ('$firstName','$lstName','$custType','$address','$address2','$city','$stateName','$mobileNumber','$altphone','$pincode')";

$updatequery ="UPDATE `hk_persons` SET first_name='$firstName',`last_name`='$lstName', person_role_type_id='$custType', address_line_1='$address',`address_line_2`='$address2',`city_id`='$city',`state_id`='$stateName',`mobile_number`='$mobileNumber',`landline_number`='$altphone',`email`='$emailid',`pincode`='$pincode' ,`acc_limit`='$limit_amount' WHERE id='$id'";

if(mysqli_query($conn,$updatequery)){
  $_SESSION['message']="Customer has been edited successfully";
    header('Location: ../customer_list.php');
}
else{
  $_SESSION['message']="Sorry!!!".mysqli_error($conn);
    echo "sorry".mysqli_error($conn);
    header('Location: ../customer_list.php');
}



?>
