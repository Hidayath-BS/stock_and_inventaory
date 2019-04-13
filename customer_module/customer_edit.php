
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
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="../vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="../css/sb-admin.css" rel="stylesheet">
    <link href="../css/cust_details.css" rel="stylesheet">

    <!--    search dropdown-->
    <link href="../css/select1.min.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
 <?php
    require('header.php');
    require('../dbconnect.php');
     $id = $_POST["edit"];
 ?>
  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->

        <form class="cust_line" method="post" action="customer_edit_handler.php">


            <?php
            $editQuery= "select HKP.*,HKPRT.id as person_role_type from `hk_persons` AS HKP  left join hk_person_role_type as HKPRT on HKP.person_role_type_id = HKPRT.id where HKP.id='$id'";
            $editExe = mysqli_query($conn,$editQuery);
            while($editRow = mysqli_fetch_array($editExe)){

            ?>
            <div class="row"><h5 style="margin:-8px 2px 8px 0px " ><u>Edit Customer</u></h5>
    <pre style="float:right">         							(Note: Fields with <i class="fa fa-asterisk" style="font-size:10px;color:red"></i> mark are compulsory)</pre></div>
    <div class="row">
        <div class="col-md-6">
       <label for="fname"> First Name <span class="requiredfield">*</span></label>
   <input type="text" class="name" name="firstName" value="<?php echo $editRow["first_name"]; ?>" placeholder="Customer Name" required>
            <div class="custrow"></div>
            <label for="lname" >Last Name</label>
        <input type="text"  class="lname" name="lstName" value="<?php echo $editRow["last_name"]; ?>" placeholder="Customer Surname">

           <div class="custrow"></div>




             <label for="customer type">Customer Type <span class="requiredfield">*</span></label>
         <select class="custtype" name="custType"  required >

               <?php

            $c_type_q = " SELECT * FROM `hk_person_role_type` WHERE person_type_id = 2";
            $exe = mysqli_query($conn,$c_type_q);
            while($row = mysqli_fetch_array($exe)){
            ?>
            <option value="<?php echo $row["id"]; ?>" <?=$row["id"] == $editRow['person_role_type'] ? 'selected="selected"':'' ?>><?php echo $row['person_role_type']; ?></option>

            <?php
            }
            ?>
</select>

         <div class="custrow"></div>
            <label for="phone">Mobile Number <span class="requiredfield">*</span></label>
        <input type="tel" class="phone" name="mobileNumber" placeholder="Customer Mobile No" maxlength="10" oninput="maxLengthCheckphone(this)"  onblur="checkLength(this)" onkeypress='validate(event)' value="<?php echo $editRow["mobile_number"]; ?>"  pattern="[0-9]{3}[0-9]{3}[0-9]{4}" required>


          <div class="custrow"></div>

             <label for="phone" style="margin-top: -10px;" >Landline Number</label>
        <input type="number" class="landline custtext" name="altphone" value="<?php echo $editRow["landline_number"]; ?>" placeholder="Customer Phone No">
<div class="custrow"></div>
		<label for="email" style="margin-top: -10px;" >Email Id</label>
        <input type="email" class="email custtextemail" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"  value="<?php echo $editRow["email"]; ?>" placeholder="Customer Email Id">
      </div>





     <div class="col-md-6">


        <label for="address1" >Address</label>
        <input type="text" class="address1" name="address" value="<?php echo $editRow["address_line_1"]; ?>" placeholder="Address Line 1" >
          <input type="text" class="address2" name="address2" value="<?php echo $editRow["address_line_2"]; ?>" placeholder="Line 2">

         <div class="custrow"></div>

          <label for="state">State <span class="requiredfield">*</span></label>
        <select class="custtstate" name="stateName" id='selUser' required>

            <?php
            $stateQuery = "SELECT * FROM `hk_states`";
            $stateExe = mysqli_query($conn,$stateQuery);
            while($stateRow =mysqli_fetch_array($stateExe)){
            ?>
 <option value="<?php echo $stateRow["id"]; ?>"<?=$stateRow["id"] ==   $editRow['state_id'] ? 'selected="selected"':'' ?>><?php echo $stateRow["state_name"]; ?> </option>
 <?php
            }
            ?>
</select>

        <div class="custrow"></div>
           <label for="city">City <span class="requiredfield">*</span></label>
		<select class="custtselect" name="city" id='selcity'  required>

            <?php
            $cityQuery = "SELECT * FROM `hk_cities`";
            $cityExe = mysqli_query($conn,$cityQuery);
            while($cityRow = mysqli_fetch_array($cityExe)){
            ?>
            <option class="cityoption" value="<?php echo $cityRow["id"]; ?>"<?=$cityRow["id"] ==   $editRow['city_id'] ? 'selected="selected"':'' ?>><?php echo $cityRow["city_name"]; ?></option>
            <?php
            }
            ?>
</select>
         <input type="button" class="custaddcity" value="Add City" onclick="openWin()">


        <div class="custrow"></div>

         <label for="city">Pin Code </label>

        <input type="text" class="custpincode1 custtext" name="pincode"  onkeypress='validate(event)' placeholder="Enter Pin Code" maxlength="6"   pattern="[0-9]{6}" value="<?php echo $editRow["pincode"]; ?>" >

        <div class="form-group">
          <label>Limit Amount</label>
          <input type="number" name="limit_amount" class="custtext" value="<?php echo $editRow["acc_limit"]; ?>" required="required" placeholder="Credit limit in Rs.">
        </div>

         <label style="display:none;">code</label>
         <input type="text" class="pincode" style="margin-left:5%; display:none;" name="code" value="<?php echo $id; ?>" readonly>
   </div>
  </div>

<!--
      <div class="row rowmargin" >
          <div class="col-md-6">



   </div>
          <div class="col-md-6">
        <label for="address2">address Line 2</label>

    </div>

      </div>
-->
<!--
    <div class="row rowmargin">

            <div class="col-md-6">


        </div>
         <div class="col-md-6">

    </div>

 </div>
 <div class="row" style="margin-top: -18px;">
    <div class="col-md-6">


       </div>

        <div class="col-md-6">

    </div>
</div>

    <div class="row" style="margin-top: -18px;">
        <div class="col-md-6" style="margin-top:9px;">

        </div>
    <div class="col-md-6">

    </div>

 </div>


-->

    <div class="row" style="margin-left: -155px;">
<!--
      <input type="submit"  value="Submit">
        <input type="reset" value="Cancel">
-->
        <button class="buttonsubmit" type="submit"><a >Submit</a></button>
       <a style=" text-decoration: none;"  href="../customer_list.php" class="buttonreset"><span>Cancel</span></a>
    </div>

            <?php

            }
            ?>
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
<!--
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    </div>
-->
    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Page level plugin JavaScript-->
    <script src="../vendor/datatables/jquery.dataTables.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin.min.js"></script>
    <!-- Custom scripts for this page-->
    <script src="../js/sb-admin-datatables.min.js"></script>
    <script src="../js/supplierdetails.js"></script>
   <script>
        var city = $('select.custselect option').attr('title');
        var state = $('select.custstate option:selected').val();
       if(state == city){
               switch(state){
                   case "1":
                       $('option').attr('title'=="1").show();
                       select.show();
                       break;
                   default:
                       break;
               }
       }
       else{

       }

      </script>

      <!--      search dropdown-->
      <script src="../js/jquery-3.2.1.min.js"></script>
      <script src="../js/select2.min.js"></script>

 <script>
        $(document).ready(function(){

            // Initialize select2
            $("#selUser").select2();

            // Read selected option
            $('#but_read').click(function(){
                var username = $('#selUser option:selected').text();
                var userid = $('#selUser').val();
            });
        });
        </script>
       <script>
        $(document).ready(function(){

            // Initialize select2
            $("#selcity").select2();

            // Read selected option
            $('#but_read').click(function(){
                var username = $('#selcity option:selected').text();
                var userid = $('#selcity').val();
            });
        });
        </script>

      <script>
        function openWin() {
            window.open("../add_city.php");
        }
        </script>


  </div>
</body>

</html>
<?php } ?>
