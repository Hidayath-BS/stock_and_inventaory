<?php
require('../dbconnect.php');
$role = ucwords($_POST["role"]);
$id = $_POST["code"];


$updatequery ="UPDATE `hk_roles` SET role='$role' WHERE id='$id'";

//UPDATE 'hk_customers' SET 'filed_name' = 'value' WHERE id=;



if(mysqli_query($conn,$updatequery)){
    header('Location: ../role_list.php');
}
else{
    echo "sorry".mysqli_error($conn);
}


?>