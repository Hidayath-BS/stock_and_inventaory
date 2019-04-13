<?php
session_start();
require("logout.php");
if($_SESSION['username']==""){
    header("Location: loginn.php");
}
else{
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
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
<!--    <link href="css/supplier.css" rel="stylesheet">-->
    <link href="css/product.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
    <?php
    require('header.php');
    require('dbconnect.php');
 ?>
  <!-- Navigation-->
    <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->

        <form class="cust_line" method="post" action="stock_module/add_stock_handler.php">

             <div class="row"><h5 style="margin: -18px 0px 8px 0px"><u>Add Stock</u></h5>
    <pre style="float:right">       								 (Note: Fields with <i class="fa fa-asterisk" style="font-size:13px;color:red"></i> mark are compulsory)</pre></div>
    <div class="row">

        <div class="col-md-6">
       <label for="name">Product Name <span class="requiredfield">*</span></label>
<!--   <input type="text" id="name" name="name" placeholder="Product Name.." required>-->
            <select id="name" class="prname" name="name">
                <option>--- select  product ---</option>
                <?php
                $selectProductQ = "SELECT * FROM `hk_products`";
                $ProductExe = mysqli_query($conn,$selectProductQ);
                while($ProductRow = mysqli_fetch_array($ProductExe)){
                ?>

                <option value="<?php echo $ProductRow["id"]; ?>"><?php echo $ProductRow["name"]." ".$ProductRow["type"]." ".$ProductRow["quantity_type"];  ?></option>

                <?php } ?>
            </select>

        </div>
        <div class="col-md-6">
            <label>Item Quantity<span class="requiredfield">*</span></label>
            <input type="number" name="quantity" required>
        </div>
        <div class="col-md-6">
        <label>Particulars<span class="requiredfield">*</span></label>
        <input type="text" name="particulars">
      </div>
    </div>

            <div class="row" style="margin-left: -155px;">
  <!--
  <input type="submit"  value="Submit">
  <input type="reset" value="Cancel">
-->
<button class="buttonsubmit" type="submit"><a >Submit</a></button>
   <a href="stock_list.php" style=" text-decoration: none;" class="buttonreset">  <span >Cancel</span></a>


</div>
  </form>


        <!-- end of customer deatils-->
  </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>MAHAT INNOVATIONS</small>
        </div>
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Page level plugin JavaScript-->
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>
    <!-- Custom scripts for this page-->
    <script src="js/sb-admin-datatables.min.js"></script>
    <script>
    function setTwoNumberDecimal(event) {
    this.value = parseFloat(this.value).toFixed(2);
}
</script>

  </div>
  <?php
if(isset($_SESSION['message'])){
  $msg = $_SESSION['message'];
  ?>

<script type="text/javascript">
  alert("<?php  echo $msg; ?>");
</script>

  <?php
}

   ?>
</body>

</html>
<?php

unset($_SESSION['message']);

} ?>
