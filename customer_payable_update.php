<?php
require('dbconnect.php');
$person_id = $_POST['submit'];

$query = "SELECT first_name,last_name FROM `hk_persons` WHERE id = ".$_POST['submit'];
$exe = mysqli_query($conn,$query);
$row = mysqli_fetch_array($exe);
$customer_name = $row['first_name']." ".$row['last_name'];

$query1 = "SELECT SUM(cr) as credit,SUM(dr) as debit FROM hk_account_".$person_id." WHERE `active`='1'";
$exe = mysqli_query($conn,$query1);
$row = mysqli_fetch_array($exe);
$due_amount = $row['debit']-$row['credit'];
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
    <link href="css/due.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
     <?php require('header.php');
    ?>

  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->

        <form class="cust_line" method="post" action="customer_due_module/customer_due_update_creation_handler.php">
            <div class="row"><h5 style="margin:-8px 2px 8px 0px " ><u>Customer Payable Update</u></h5>
    <pre style="float:right">                                                               (Note: Fields with <i class="fa fa-asterisk" style="font-size:13px;color:red"></i> mark are compulsory)</pre></div>
<!--            <h5 style="margin:-18px 0px 18px 0px " ><u>Customer Due Update</u></h5>-->
    <div class="row">
        <div class="col-md-6">
                   <label for="name">Date<span class="requiredfield">*</span></label>
       <input type="date" name = "date" class="custpay" value="<?php echo date('Y-m-d'); ?>" required>
                 <!-- <div class="row balrow"></div> -->
                 <label for="name">Customer Name<span class="requiredfield">*</span></label>
                 <input type="text" class="duetext dueamt1" name="customer_name" id="customer_name" value = "<?php echo $customer_name; ?>" readonly>

       <label for="name">Due Amount<span class="requiredfield">*</span></label>
       <input type="text" class="duetext dueamt1" name="due_amount" id="due_amount" value = "<?php echo $due_amount; ?>" readonly>
       <input type="text" style="display:none" class="duetext dueamt1" name="id" id="id" value = "<?php echo $person_id; ?>" readonly>

            <div class="row duerow"></div>
       <label for="name">Amount Paying<span class="requiredfield">*</span></label>
   <input type="text" class="duetext dueamt" name="enterd_amount" id="enterd_amount" onblur="calcDifference()" placeholder="Enter Amount.." required>

            <div class="row duerow"></div>
       <label for="name">Remaining Due<span class="requiredfield">*</span></label>
    <input type="text" class="duetext dueamt" name="remainingdue"  id="remainingdue" readonly>

    <div class="row duerow"></div>
<label for="name">Remarks</label>
<input type="text" class="duetext dueamt" name="particulars" placeholder="Clearing Due"  id="particulars">
      </div>
    </div>


     <div class="row duesubmit">


        <input  type="submit"  value="Submit" >
           <button class="buttonreset"><a href="customer_due_list.php" style="color: white; text-decoration: none;">Cancel  </a></button>

<!--
        <button class="buttonsubmit"><a href="#" style="color: white;">Add Expense</a></button>
        <button class="buttonreset" ><a href="#" style="color: white;">Add Income</a></button>
-->
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
    function calcDifference(){
       var due = $("#due_amount").val();
       var paid = $("#enterd_amount").val();
       var diffrence = parseFloat(due)-parseFloat(paid);
       $("#remainingdue").val(diffrence);
    }
    </script>

  </div>
</body>

</html>
