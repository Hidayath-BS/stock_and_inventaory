<?php
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
    <link href="../css/supplier_advances.css" rel="stylesheet">
 <link href="../css/select1.min.css" rel="stylesheet">

</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <?php
    require('header.php');
    ?>
  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->

        <form class="cust_line" method="POST" action="supplier_advance_edit_handler.php">
            <div class="row"><h5 style="margin:-8px 2px 8px 0px " ><u>Edit Supplier Advances</u></h5>
    <pre style="float:right">                                                                   (Note: Fields with <i class="fa fa-asterisk" style="font-size:10px;color:red"></i> mark are compulsory)</pre></div>

            <?php
                require('../dbconnect.php');
                $query = "SELECT HKSA.*,HKP.first_name,HKP.last_name,HKP.id
                FROM `hk_supplier_advances` AS HKSA
                LEFT JOIN `hk_persons` AS HKP ON HKSA.person_id=HKP.id
                WHERE HKSA.id=".$id;

                $exe = mysqli_query($conn,$query);
            while($datarow = mysqli_fetch_array($exe)){
            ?>
    <div class="row">
        <div class="col-md-6">
          <label for="address" >Supplier Name<span class="requiredfield">*</span></label>

<!--
        <input type="text" id="address" class="advancetext" value="<?php echo $datarow['first_name']; ?>"
        name="address" placeholder="supplier name.." readonly>
-->
            <select  class="advancetype" id="sname" name="advance_type_id" required>
			<?php
			$selectAdvanceType = "select * from `hk_persons` WHERE person_type_id = '1'";
			$selectAdvanceExe = mysqli_query($conn,$selectAdvanceType);
			while($selectAdvanceRow = mysqli_fetch_array($selectAdvanceExe)){
			 ?>
  		 <option value="<?php echo $selectAdvanceRow['id']; ?>" <?=$selectAdvanceRow['id'] == $datarow["id"] ? 'selected="selected"':'' ?>><?php echo $selectAdvanceRow['first_name']; ?></option>
  		 <?php } ?>
</select>

          <div class="salesrow"></div>

          <label for="name">Advance Type<span class="requiredfield">*</span></label>
		<select  class="advancetype" id="type" name="advance_type_id" required>
			<?php
			$selectAdvanceType = "select * from `hk_supplier_advance_type`";
			$selectAdvanceExe = mysqli_query($conn,$selectAdvanceType);
			while($selectAdvanceRow = mysqli_fetch_array($selectAdvanceExe)){
			 ?>
  		 <option value="<?php echo $selectAdvanceRow['id']; ?>" <?=$selectAdvanceRow['id'] == $datarow["advance_type_id"] ? 'selected="selected"':'' ?>><?php echo $selectAdvanceRow['supplier_advance_type']; ?></option>
  		 <?php } ?>
</select>

<div class="salesrow"></div>
         <label for="location" class="salesloca">Amount<span class="requiredfield">*</span></label>
        <input type="text"  class="advancetext advanceamount1" name="amount" value="<?php echo $datarow['amount']; ?>" placeholder="Enter advance amount" required>
       </div>
    </div>

     <input type="aria-hidden" class="pincode" style="margin-left:5%; display:none;" name="code"
     value="<?php echo $id; ?>" readonly>

<!--
   
    <input  type="submit"  value="Submit">
       <button class="buttonreset"><a href="../supplier_advance_list.php" style="color: white; text-decoration: none;">Cancel  </a></button>
    
-->
 <div class="row adsubmit">
            
            <button class="buttonsubmit" type="submit"><a >Submit</a></button>
     <a href="../supplier_advance_list.php" class="buttonreset" style=" text-decoration: none;" >  <span >Cancel</span></a>
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
      <script src="../js/jquery-3.2.1.min.js"></script>
      <script src="../js/select2.min.js"></script>
      <script>
        $(document).ready(function(){

            // Initialize select2
            $("#sname").select2();

            // Read selected option
            $('#but_read').click(function(){
                var username = $('#sname option:selected').text();
                var userid = $('#sname').val();
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

  </div>
</body>

</html>
