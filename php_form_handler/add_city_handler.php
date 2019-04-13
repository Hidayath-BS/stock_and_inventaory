<?php
require('../dbconnect.php');
$state_id = $_POST["state_id"];
$city_name = ucwords($_POST["city"]);

$query = " INSERT INTO `hk_cities` (`city_name`,`state_id`) VALUES
 ('$city_name','$state_id')";
//user id should be taken from login session ID

if(mysqli_query($conn,$query)){
    echo "success";
 header("Location: ../add_city.php");
//
}
else{
    echo "sorry".mysqli_error($conn);
}


?>
