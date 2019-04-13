<?php
session_start();
require("logout.php");

if($_SESSION['username']==""){
    header("Location: loginn.php");
}
else{

  require("dbconnect.php");
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

<style type="text/css">
  .fl-R{
    float: right;
    }
    .in{
      width: 48%;
    }

</style>

</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
    <?php
    require('header.php');
 ?>
  <!-- Navigation-->
    <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->

        <form class="cust_line" method="post" action="delaccount/closeAccounthandler.php">

             <div class="row"><h5 style="margin: -18px 0px 8px 0px"><u>Close Account</u></h5>

    <pre style="float:right">       								 (Note: Fields with <i class="fa fa-asterisk" style="font-size:10px;color:red"></i> mark are compulsory)</pre></div>
    <div class="row">

        <div class="col-md-5">
          <label for="name">Select Type of Account <span class="requiredfield">*</span></label>

          <select id="typeAcc" class="fl-R" onchange="selacctype()">
            <option>-- select Type of account --</option>
          <?php
          $typeAccQ = "SELECT * FROM `hk_person_type`";
          $typeAccExe = mysqli_query($conn,$typeAccQ);
          while($typeAccRow = mysqli_fetch_array($typeAccExe)){
           ?>
            <option value="<?php echo $typeAccRow["id"]; ?>"><?php echo $typeAccRow["person_type"]; ?></option>

           <?php
          }
          ?>

          </select>
          
        </div>

        <div class="col-md-6">
          <label for="name">Select Account <span class="requiredfield">*</span></label>
          <select id="account" class="fl-R" name="account" required="required">
            

          </select>
        </div>

    </div>
      
      <div class="row">
        <div class="col-md-5">
          <label for="name">From Date <span class="requiredfield">*</span></label>
          <input type="date" id="fromdate" onchange="mindate()" max="<?php echo date("Y-m-d"); ?>" class="fl-R in" name="fromdate" required="required" >
        </div>

        <div class="col-md-6">
          <label for="name">TO Date <span class="requiredfield">*</span></label>
          <input type="date"  id="todate" class="fl-R in" max="<?php echo date("Y-m-d"); ?>" name="todate" required="required">
        </div>

      </div>     

      <div class="row">
        
        <div class="col-md-6 offset-2">
          <label for="name">Please enter your Password <span class="requiredfield">*</span></label>
          <input type="text" name="password" required="required" placeholder="Enter Your password">

        </div>

      </div>
    



     <div class="row">


        <input  type="submit"  value="Submit" class="buttonsubmit">

        <a href="index.php" style="color: white;text-decoration:none;" class="buttonreset">Cancel</a>

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


 <script type="text/javascript">
      
      function selacctype(){
        var acctype = $("#typeAcc").val();

        var dataString = 'acctype='+acctype;
        $.ajax({

      url: 'delaccount/Accountlist.php',
      dataType: "json",
      data: dataString,
      type: 'POST',
      cache: false,
      success: function(sales) {
        if(sales){
          $("#account").html("");
          for(i = 0; i<sales.length;i++){
            sales[i] = JSON.parse(sales[i]);
            $("#account").append(`
              <option value="`+sales[i].id+`">`+sales[i].first_name+` `+sales[i].last_name+`</option>
              `);
          }
        }
      }
  });
      }

    </script>


 <script type="text/javascript">
     function mindate(){
      var mindate = $("#fromdate").val();
      $("#todate").attr("min",mindate);
     }

   </script>

<?php

if(isset($_SESSION['msg'])){
?>
<script type="text/javascript">
  
  alert("<?php echo $_SESSION['msg']; ?>");

</script>

<?php
}

unset($_SESSION['msg']);
 ?>


  </div>
</body>

</html>
<?php } ?>
