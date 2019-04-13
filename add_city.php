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
<!--    <link href="css/supplier.css" rel="stylesheet">-->
    <link href="css/addcity.css" rel="stylesheet">
     <link href="css/select1.min.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
     <?php require('header.php');
    ?>

  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->

        <form class="cust_line" method="post" action="city_module/city_creation_handler.php">
             <div class="row"><h5 style="margin:-8px 2px 8px 0px " ><u>Add City</u></h5>

    		 <pre style="float:right">                                                     (Note: Fields with <i class="fa fa-asterisk" style="font-size:10px;color:red"></i> mark are compulsory)</pre>
    		<a href="JavaScript:window.close()"><button type="button" class="close" aria-label="Close" style="margin-left:50px;color:darkred;">
  			<span aria-hidden="true">&times;</span>
			</button></a>
    </div>
<!--            <h5 style="margin:-18px 0px 18px 0px " ><u>Add City</u></h5>-->
    <div class="row" style="margin-top:-18px;">
        <div class="col-md-6">

       <label for="state">State<span class="requiredfield">*</span></label>
             <select name = "state_id" id="state" class="cityselect cityamt1" name="type">
               <?php
                require('dbconnect.php');
                $u_state_q = " SELECT * FROM `hk_states`";
                $exe = mysqli_query($conn,$u_state_q);
                while($row = mysqli_fetch_array($exe)){
                ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['state_name']; ?></option>
                <?php
                }
                ?>
</select>

            <div class="row cityrow"></div>
       <label for="name">City<span class="requiredfield">*</span></label>
   <input type="text" class="citytext cityamt" name="city" placeholder="Enter City" required>


      </div>
    </div>


     <div class="row citysubmit">


<!--
        <input  type="submit"  value="Submit" >
       <button class="buttonreset"><a href="" style="color: white; text-decoration: none;">Cancel  </a></button>
-->


         <button class="buttonsubmit" type="submit"><a >Submit</a></button>
         <button type="reset" style=" text-decoration: none;" class="buttonreset">  <span >Cancel</span></button>
<!--
        <button class="buttonsubmit"><a href="#" style="color: white;">Add Expense</a></button>
        <button class="buttonreset" ><a href="#" style="color: white;">Add Income</a></button>
-->
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
      <script src="js/jquery-3.2.1.min.js"></script>
      <script src="js/select2.min.js"></script>

 <script>
        $(document).ready(function(){

            // Initialize select2
            $("#state").select2();

            // Read selected option
            $('#but_read').click(function(){
                var username = $('#state option:selected').text();
                var userid = $('#state').val();
            });
        });
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
<?php
} ?>
