<?php
session_start();
require("logout.php");

if($_SESSION['username']==""){
    header("Location: loginn.php");
}
else{


  if($_SESSION["auth_balance"]==false){
    header("Location: admin_auth_login.php");
  }
else{



?>

<!DOCTYPE html>
<html lang="en">

<?php

function getCustomerDueList(){
require("dbconnect.php");

$print_array = array();
$print_array_index = 0;

  $query  = "SELECT id,first_name,last_name FROM  `hk_persons` WHERE person_type_id = '2' ORDER BY `first_name`";
             $exe = mysqli_query($conn,$query);
             $person_array_length = mysqli_num_rows($exe);
               $x = 0;
             while($row = mysqli_fetch_array($exe)){
               $person_array[$x]["id"] = $row['id'];
               $person_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
               $x++;
             }

for($x=0;$x<$person_array_length;$x++){
$personBal1 =0;
  $query = "SELECT SUM(cr) as credit,SUM(dr) as debit FROM hk_account_".$person_array[$x]["id"]." WHERE active = 1";
    $exe = mysqli_query($conn,$query);
    if(mysqli_num_rows($exe)==0){
      $personBal = 0;
    }
    else{
      while($row = mysqli_fetch_array($exe)){
           $personBal = $row["debit"]-$row["credit"];
        }
    }

    $query = "SELECT MAX(date) as date FROM hk_account_".$person_array[$x]["id"];
      $exe = mysqli_query($conn,$query);
        if(mysqli_num_rows($exe)==0){
          $lastDate = date("Y-m-d");
        }
        else{
          while($row = mysqli_fetch_array($exe)){
              $lastDate =$row["date"];
            }
        }

   if($personBal < 0){
    $personBal1 = $personBal  * (-1);
    $print_array[$print_array_index]["id"] = $person_array[$x]["id"];
    $print_array[$print_array_index]["customer_name"] = $person_array[$x]["customer_name"];
    $print_array[$print_array_index]["balance"] = $personBal1;
    $print_array[$print_array_index]["date"] = $lastDate;
    $print_array_index++;
  }
}
return $print_array;
}

$print_array = getCustomerDueList();
 ?>


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
    <link href="css/balance.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <?php
    require('header.php');
    ?>
  <div class="content-wrapper ">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
<!--
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Tables</li>
      </ol>
-->
      <!-- Example DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <h6 style="display: inline-block;">Customers Receivable List</h6>

         <!-- <a href="add_customer_balance.php"><button class="baladd">Add Balance</button></a> -->
          <button class="baladd" onclick="myFunction()"><i class="fa fa-refresh" style="color: white;"></i></button>
<!--            <button class="cashaddbutton"><a href="cash_book.php" style="color: white;"> <i class="fa fa-plus"> Add Cash</i></a></button>-->

          </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr class="custtd">
                  <th>Sl.No</th>
                  <th>Customer_Name</th>
                  <th>Balance Amount</th>
                  <th>Clearance</th>
                 </tr>
              </thead>
              <tbody>
                <?php
                                 $x=0;//To show Serial numbers irrespective of Order ID
                                 foreach ($print_array as $printdata) {
                                   // code...
                                   ?>
                                   <tr class="custtd"


                                   <?php
                                   $d1 = $printdata["date"];
                                   $d1=strtotime($d1);
                                   $d2=ceil(($d1-time())/60/60/24);
                                   if($d2<-30){
                                    echo "style='background:#e48989;color:#fff;'";
                                  }else{
                                    echo "style='background:#fff;color:#000;'";
                                  }

                                    ?>

                                   >
                                     <td scope="row"><?php

                                         echo  ++$x;
                                     ?></td>
                                     <td><?php echo $printdata["customer_name"]; ?></td>
                                     <td><?php echo $printdata['balance']; ?></td>
                                     <td>
                                       <form method="post" action="customer_receivable_update.php">
                                         <button name = "submit" value="<?php echo $printdata['id']; ?>" class = "baladdbutton" ><a style="color: white;">Clear Balance</a></button>
                                         </form>
                                       </td>
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
<!--
               <div id="myModal" class="smodal">

  <div class="smodal-content">
    <div class="smodal-header">
      <span class="sclose">&times;</span>
      <h2>Bank Details</h2>
    </div>
    <div class="smodal-body">
      <div class="table-responsive">
            <table class="table table-bordered suppliertr" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr >
                  <td class="suppliertd">Cheque Number :</td>
                    <td></td>
                  </tr>
                  <tr>
                  <td class="suppliertd">Transaction Id :</td>
                    <td></td>
                 </tr>

              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
    </div>

  </div>
</div>
-->
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
    <!-- Scripts for modal-->
    <script src="js/purchasereturn_list.js"></script>
      <script>
function myFunction() {
    location.reload();
}
</script>
  </div>
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
<?php }
}
 ?>
