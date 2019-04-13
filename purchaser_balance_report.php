<?php
require ('dbconnect.php');
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
<!--    <link href="css/supplier.css" rel="stylesheet">-->
    <link href="css/balancelist.css" rel="stylesheet">
    <link href="css/salesreports.css" rel="stylesheet">
    <link href="css/cust_details.css" rel="stylesheet">
    <link href="css/select1.min.css" rel="stylesheet">
    <script type="text/javascript">
    function hide(){
      document.getElementById('selectedcity').style.display ='none';

        // document.getElementById('selectedproduct').style.display = 'none';
    }
    function showcity(){
         document.getElementById('selectedcity').style.display ='block';
      // document.getElementById('div2').style.display = 'block';
    }
    </script>
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
     <?php require('header.php');
    ?>

  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->

        <form class="cust_line" method="post" action="Reports/customer_balance_report.php">
            <h5 style="margin:-18px 0px 18px 0px " ><u>Customer Balance Report</u></h5>
            <div class="row">
              <div class="col-sm-4 sreportradio" >
                <label><input type="radio" name="city" value="selectedcities" onclick="showcity();" checked> Select City</label>
                <label><input type="radio" name="city" value="allcities" onclick="hide();"> All Cities</label>
              </div>

              <div class="col-sm-4 sreportradio productlabel" id="selectedcity" >
                <label class="retpro">City Name<span class="requiredfield">*</span></label>
                <select id='selcity' class="custselect " name="city_id" >
                  <option value=" " selected="selected">---Select City Name---</option>
                  <!--                         <option selected>Select Product</option>-->
                  <?php
                  //select product name form database
                  $selectCity = "SELECT * FROM `hk_cities`";
                  $selectcityExe = mysqli_query($conn,$selectCity);
                  while($selectRow = mysqli_fetch_array($selectcityExe)){


                    ?>

                    <option value="<?php echo $selectRow["id"]; ?>"><?php echo $selectRow["city_name"]; ?></option>

                    <?php
                  }
                  ?>

                </select>


                <!-- <select id="sretpro_type" class="ptext1" name="quantity_type_id" >
                  <?php
                  //select product name form database
                  $selectProduct = "SELECT * FROM `hk_quantity_type`";
                  $selectproductExe = mysqli_query($conn,$selectProduct);
                  while($selectRow = mysqli_fetch_array($selectproductExe)){


                    ?>

                    <option value="<?php echo $selectRow["id"]; ?>"><?php echo $selectRow["quantity_type"]; ?></option>

                    <?php
                  }
                  ?>

                </select> -->
              </div>
                        </div>
            <div class="row">
               <button class="buttonsubmit" type="submit" formtarget="_blank">Generate Report</button>
           </div>



  </form>
</div>


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
    <script src="js/select2.min.js"></script>
  <script>
    $(document).ready(function(){

        // Initialize select2
        $("#selcity").select2();

        // Read selected option
        $('#but_read').click(function(){
            var username = $('#selcity option:selected').text();
            var userid = $('#selcity').val();

  //                $('#result').html("id : " + userid + ", name : " + username);
        });
    });
    </script>


</body>

</html>
<?php } ?>
