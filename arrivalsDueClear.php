<?php
session_start();
// require("logout.php");

if($_SESSION['username']==""){
    header("Location: loginn.php");
}
else{
    $arrival = $_GET["arrival"];
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
    require('dbconnect.php');
    $getquery = "SELECT HKA.* , HKP.first_name, HKP.last_name FROM `hk_arrivals` as HKA left join hk_persons AS HKP ON HKP.id = HKA.supplier_id WHERE HKA.id = $arrival";

    //   echo $getquery;

    $exe = mysqli_query($conn, $getquery);

    // $weigh_bill_number = "";
    // $billDate ="";
    // $dueAmount="";
    // $supplierName = "";
    // $supplierId = "";

    $row = mysqli_fetch_array($exe);

    //   print_r($row);

    $weigh_bill_number = $row['weigh_bill_number'];
    $billDate = $row["date"];
    $dueAmount = $row["amount_balance"];
    $supplierName = $row["first_name"]." ".$row["last_name"];
    $supplierId = $row["supplier_id"];

    

    ?>


<div class="content-wrapper">
<div class="container-fluid">

<div class="card mb-3">
  <div class="card-header">
    <h6>Arrivals Due Clear</h6>
  </div>

  <div class="card-body">
      <div class="container">

      <form action="arrivals_module/due_Clear.php" method="POST">
        <div class="row">
        <div class="col-md-2">
        <label>Date</label>
        </div>
          <div class="col-md-4">
          
          <input type="date" id="ondate" class="adddate" name="ondate" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>">
          </div>

      <div class="col-md-3">
      <label>
              Weigh Bill Number <b> <?php echo $weigh_bill_number; ?> </b>
            </label>
        </div>

          <div class="col-md-3">
            <label>
            Arrival Date : <b> <?php echo $billDate; ?> </b>
            </label>
            


          
          
          </div>
        </div>
        <hr>
        <div class="row">

          <div class="col-md-2">
          <label>Due Amount</label>
          </div>
          <div class="col-md-4">
          
          <label>
            <b>
            <u> <?php echo $dueAmount; ?> </u>
            </b>
          </label>
          
          </div>
        </div>
        
        <div class="row">
        <div class="col-md-2">
        <label> Amount Paying </label>
          </div>

            <div class="col-md-4">
                <input type="float" class="adddate" placeholder="Amount Payment" name="payAmount" value=""  />
            </div>
        </div>
        <hr>
        <div class="row">
        <div class="col-md-2">
        <label> Particulars </label>
          </div>
            <div class="col-md-4">
                
                <input type="text" class="adddate" name="particulars" placeholder="Particulars" value=""  />
            </div>
        </div>

        <div class="row">

            <div class="col-md-2 offset-10">
                <button type="submit" name="arrival" value="<?php echo $arrival; ?>" class="btn btn-primary"> SUBMIT </button>
            </div>
        </div>
      </form>


      
      </div>
  
  </div>

</div>

<div class="card mb-3">
  <div class="card-header">
    <h6>Arrivals Balance Clear List</h6>
  </div>
  <div class="card-body">
      <div class="container">
        <table class="table table-bordered table-hover table-sm">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th> Date </th>
                    <th> Amount </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $listQuery = "SELECT * FROM `hk_arrivals_transaction_map` WHERE arrival_id = $arrival";
                $exe = mysqli_query($conn, $listQuery);
                $slNo =1;
                while($row1 = mysqli_fetch_array($exe)){
                    ?>
                    <tr>
                        <td> <?php echo $slNo; ?>  </td>
                        <td> <?php echo $row1["date"]; ?>  </td>
                        <td> <?php echo $row1["amount"]; ?>  </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
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
