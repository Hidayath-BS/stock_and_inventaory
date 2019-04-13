<?php
require('../dbconnect.php');
$id=$_POST['edit'];
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
    <link href="../css/quantity.css" rel="stylesheet">
</head>

    <body class="fixed-nav sticky-footer bg-dark" id="page-top">
 <?php
        require('../header.php'); 
    ?>
  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->

        <form class="cust_line" method="post" action="quantity_type_edit_handler.php">
            <?php
            $qtyquery="SELECT * FROM `hk_quantity_type` WHERE id=".$id;
            $qtyexe=mysqli_query($conn, $qtyquery);
            while($queryrow=mysqli_fetch_array($qtyexe)){
            ?>

            <div class="row"><h5 style="margin: -18px 0px 8px 0px"><u>Add Quantity Type</u></h5>
    <pre style="float:right">       								 (Note: Fields with <i class="fa fa-asterisk" style="font-size:10px;color:red"></i> mark are compulsory)</pre></div>
    <div class="row">
        <div class="col-md-6">
       <label for="name">Quantity Type<span class="requiredfield">*</span></label>

   <input type="text" class="baltext" id="type" name="qty_type" placeholder="Unit"
          value="<?php echo $queryrow["quantity_type"]; ?>" required>

             <input type="hidden" class="baltext" id="type" name="qty_id" placeholder="Unit"
          value="<?php echo $queryrow["id"]; ?>" required>

      </div>
    </div>
     <div class="row">

        <input  type="submit"  value="Submit" class="buttonsubmit">
          <a href="../quantity_type_list.php" style="color: white;text-decoration:none;" class="buttonreset">Cancel</a>

<!--
        <button class="buttonsubmit"><a href="#" style="color: white;">Submit</a></button>
        <button class="buttonreset" ><a href="#" style="color: white;">Cancel</a></button>
-->
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


  </div>
</body>

</html>
