
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
<!--    search dropdown-->
    <link href="css/select1.min.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <?php
  require('header.php');

  ?>
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- customer details-->

      <form class="cust_line" method="post" action="customer_module/customer_creation_handler.php">
        <div class="row"><h5 style="margin:-8px 2px 8px 0px " ><u>Add Customer</u></h5>
    <pre style="float:right">       								 (Note: Fields with <i class="fa fa-asterisk" style="font-size:10px;color:red"></i> mark are compulsory)</pre></div>
        <div class="row">
          <div class="col-md-6">
            <label for="fname"> First Name <span class="requiredfield">*</span></label>
            <input type="text" class="name custtext" name="firstName" placeholder="Customer name" required>
            <div class="custrow"></div>
            <label for="lname" >Last Name</label>
            <input type="text"  class="lname custtext" name="lstName" placeholder="Customer surname">

            <div class="custrow"></div>
            <label for="customer type">Customer Type <span class="requiredfield">*</span></label>
            <select class="custtype" name="custType" required>

              <?php
              require('dbconnect.php');
              $c_type_q = " SELECT * FROM `hk_person_role_type` WHERE person_type_id = 2";
              $exe = mysqli_query($conn,$c_type_q);
              while($row = mysqli_fetch_array($exe)){
                ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['person_role_type']; ?></option>
                <?php
              }
              ?>
            </select>

            <div class="custrow"></div>
            <label for="phone">Mobile Number <span class="requiredfield">*</span></label>
            <input type="tel" class="phone" name="mobileNumber" placeholder="Customer Mobile No" maxlength="10" oninput="maxLengthCheckphone(this)"  onblur="checkLength(this)" onkeypress='validate(event)' pattern="[0-9]{3}[0-9]{3}[0-9]{4}" required>


            <div class="custrow"></div>
            <label for="phone"  >Landline Number </label>
            <input type="number" class="phone1 custtext" name="altphone" placeholder="Customer Landline No" value="">

          	<div class="custrow"></div>
            <label for="phone"  >Email Id </label>
            <input type="email" class="email custtextemail" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" placeholder="Customer Email Id" value="">

          </div>

          <div class="col-md-6">


            <label for="address1" >Address</label>
            <input type="text" class="address1 custtext" name="address" placeholder="Address Line 1" >
            <input type="text" class="address2 custtext" name="address2" placeholder="Line 2">

            <div class="custrow"></div>
            <div class="panel-body">
           <div class="form-group">
               <label for="title">State <span class="requiredfield">*</span></label>
               <select name="state" class="custstate" class="form-control" id='selUser' required>
                   <option value="">--- Select State ---</option>



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
          </div>

          <div class="custtrow"></div>
           <div class="form-group">
               <label for="title">City <span class="requiredfield">*</span></label>
               <select class="custselect " id='selcity' name="city" class="form-control" required >
               </select>
                <input type="button" class="custaddcity" value="Add City" onclick="openWin()">
           </div>
     </div>

            <div class="custtrow"></div>

            <label for="city">Pin Code </label>
            <input type="text" class="custtext custpincode" name="pincode"  onkeypress='validate(event)'  placeholder="Enter Pin Code" value="" maxlength="6"  pattern="[0-9]{6}" >
            <br>

            
            <div class="form-group">
              <label>Credit Limit</label>
              <input type="number" name="cred_limit" class="custtext" required="required" placeholder="Credit limit in Rs.">
            </div>

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


<div class="row" style="margin-left: -155px;">
  <!--
  <input type="submit"  value="Submit">
  <input type="reset" value="Cancel">
-->
<button class="buttonsubmit" type="submit"><a >Submit</a></button>
   <a href="customer_list.php" style=" text-decoration: none;" class="buttonreset">  <span >Cancel</span></a>


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

<!--      search dropdown-->
      <script src="js/jquery-3.2.1.min.js"></script>
      <script src="js/select2.min.js"></script>
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
<script>
$( "select[name='state']" ).change(function () {
    var stateID = $(this).val();


    if(stateID) {


        $.ajax({
            url: "ajaxpro.php",
            dataType: 'Json',
            data: {'id':stateID},
            success: function(data) {
              console.log(data);
                $('select[name="city"]').empty();
                $.each(data, function(key, value) {
                    $('select[name="city"]').append('<option value="'+ key +'">'+ value +'</option>');
                });
            }
        });


    }else{
        $('select[name="city"]').empty();
    }
});


</script>

      <script>
        $(document).ready(function(){

            // Initialize select2
            $("#selUser").select2();

            // Read selected option
            $('#but_read').click(function(){
                var username = $('#selUser option:selected').text();
                var userid = $('#selUser').val();

//                $('#result').html("id : " + userid + ", name : " + username);
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

//                $('#result').html("id : " + userid + ", name : " + username);
            });
        });
        </script>
        <script>
        function openWin() {
            window.open("add_city.php");
        }
        </script>
</div>
</body>

</html>
<?php } ?>
