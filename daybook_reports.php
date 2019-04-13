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
  <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" />
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
    <link href="css/daybook.css" rel="stylesheet">



    <script type="text/javascript">
    function datecasshow1(){
      document.getElementById('datecas').style.display ='block';
        document.getElementById('datecass').style.display = 'none';
    }
    function datecasshow2(){
         document.getElementById('datecas').style.display ='none';
      document.getElementById('datecass').style.display = 'block';
    }
    </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/locales/bootstrap-datepicker.bn.min.js"></script>

</head>

<body onload = "document.refresh();" class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <?php
    require('header.php');
    ?>
  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->

  <form class="cust_line" action = "Reports/daybookreport.php" method="post">
    <h5 style="margin: -18px 0px 8px 0px"><u>Daybook Reports</u></h5>
    <div class="row">
        <div class="col-md-6">

          <div>
           <label class="headingcas"><input type="radio" name="dateType" value="onDate" onclick="datecasshow1();" checked > On date</label>
           <label class="headingcas"><input type="radio" name="dateType" value="btDate" onclick="datecasshow2();"> Between dates</label>
         </div>
         <div id="datecas">
        <label for="date" >Date: </label>
        <input type="date" id="ondate" class="datetextt" name="ondate" value="<?php echo date('Y-m-d'); ?>"
         max="<?php echo date('Y-m-d'); ?>">

     </div>
     <div id="datecass" style="display: none">
               From :  <input type="date" name="fromdate" class="datetextt" id="fromdate" max="<?php echo date('Y-m-d'); ?>">  To :
                <input type="date" id="todate" name="todate" class="datetextt" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
           </div>
        <!-- <div>
        <label><input type="radio" name="Radio" value="date1"onclick="show3();" checked > On date</label>
        <label><input type="radio" name="Radio" value="on date2" onclick="show4();"> between</label>

    </div> -->

<!--
            <div class="dayrow"></div>

            <label for="name">Sales</label>
<select  class="dayselect daysales" name="sales" >
 <option value="First Choice">Detailed</option>
 <option value="Second Choice">Compact</option>
</select>
            <div class="dayrow"></div>
            <label for="name">Purchase</label>
<select  class="dayselect" name="purchase" >
 <option value="First Choice">Detailed</option>
 <option value="Second Choice">Compact</option>
</select>
-->
             <div class="dayrow"></div>
            <input type="radio" class="daycheck" name="daybook_report" value="sales">Show Detailed Sales

            <div class="dayrow"></div>
            <input type="radio" class="daycheck" name="daybook_report" value="purchases">Show Detailed Purchase

            <div class="dayrow"></div>
            <input type="radio" class="daycheck" name="daybook_report" value="sales and purchases">Show Detailed Sale and Purchase


            <div class="dayrow"></div>
            <input type="radio" class="daycheck" name="daybook_report" value="payments">Payments

            <div class="dayrow"></div>
            <input type="radio" class="daycheck" name="daybook_report" value="receipts">Receipts

            <!-- <div class="dayrow"></div>
            <input type="radio" class="daycheck" name="vehicle" value="interstate">Summarise Purchaser A/C
            <div class="dayrow"></div>
            <input type="radio" class="daycheck" name="vehicle" value="interstate">Summarise Supplier A/C -->


       </div>
    </div>

    <div class="row daysubmit">

<button class="buttonsubmit" type="submit" formtarget="_blank"><a >Submit</a></button>
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

  </div>
</body>

</html>
<?php } ?>
