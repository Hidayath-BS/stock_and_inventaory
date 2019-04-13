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

<!--    Tab css-->
 <link href="css/order_list.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
    <?php
    require('header.php');
    ?>
    <div class="content-wrapper">
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
    <a>Stock List</a>

             <button class="addbutton" onclick="myFunction()"><i class="fa fa-refresh"></i></button>
        <a href="add_stock.php"> <button class="addbutton"> <i class="fa fa-plus"> Add Stocks</i></button></a> </div>
        <div class="card-body1">

<!--  Tab start       -->

        <section id="tabs">
	<div class="container">
<!--		<h6 class="section-title h1">Tabs</h6>-->
		<div class="row">
			<div class="col-lg-12 ">


				<div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">

				<!--First Tab-->
					<div class="tab-pane fade show active" id="nav-contact" role="tabpanel" aria-labelledby="nav-home-tab">
						<div class="table-responsive">

             			   <table class="table table-bordered table-hover" id="dataTable1" width="100%" cellspacing="0">
              <thead>
                <tr class="custtd">
                  <th>Sl No</th>
                  <th>Product Name</th>
                    <th>Current Stock</th>

                 </tr>
              </thead>

              <tbody>
                <?php
                             require('dbconnect.php');
                             $orderlistq ="SELECT HKSTOCKS.*,HKP.name,HKP.type,HKP.quantity_type
                             FROM hk_stocks AS HKSTOCKS
                             left JOIN hk_products AS HKP ON HKP.id = HKSTOCKS.product_id";
                             $exe = mysqli_query($conn,$orderlistq);

                                 $x=0;//To show Serial numbers irrespective of Order ID
                             while($row = mysqli_fetch_array($exe)){
                             ?>
           <tr class="custtd">
                  <td><?php echo ++$x;?></td>
                  <td><?php echo $row['name']." ".$row['type']." ".$row['quantity_type']?></td>
               <td><?php echo $row['quantity'];?></td>


                </tr>
              <?php } ?>


              </tbody>
            </table>
          			</div>
					</div>

				</div>

			</div>
		</div>
	</div>
</section>

<!--  Tab Ends       -->

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
      <!-- delete modal   -->

      <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Do you want to delete?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body" id="deleteModalName">Please confirm..</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal" onclick="flushValues()">Cancel</button>
<!--            <a class="btn btn-primary" href="login.html">Logout</a>-->
               <form method="post" action="php_form_handler/order_delete_handler.php">
                        <button class="btn btn-default" type="submit" name="delete" id="deleteModalButton" value="">DELETE</button>
                                        </form>
          </div>
        </div>
      </div>
    </div>
<!-- delete modal   -->

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
      function deleteModalvalue(deleteId, name){
         $('#deleteModalButton').val(deleteId);
          $('#deleteModalName').html("Hey!.. "+ id +" will get deleted soon..");
    }
      function flushValues(){
            $('#deleteModalButton').val("");
     }</script>
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

<!-- Go back when click on cancel-->


  </div>
</body>

</html>
<?php } ?>
