<?php
require('dbconnect.php');
session_start();
setlocale(LC_MONETARY, 'en_IN');

?>

<!DOCTYPE HTML>
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
    <link href="css/balance.css" rel="stylesheet">
<style type="text/css">
  .align-left{
    text-align: left;
  }
  .align-right{
    text-align: right;
  }
  .cust-label{
    padding: 0px;
  }
  .float-left{
    float: left;
  }
  .float-right{
    float: right;
  }

  .cust-form{
    border: 3px solid #ccc;
    padding: 20px;
  }
  .submit-btn{
    margin-left: 0px !important;
    float: none !important;
    margin-top: 30px;
  }
</style>


</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
     <?php require('header.php');
    ?>

  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->

        
              <div class="row">
                <div class="col-md-12">
                  <h5 class="text-center"><u>Admin Authentication</u></h5>  
                </div>
                
              </div>

   

<br>
    <div class="row">
        <div class="col-md-4 offset-4">

          <form class="cust-form" method="post" action="customer_balance_module/admin_auth.php">
           
              
          <label>Admin Username</label>
          <input class="form-control" type="text" name="username">

          <label>Password</label>
          <input class="form-control" type="password" name="password" required="required">    

          <label>Date</label>
          <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>"  required="required" class="form-control">
          
          <div class="text-center">
            

            <input type="submit" name="submit" value="SUBMIT" class="btn-primary submit-btn">
          </div>
          

          </form>
          

          
     
        </div>

      


      </div>

      

      


      

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
    <?php 
    require("logout.php");
     ?>
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
       var due = $("#balance_amount").val();
       var paid = $("#enterd_amount").val();
       var diffrence = parseFloat(due)-parseFloat(paid);
       $("#remainingdue").val(diffrence);
    }
    </script>

<?php
  if(isset($_SESSION['message'])){
    $msg = $_SESSION['message'];
  }
?>


 <script type="text/javascript">
  alert("<?php  echo $msg; ?>");
  </script>
<?php
  unset($_SESSION['message']);
   ?>
  </div>
</body>

</html>
