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
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="../vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="../css/sb-admin.css" rel="stylesheet">
    <link href="../css/cust_details.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->

    <?php
    require('header.php');
     require('../dbconnect.php');
 ?>
  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->

        <form class="cust_line" method="post" action="order_status_type_edit_handler.php">
            <?php
                $id = $_POST["edit"];
                $editdata = "SELECT * FROM `hk_orders_status_type` WHERE id=".$id;
                $data = mysqli_query($conn,$editdata);
            if($data){
                while($datarow = mysqli_fetch_array($data)){




            ?>
<!--             <h5 style="margin: -18px 0px 8px 0px"><u>Edit Order Status</u></h5>-->
            <div class="row"><h5 style="margin: -18px 0px 8px 0px"><u>Edit Order Status Type</u></h5> 
    <pre style="float:right">       								 (Note: Fields with <i class="fa fa-asterisk" style="font-size:13px;color:red"></i> mark are compulsory)</pre></div>
    <div class="row">
        <div class="col-md-6">
       <label for="name">Order Status Type<span class="requiredfield">*</span></label>
   <input type="text" id="type" name="type"  class="orderqty1" placeholder="Type.." value="<?php echo $datarow['order_status_type']; ?>" required>
      </div>
    </div>

     <div class="row">


<!--
        <input  type="submit"  value="Submit" class="productSubmit">
        <input  type="reset" value="Cancel" class="productReset" >
-->

         <button class="buttonsubmit" type="submit" name="submit" value="<?php echo $id; ?>"><a style="color: white;">Submit</a></button>
        <button class="buttonreset"  type="reset"><a href="../order_list.php" style="color: white; text-decoration:none;">Cancel</a></button>
<!--
        <button class="buttonsubmit"><a href="#" style="color: white;">Submit</a></button>
        <button class="buttonreset" ><a href="#" style="color: white;">Cancel</a></button>
-->
    </div>
            <?php }}else{
                    echo "sorry".mysqli_error($conn);
                } ?>
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
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Page level plugin JavaScript-->
    <script src="../vendor/datatables/jquery.dataTables.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin.min.js"></script>
    <!-- Custom scripts for this page-->
    <script src="../js/sb-admin-datatables.min.js"></script>


  </div>
</body>

</html>
<?php
//require('../dbconnect.php');
//$name = $_POST["name"];
//$cust_type = $_POST["cust_type"];
//$address = $_POST["address"];
//$city = $_POST["city"];
//$state = $_POST["state"];
//$phone = $_POST["phone"];
//$altphone = $_POST["altphone"];
//
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
//    echo "success query exicuted";
//}
//else{
//    echo "sorry".mysqli_error($conn);
//}
//
}
?>
