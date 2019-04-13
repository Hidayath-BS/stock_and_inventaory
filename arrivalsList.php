<?php
session_start();
require("logout.php");

if($_SESSION['username']==""){
    header("Location: loginn.php");
}
else{
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
  <link href="css/sb-admin.css" rel="stylesheet">
    <link href="css/cash.css" rel="stylesheet">

    <style>
    .red{
        background:red !important;
        color:white;
    }

    .display{
      display: block;
    }
    .displayNone{
      display: none !important;
    }
    .yellow{
        background: yellow !important;
        color: black;
    }
    .green{
        background: green !important;
        color: white !important;
    }
    
    </style>
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <?php
    require('header.php');
    ?>
  <div class="content-wrapper ">
    <div class="container-fluid">
      <!-- Breadcrumbs-->

      <div class="card mb-3">
        <div class="card-header">
          <h6>Arrivals List</h6>

          <button class="cashadd" onclick="myFunction()"><i class="fa fa-refresh" style="color: white;"></i></button>
            <button class="cashaddbutton"><a href="arrivalsentry.php" class="staff" style="color: white;"> <i class="fa fa-plus"> Add Arrivals</i></a></button>

          </div>


        <div class="card-body">

          <div class="container">

            <div class="row">



              <div class="col-md-3">
                <input type="date" id="date" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" onchange="datepick()" name="ondate" class="form-control">

              </div>

              <div class="col-md-3 offset-3">
                <!-- <button class="printcash" type="submit"><a>Print Today's Transaction </a></button> -->
              </div>

              <div class="col-md-3">
                <div class="form-group form-disp">

                  <input type="text" id="myInput" placeholder="Search" class="form-control">
                  <i class="fa fa-search search-icon"></i>
                </div>

              </div>

            </div>
            
          </div>
          <hr>
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm" id="cashTable"  width="100%" cellspacing="0">
              <thead>
                <tr class="custtd">
                  <th>Sl.No</th>
                  <th>Date</th>
                  <th>Weigh Bill Number</th>
                  <th>Supplier Name</th>
                  <th>Rate</th>
                  <th>Quantity</th>
                  <th>Advance</th>
                  <th>Payment </th>

                 </tr>
              </thead>
              <tbody id="table_body">
                <?php


                             $query = "SELECT AKA.*, HKP.first_name, HKP.last_name FROM `hk_arrivals` AS AKA left JOIN hk_persons AS HKP ON HKP.id = AKA.supplier_id WHERE AKA.date = '".date("Y-m-d")."' && AKA.active = 1";
                             
                             $exe = mysqli_query($conn,$query);

                            $sl_no =1;
                             while($row= mysqli_fetch_array($exe)){

                                $bgclass = "";
                                $btnClass = "";
                                if($row["payment_status"]==0){
                                    $bgclass = "red";
                                    $btnClass = "display";
                                }else{
                                    if($row["payment_status"]==1){
                                        $bgclass = "yellow";
                                        $btnClass = "display";
                                    }else{
                                        $bgclass = "green";
                                        $btnClass = "displayNone";
                                    }
                                }

                    ?>

                                 <tr class="custtd  <?php echo $bgclass; ?>">
                                    <td> <?php echo $sl_no; ?> </td>
                                    <?php
                                        $date = strtotime($row['date']);

                                    ?>
                                    <td> <?php echo date("d-m-Y",$date); ?> </td>
                                    <td> <?php echo $row["weigh_bill_number"]; ?> </td>
                                    <td> <?php echo $row["first_name"]." ".$row["last_name"]; ?> </td>
                                    <td> <?php echo $row["rate"]; ?> </td>
                                    <td> <?php echo $row["quantity"]; ?> </td>
                                    <td> <?php echo $row["advance"]; ?> </td>
                                    <td>
                                      <form action="arrivalsDueClear.php" method="GET" class="<?php echo $btnClass; ?>">
                                        <button class="btn btn-primary" name="arrival" value="<?php echo $row["id"]; ?>" >PAY </button>
                                      </form> 
                                    </td>
                                 </tr>
                 <?php
                                 $sl_no++;
                             }

                      ?>       



                         



            </tbody>
            </table>
          </div>

        </div>



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
    <!-- Scripts for modal-->
    <script src="js/purchasereturn_list.js"></script>
    <script>
function myFunction() {
   location.reload();
}
</script>

<?php
      if($_SESSION['role']=='STAFF'){
          echo "<script> function staff(){
            $('.staff').attr('disabled','disabled');
            $('.member').attr('disabled','disabled');
             $('.member').removeAttr('href');
             $('.staff').removeAttr('href');
          }
          staff();
        </script>";
      }elseif($_SESSION['role']=='MEMBER'){
          echo "<script>
                    function member(){
                    $('.staff').attr('disabled','disabled');
                    }
            member();
                </script>";
      }


      ?>

<script type="text/javascript" src="script/arrivalslist.js">

</script>


<script type="text/javascript">

$(document).ready(function(){
$("#myInput").on("keyup", function() {
  var value = $(this).val().toLowerCase();
  $("#cashTable tbody tr").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
  });
});

});
</script>


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

  </div>
</body>

</html>
<?php } ?>
