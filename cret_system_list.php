
<?php
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
    <link href="css/cust_details.css" rel="stylesheet">
     <link href="css/cret_system_list.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <?php
    require('header.php');
    ?>
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Example DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <h6>Crate List</h6>
            <button class="custadd" onclick="reloadFunction()"><i class="fa fa-refresh"></i></button>
<!--           <button class="custaddbutton"><a href="#" style="color: white;"> <i class="fa fa-plus"> Add Customer</i></a></button>-->

          </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr class="custtd">
                  <th>Sl No</th>
                  <th>Customer Name</th>
                  <th>Number of Crates</th>
                  <th>Amount</th>

                  <th>Clearance</th>
                </tr>
              </thead>


             <tbody>

               <?php
                            require('dbconnect.php');
                            $salesreturnlistq ="SELECT HKCT.*,HKP.first_name,HKP.last_name FROM `hk_crate_tracker`
                             AS HKCT left JOIN hk_persons AS HKP ON HKP.id = HKCT.person_id WHERE HKCT.number_of_crates>0";
                            $exe = mysqli_query($conn,$salesreturnlistq);

                                $x=1;//To show Serial numbers irrespective of Order ID
                            while($row = mysqli_fetch_array($exe)){
//

                            ?>


              <tr class="custtd tablecenter">

               <td class="custtd">
                 <?php echo $x; ?>
                </td>
                 <td class="custtd">
                   <?php echo $row["first_name"]." ".$row["last_name"]; ?>
                  </td>
                  <td class="custtd">
                    <?php echo $row["number_of_crates"]; ?>
                </td>

                  <td class="custtd">
                    <?php echo $row["amount"]; ?>
                </td>
                  <td class="custtd">
                    <form  action="cret_system_entry.php" method="post">
                      <button type="submit" value="<?php echo $row["id"];?>" name="crateTrackerId" class="subcret" data-toggle="tooltip" title="View"><span class="fa fa-eye"></span></button>
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


 <script>
      function updateModalValue(deleteId, name) {

          $('#deleteModalButton').val(deleteId);
          $('#deleteModalName').html("Hey!.. "+ name +" will get deleted soon..");
      }

        function flushValues(){
            $('#deleteModalButton').val("");
        }
        function reloadFunction() {
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
<?php } ?>
