<?php
session_start();
if($_SESSION['admin_username']==""){
    header("Location: adminlogin.php");
}
else{

  require("dbconnect.php");


  function checkAccess(){
  require("dbconnect.php");
  $query = "SELECT access FROM `hk_admin_access` WHERE id =1";
  $exe = mysqli_query($conn,$query);
  while($row = mysqli_fetch_array($exe)){
    $access = $row["access"];
  }
  return $access;
  }


 ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>HK</title>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
  <link href="css/purchase_return.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="vendor/font-awesome/css/font-awesome.min.css">
<style type="text/css">
  .cust-form{
    padding-top: 20%;
    padding-bottom: 20%;
    margin-top: 50%;
    background: #fff;
    border: 2px solid #000;
    box-shadow: 5px 5px 5px 5px #ccc;
  }

  .btn-circle{
    width: 50px;
    height: 50px;
    border-radius: 50%;
  }

  .text-center{
    text-align: center;
  }
</style>

</head>
<body>
<div class="container">


<div class="row">
    <div class="col-md-4 offset-4 text-center">
  <form class="cust-form" action="php_form_handler/adminpermission_handler.php" method="post">

    <a href="php_form_handler/access_logout.php"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-sign-out fa-2x" aria-hidden="true"></i></button></a>

    <hr>
    <?php
    $access = checkAccess();

    if($access == "1"){
?>
<!-- html code -->
<button type="submit" name="access" value="0" class="btn btn-danger" id="button2" >Restrict Access</button>
  <?php  }else{
?>

<!-- html code -->
<button type="submit" name="access" value="1" class="btn btn-primary" id="button1">Give Access</button>
<?php

    }
     ?>








  </form>
</div>
  </div>
</div>
</body>

</html>
<?php
}

?>
