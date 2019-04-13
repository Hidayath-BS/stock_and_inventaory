<?php
require('../dbconnect.php');
$role =ucwords($_POST['role']);

$query = " INSERT INTO `hk_roles` (`role`) VALUES ('$role')";

if(mysqli_query($conn,$query)){
    //echo "success";
    header("Location: ../role_list.php");
}
else{
    echo "sorry".mysqli_error($conn);
}


?>