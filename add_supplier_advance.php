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
    <link href="css/supplier_advances.css" rel="stylesheet">
     <!--    search dropdown-->
    <link href="css/select1.min.css" rel="stylesheet">

<style type="text/css">
  .cust-input{
    width: 60%;
    display: inline-block;
  }
</style>


</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <?php
    require('header.php');
    ?>




  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->

        <form class="cust_line" method="POST" action="supplier_advance_module/supplier_advance_creation_handler.php">
            <div class="row"><h5 style="margin:-8px 2px 8px 0px " ><u>Add Supplier Advances</u></h5>
    <pre style="float:right">                                                                   (Note: Fields with <i class="fa fa-asterisk" style="font-size:10px;color:red"></i> mark are compulsory)</pre></div>
<!--            <h5 style="margin: -18px 0px 8px 0px"><u>Supplier Advances</u></h5>-->
    <div class="row">
        <div class="col-md-6">
            <label for="date" >Date<span class="requiredfield">*</span></label>
     <input type="date" id="ondate" class="adtext1" name="ondate" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" required>

             <div class="salesrow"></div>
          

         
            
              <div class="salesrow"></div>

                

<div class="salesrow"></div>
         
       </div>
       <div class="col-md-6">
        
<label for="address" >Supplier Name<span class="requiredfield">*</span></label>
         <select id="order"  class="form-control cust-input" name="supplier_id" required>
           <option value="">Select Supplier</option>
         <?php
            require('dbconnect.php');
            $u_type_q = " SELECT * FROM `hk_persons` WHERE person_type_id = '1' ORDER BY `first_name` ";
            $exe = mysqli_query($conn,$u_type_q);
            while($row = mysqli_fetch_array($exe)){
            ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['first_name']." ".$row['last_name']; ?></option>
            <?php
            }
            ?>
</select>
 <input type="button" class="suppadd" value="Add Supplier" onclick="openWin()">

       </div>

    </div>

    <div class="row">
      <div class="col-md-6">
        <label for="name">Advance Type<span class="requiredfield">*</span></label>
    <select id="type" class="form-control cust-input"  name="advance_type_id" required>
        <?php
            require('dbconnect.php');
            $u_type_q = " SELECT * FROM `hk_supplier_advance_type`";
            $exe = mysqli_query($conn,$u_type_q);
            while($row = mysqli_fetch_array($exe)){
            ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['supplier_advance_type']; ?></option>
            <?php
            }
            ?>
</select>
      </div>
      <div class="col-md-6">
        <label for="location" class="salesloca">Amount<span class="requiredfield">*</span></label>
        <input type="text"  class="form-control cust-input" name="amount" placeholder="Enter advance amount" required>
      </div>

    </div>

    <div class="row">
        <div class="col-md-6">
          <label class="salesloca">Particulars</label>
          <input type="text" name="particulars" class="form-control cust-input">
        </div>
    </div>

    

    <div class="row ">
    <input  type="submit"  class="buttonsubmit" value="Submit">
       <a href="supplier_advance_list.php" style="text-decoration: none;" class="buttonreset"><span>Cancel </span> </a>
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
    <!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    </div> -->
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
   <script src="js/supplierdetails.js"></script>

<!--      search dropdown-->
      <script src="js/jquery-3.2.1.min.js"></script>
      <script src="js/select2.min.js"></script>

 <script>
        $(document).ready(function(){

            // Initialize select2
            $("#order").select2();

            // Read selected option
            $('#but_read').click(function(){
                var username = $('#order option:selected').text();
                var userid = $('#order').val();
            });
        });
        </script>
 <script>
        $(document).ready(function(){

            // Initialize select2
            $("#type").select2();

            // Read selected option
            $('#but_read').click(function(){
                var username = $('#type option:selected').text();
                var userid = $('#type').val();
            });
        });
        </script>
<script>
        function openWin() {
            window.open("add_supplier.php");
        }
        </script>

  </div>
</body>

</html>
<?php } ?>
