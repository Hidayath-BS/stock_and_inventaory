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
    <link href="vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">
    <link href="css/salesreports.css" rel="stylesheet">
    <script type="text/javascript">
    function show1(){
      document.getElementById('div1').style.display ='block';
        document.getElementById('div2').style.display = 'none';
    }
    function show2(){
         document.getElementById('div1').style.display ='none';
      document.getElementById('div2').style.display = 'block';
    }

    </script>

    <script type="text/javascript">
    function hide(){
      document.getElementById('selectedproduct').style.display ='none';

        // document.getElementById('selectedproduct').style.display = 'none';
    }
    function showproduct(){
         document.getElementById('selectedproduct').style.display ='block';
      // document.getElementById('div2').style.display = 'block';
    }
    function showcustomer(){
          document.getElementById('selectedcustomer').style.display ='block';
          // document.getElementById('selectedproduct').style.display ='none';
    }
    function hidecustomer(){
        document.getElementById('selectedcustomer').style.display ='none';
    }
    </script>

  </head>

  <body class="fixed-nav sticky-footer bg-dark" id="page-top">
    <!-- Navigation-->
    <?php
    require('header.php');
    ?>
    <div class="content-wrapper">
      <div class="container-fluid">
        <!-- customer details-->

        <form class="cust_line" method="post" action="Reports/sales_reg_report2.php">
          <h5 style="margin: -18px 0px 8px 0px"><u>Sales Register Reports</u></h5>
          <div class="row">
            <div class="col-md-4 sreportborder">
              <div class="radio-inline"><label><input type="radio"  checked="" name="transaction_type"  value="cash">Cash</label></div>
              <div class="radio-inline"><label><input type="radio" name="transaction_type" value="credit"> Credit</label></div>
              <div class="radio-inline"><label><input type="radio" name="transaction_type" value="both"> Both</label></div>
            </div>

            <div class="col-sm-4 sreportradio" >
              <div class="radio block"><label><input type="radio" checked="" name="typeDate" onclick="show1();" value="onDate">As On Date</label></div>
              <div class="radio block"><label><input type="radio" name="typeDate" onclick="show2();" value="btDate">Between Dates</label></div>

            </div>


    <div class="col-md-4 sreportborder1">

            <div id="div1">
               <label for="date" >Date</label>
               <input type="date" id="ondate" class="sreportmtext1" name="ondate" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
            </div>



            <div id="div2" style="display: none">
      <label>  From</label>
       <input type="date" name="fromdate" id="fromdate" class="sreportmtext1" max="<?php echo date('Y-m-d'); ?>">
       <br>
       <label>To</label>
        <input type="date" id="todate" name="todate" class="sreportmtext2"  value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                  </div>

        </div>
</div>
          <div class="row">
            <div class="col-sm-4 sreportradio" >
              <label><input type="radio" name="product" value="selectedproducts" onclick="showproduct();" checked> Select Product</label>
              <label><input type="radio" name="product" value="allproducts" onclick="hide();"> All Products</label>
            </div>

            <div class="col-sm-4 sreportradio productlabel" id="selectedproduct" >
              <label class="retpro">Product Name<span class="requiredfield">*</span></label>
              <select id="sretpro_type" class="ptext" name="product_id" >
                <option value=" " selected="selected">---Select Product Name---</option>
                <!--                         <option selected>Select Product</option>-->
                <?php
                //select product name form database
                $selectProduct = "SELECT * FROM `hk_products` where products_active=1 ORDER BY `name`";
                $selectproductExe = mysqli_query($conn,$selectProduct);
                while($selectRow = mysqli_fetch_array($selectproductExe)){


                  ?>

                  <option value="<?php echo $selectRow["id"]; ?>"><?php echo $selectRow["name"]." ".$selectRow["type"]." ".$selectRow["quantity_type"]; ?></option>

                  <?php
                }
                ?>

              </select>


              <!-- <select id="sretpro_type" class="ptext1" name="quantity_type_id" >
                <?php
                //select product name form database
                $selectProduct = "SELECT * FROM `hk_quantity_type` ";
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
            <div class="col-sm-4 sreportradio" >
              <label><input type="radio" name="customer" value="selectedcustomers" onclick="showcustomer();" checked>  Select Customer</label>
              <label><input type="radio" name="customer" value="allcustomers" onclick="hidecustomer();"> All Customers</label>

            </div>

            <div class="col-sm-4 sreportradio productlabel" id="selectedcustomer" >
              <label class="retpro">Customer Name<span class="requiredfield">*</span></label>
              <select id="sretpro_type" class="ptext" name="customer_id" >
                <option value=" " selected="selected">---Select Customer Name---</option>
                <!--                         <option selected>Select Product</option>-->
                <?php
                //select product name form database
                $selectCustomer = "SELECT * FROM `hk_persons` where person_active=1 AND person_type_id=2 ORDER BY `first_name`";
                $selectcustomerExe = mysqli_query($conn,$selectCustomer);
                while($selectRow = mysqli_fetch_array($selectcustomerExe)){


                  ?>

                  <option value="<?php echo $selectRow["id"]; ?>"><?php echo $selectRow["first_name"]." ".$selectRow["last_name"]; ?></option>

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


        <div class="row daysubmit">

          <button class="buttonsubmit" type="submit" formtarget="_blank"><a >Continue</a></button>
          <button class="buttonreset"  type="reset"><a >Back</a></button>
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
