<?php
session_start();

require("logout.php");
if($_SESSION['username']==""){
    header("Location: loginn.php");
}
else{
?>
<?php
require('dbconnect.php');
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
    <link href="css/supplier_account_statement.css" rel="stylesheet">
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

  <form class="cust_line" action = "Reports/supplier_account_statement.php" method="post">
    <h5 style="margin: -18px 0px 8px 0px"><u>Supplier Account Statement</u></h5>
    <div class="row">
    <div class="col-md-6">
        <label class="headingdate">Supplier Name:</label>
        <select class="dropsas" id = "id" name = "id">
          <option>Select Supplier</option>
          <?php
            $sqlpname = "SELECT * FROM `hk_persons` WHERE person_type_id = 1 ORDER BY `first_name`";
            $resultset = mysqli_query($conn, $sqlpname) or die("database error:". mysqli_error($conn));
            while( $rowscust = mysqli_fetch_assoc($resultset) ) {
            ?>

            <option name = "id" value="<?php echo $rowscust["id"]; ?>">
            <?php echo $rowscust["first_name"]." ".$rowscust["last_name"]; ?>
            </option>
            <?php } ?>
                  </select>
        </div>
      </div>
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
   </div>





       </div>
    </div>
    <label>
      <input type = "checkbox" name = "details"> Show Details
    </label>

    <div class="row daysubmit">

<button class="balancesheetbut" type="submit" formtarget="_blank"><a>View Balance Sheet </a></button>
<!--   <a href="#" style=" text-decoration: none;" class="buttonreset">  <span >Cancel</span></a>-->

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
   <script src="js/supplierdetails.js"></script>

  </div>
</body>

</html>
<?php } ?>
