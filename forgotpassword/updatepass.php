<?php
require('../dbconnect.php');
$username = $_POST["username"];
$password = $_POST["pass1"];

$password = sha1($password);

$updateQuery = "UPDATE hk_users SET `password` ='$password' WHERE username = '$username'";

//$updateExe = mysqli_query($conn,$updateQuery);

if(mysqli_query($conn,$updateQuery)){
    header('Location: ../loginn.php');
}

?>