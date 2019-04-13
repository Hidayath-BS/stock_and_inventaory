<?php
session_start();
// require("logout.php");

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
  <!-- Custom styles for this template-->
  <link href="css/sb-admin1.css" rel="stylesheet">
    <link href="css/purchaseentry.css" rel="stylesheet">
    <!--    search dropdown-->
    <link href="css/select2.min.css" rel="stylesheet">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="script/getData.js"></script>
    <script src="script/purchseQuantity.js"></script>
    <script>

        function columsi(){


        for(ex = 2;ex<8; ex++){
//
    $("#table > thead > tr:nth-child("+ex+") > td:nth-child(1)").css('display','none');


        }

        }





    </script>
    <style type="text/css">
      .adddate{
        width: 60%;
        height: 40px;
      }
      .inline{
        display: inline;
      }
    </style>

</head>


<body class="fixed-nav sticky-footer bg-dark" id="page-top">

<?php
    require('header.php');
    ?>


<div class="content-wrapper">
<div class="container-fluid">

<div class="card mb-3">
  <div class="card-header">
    <h6>Arrivals Entry</h6>
  </div>

  <div class="card-body">
      <div class="container">

      <form action="arrivals_module/add_arrivals.php" method="POST">
        <div class="row">
        <div class="col-md-2">
        <label>Date</label>
        </div>
          <div class="col-md-4">
          
          <input type="date" id="ondate" class="adddate" name="ondate" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>">
          </div>

      <div class="col-md-2">
      <label>
              Supplier Name
            </label>
        </div>

          <div class="col-md-4">

            
            <select name="supplier_id" class="adddate">
                            <!-- <option selected="selected">Select Supplier Name</option> -->
                <?php
                        $sql = "SELECT id,first_name,last_name FROM hk_persons WHERE person_type_id = 1";
                        $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
                        while( $rows = mysqli_fetch_assoc($resultset) ) {
                    ?>
                <option value="<?php echo $rows["id"]; ?>"><?php echo $rows["first_name"]." ".$rows["last_name"]; ?></option>
                <?php } ?>
                  </select>


          
          
          </div>
        </div>
        <hr>
        <div class="row">

          <div class="col-md-2">
          <label>Weigh Bill Number</label>
          </div>
          <div class="col-md-4">
          
          <input type="text" class="adddate" value="" placeholder="Weigh Bill Number" name="slipnumber"/>
          
          </div>
        </div>
        
        <div class="row">
        <div class="col-md-2">
        <label> Rate </label>
          </div>

            <div class="col-md-4">
                
                <input type="float" class="adddate" placeholder="Rate" name="rate" value=""  />
            </div>

            <div class="col-md-2">
            <label> Quantity </label>
          </div>
            <div class="col-md-4">
                
                <input type="float" class="adddate" name="quantity" placeholder="Quantity" value=""  />
            </div>
        </div>
        <hr>
        <div class="row">
        <div class="col-md-2">
        <label> Advance </label>
          </div>
            <div class="col-md-4">
                
                <input type="float" class="adddate" name="advance" placeholder="Advance Amount" value="0"  />
            </div>
        </div>

        <div class="row">

            <div class="col-md-2 offset-10">
                <button type="submit" class="btn btn-primary"> SUBMIT </button>
            </div>
        </div>
      </form>


      
      </div>
  
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
<script src="js/supplierdetails.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


     <?php
  if(isset($_SESSION['message'])){
  $msg = $_SESSION['message'];
  ?>

  <script type="text/javascript">
  alert("<?php  echo $msg; ?>");
  </script>

  <?php
  unset($_SESSION['message']);
  }

   ?>


</body>

</html>
<?php } ?>
