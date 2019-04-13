<?php
require('../dbconnect.php');
$access = $_POST["access"];
$setaccess="UPDATE `hk_admin_access` SET `access`=$access WHERE id=1";
$exe=mysqli_query($conn,$setaccess);
header("Location: ../adminpermission.php");
?>
