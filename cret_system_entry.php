
<?php
session_start();
require("logout.php");

if($_SESSION['username']==""){
    header("Location: loginn.php");
}
else{
  $crateTrackerId = $_POST["crateTrackerId"];
  require("dbconnect.php");

  $crateDataQ = "SELECT HKCT.*,HKP.first_name,HKP.last_name FROM `hk_crate_tracker`
   AS HKCT left JOIN hk_persons AS HKP ON HKP.id = HKCT.person_id WHERE HKCT.id='$crateTrackerId'";

   $exe = mysqli_query($conn,$crateDataQ);
   while($row = mysqli_fetch_array($exe)){
     $customer_name = $row["first_name"]." ".$row["last_name"];
     $crate_count = $row["number_of_crates"];
     $crate_amount = $row["amount"];

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
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
  <link href="css/cret_system_entry.css" rel="stylesheet">
     <link href="css/cust_details.css" rel="stylesheet">
<!--    search dropdown-->
    <link href="css/select1.min.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <?php
  require('header.php');

  ?>
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- customer details-->

      <form class="cust_line" action="crate_module/crate_return_handler.php" method="post">
        <div class="row"><h5><u>Crate Return Entry</u></h5>
    <pre style="float:right">       								 (Note: Fields with <i class="fa fa-asterisk" style="font-size:10px;color:red"></i> mark are compulsory)</pre></div>
        <div class="row">
          <div class="col-md-6">
            <label for="cname"> Customer Name <span class="requiredfield">*</span></label>
            <input type="text" class="custstyle" name="custname" placeholder="Customer Name" value="<?php echo "$customer_name"; ?>" required readonly>
            <div class="custrow"></div>
            <label for="ncrets" >Number Of Crates<span class="requiredfield">*</span></label>
            <input type="text"  class="numcrets" name="ncrets" placeholder="Number of Crates" value="<?php echo $crate_count; ?>" readonly required>

            <div class="custrow"></div>

            <label for="amount">Amount <span class="requiredfield">*</span></label>
            <input type="text" class="camount" name="amount" placeholder="Amount of Crates" value="<?php echo $crate_amount; ?>" required readonly>

            <div hidden>
              <label for="amount">Crate Tracker Id <span class="requiredfield">*</span></label>
              <input type="text" class="camount" name="crateTrackerId" placeholder="Crate Tracker Id" value="<?php echo $crateTrackerId; ?>" required readonly>

            </div>

            </div>
            <div class="col-md-6">

              <label for="rcrets">Date<span class="requiredfield">*</span> </label>
              <input type="date" class="rcrets" name="date" placeholder="Returning Crates" value="" required>


            <label for="rcrets">Number of Returning Crates<span class="requiredfield">*</span> </label>
            <input type="text" class="rcrets" name="crate_count" placeholder="Returning Crates" value="" required>

          	<div class="custrow"></div>
            <label for="amount">Paying Amount<span class="requiredfield">*</span> </label>
            <input type="text" class="ramount" name="crate_amount" placeholder="Paying amount" value="" required>

          </div>

      </div>



<div class="row">

<button class="buttonsubmit" type="submit"><a >Submit</a></button>
   <a href="#" style=" text-decoration: none;" class="buttonreset">  <span >Cancel</span></a>


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




</div>
</body>

</html>
<?php } ?>
