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
    <link href="css/stock_register.css" rel="stylesheet">
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
    <script type="text/javascript">
    function hide(){
      document.getElementById('selectedproduct').style.display ='none';
        // document.getElementById('selectedproduct').style.display = 'none';
    }
    function showproduct(){
         document.getElementById('selectedproduct').style.display ='block';
      // document.getElementById('div2').style.display = 'block';
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

      <form class="cust_line" action = "Reports/stock_reporttwo.php" method="post">
       <h5 style="margin: -18px 0px 8px 0px"><u>Stock Register</u></h5>

      


         <div class="row">

          <div class="col-md-12" id="selectedproduct">
            <label class="retpro">Product Name<span class="requiredfield">*</span></label>
                     <select id="sretpro_type" class="ptext" name="product_id" >
                         <option value=" " selected="selected">---Select Product Name---</option>

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
     </div>
    </div>




    <div class="row">

        <button class="buttonsubmit" type="submit" formtarget="_blank"><a >Continue</a></button>
   <a style=" text-decoration: none;" class="buttonreset" href="#">  <span >Cancel</span></a>
    </div>
   </form>

         </div>

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
   <script src="js/supplierdetails.js"></script>


</body>

</html>
<?php }

    ?>
