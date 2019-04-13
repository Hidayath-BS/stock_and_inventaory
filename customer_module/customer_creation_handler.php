<?php
session_start();
require('../dbconnect.php');
$firstName = ucwords($_POST["firstName"]);
$lstName = ucwords($_POST["lstName"]);
$custType = $_POST["custType"];
$mobileNumber = $_POST["mobileNumber"];
$altphone = $_POST["altphone"];
$address = ucwords($_POST["address"]);
$address2 = ucwords($_POST["address2"]);
$city = $_POST["city"];
$stateName = $_POST["state"];
$pincode = $_POST["pincode"];
$email = $_POST["email"];
$cred_limit = $_POST["cred_limit"];


// echo "<br>".$name."<br>";
$query = " INSERT INTO `hk_persons`(`person_type_id`,`first_name`,`last_name`, `person_role_type_id`, `address_line_1`, `address_line_2`, `city_id`, `state_id`, `mobile_number`, `landline_number`,`pincode`,`email`,`acc_limit`) VALUES
 (2,'$firstName','$lstName','$custType','$address','$address2','$city','$stateName','$mobileNumber','$altphone','$pincode','$email','$cred_limit')";

if(mysqli_query($conn,$query)){
    echo "success";
    $last_id = mysqli_insert_id($conn);
    $query1 = " INSERT INTO `hk_person_balance` (`person_id`) VALUES ('$last_id')";

    if(mysqli_query($conn,$query1)){
      echo "success";
    }
    else{
        echo "sorry".mysqli_error($conn);
    }
    $query2 = " INSERT INTO `hk_person_due` (`person_id`) VALUES ('$last_id')";

    if(mysqli_query($conn,$query2)){
      echo "success";
    }
    else{
        echo "sorry".mysqli_error($conn);
    }

    $query3 = "CREATE TABLE hk_account_".$last_id." (
id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
date DATE NOT NULL,
particulars VARCHAR(255) NOT NULL,
cr FLOAT NOT NULL,
dr FLOAT NOT NULL,
balance FLOAT NOT NULL,
active TINYINT(1) NOT NULL DEFAULT 1
)";


// insert an entry in crate tracker

$crateTQ = "INSERT INTO `hk_crate_tracker`(`person_id`, `number_of_crates`, `amount`)
 VALUES ('$last_id','0','0')";




 if(mysqli_query($conn,$query3)){
    mysqli_query($conn,$crateTQ);
      echo "table created";
      $_SESSION['message']="Customer has been added successfully";
    }
    else{
        $_SESSION['message']="Sorry!!!".mysqli_error($conn);
        echo "sorry while createing table".mysqli_error($conn);
    }



    header("Location: ../customer_list.php");
}
else{
    echo "sorry".mysqli_error($conn);
}


?>
