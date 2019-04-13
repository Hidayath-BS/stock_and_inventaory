<?php
    require('../dbconnect.php');
    $id = $_POST["edit"];
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
    <link href="../css/supplier_advances_type.css" rel="stylesheet">


</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <?php
    require('header.php');
    ?>
  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->

        <form class="cust_line" method="POST" action="supplier_advance_type_edit_handler.php">

            <?php
            $editQuery= "select * from `hk_supplier_advance_type` where id='$id'";
            $editExe = mysqli_query($conn,$editQuery);
            while($editRow = mysqli_fetch_array($editExe)){

            ?>
              <div class="row"><h5 style="margin:-8px 2px 8px 0px " ><u>Edit Supplier Advance Type</u></h5>
    <pre style="float:right">                                                               (Note: Fields with <i class="fa fa-asterisk" style="font-size:10px;color:red"></i> mark are compulsory)</pre></div>
<!--            <h5 style="margin: -18px 0px 8px 0px"><u>Supplier Advances</u></h5>-->
    <div class="row">
        <div class="col-md-6">

         <label for="location" class="salesloca">Supplier Advance Type<span class="requiredfield">*</span></label>
        <input type="text"  class="advancetext advanceamount" name="type" value="<?php echo $editRow["supplier_advance_type"]; ?>" placeholder="Advance type" required>

       </div>

        <input type="hidden" class="pincode" name="code" value="<?php echo $id; ?>" readonly>
    </div>

    <div class="row advancesubmit">
<!--
    <input  type="submit" class="advancesubmit" value="Submit">
         <button class="buttonreset"><a href="../supplier_advance_type_list.php" style="color: white; text-decoration: none;">Cancel  </a></button>
-->
         <input  type="submit" class="buttonsubmit" value="Submit">
      <a href="../supplier_advance_type_list.php" style="text-decoration: none;" class="buttonreset"><span>Cancel </span> </a>
     </div>
      <?php
        }
      ?>
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
   <script src="../js/supplierdetails.js"></script>

  </div>
</body>

</html>
