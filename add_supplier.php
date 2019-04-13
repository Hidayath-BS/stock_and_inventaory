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
    <link href="css/supplierdetails.css" rel="stylesheet">
 <link href="css/select1.min.css" rel="stylesheet">
<!--    search dropdown-->
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
 <?php require('header.php');
    ?>
  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->

        <form class="cust_line" method="post" action="supplier_module/supplier_creation_handler.php">

             <div class="row">  <h5 style="margin:-18px 0px 18px 0px " ><u>Add Supplier</u></h5>
    <pre style="margin-top:-12px;margin-bottom: 2em !important;">       								 (Note: Fields with <i class="fa fa-asterisk" style="font-size:10px;color:red"></i> mark are compulsory)</pre>
            <a href="JavaScript:window.close()"><button type="button" class="close" aria-label="Close" style="margin-right: -140px;color: #f50404;margin-top: -18px;">
  			<span aria-hidden="true">&times;</span>
			</button></a>
            </div>
    <div class="row supprow">
        <div class="col-md-6">
       <label for="name">First Name <span class="requiredfield">*</span></label>
   <input type="text"  class="suppname supptext" name="supplierName" placeholder="Supplier First Name"  required>

            <div class="supprow"></div>
            <label for="address" >Last name</label>
        <input type="text" class="lname supptext" name="lastname" placeholder="Supplier Surname" >

           <div class="supprow"></div>

               <label for="address" >Supplier Type <span class="requiredfield">*</span></label>
<!--        <input type="text" id="address" name="address" placeholder="Your address.." >-->
         <select  class="supptype suppselect" name="supplierType" required>
             <?php
             require('dbconnect.php');
             $supplierType_Querry = "SELECT * FROM `hk_person_role_type` WHERE person_type_id = 1";
             $supplierType_exe = mysqli_query($conn,$supplierType_Querry);
             while($supplierType_row = mysqli_fetch_array($supplierType_exe)){
             ?>
            <option value="<?php echo $supplierType_row["id"];?>"><?php echo $supplierType_row["person_role_type"]; ?></option>
             <?php } ?>


</select>


           <div class="supprow"></div>

             <label for="phone">Mobile Number <span class="requiredfield">*</span></label>
        <input type="tel"  class="suppphone supptext" name="supplierPhone" placeholder="Supplier Mobile No"
maxlength="10" oninput="maxLengthCheckphone(this)"  onblur="checkLength(this)" onkeypress='validate(event)' pattern="[0-9]{3}[0-9]{3}[0-9]{4}" required >


           <div class="supprow"></div>

            <label for="phone">Landline Number</label>
        <input type="number"  class="suppphone2 supptext" name="altPhone" placeholder="Supplier Landline No"  >

            <div class="supprow"></div>
            <label for="email"  >Email Id </label>
            <input type="email" class="suppemail supptextemail" name="email1" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"  placeholder="Supplier Email Id" value="" >

      </div>
         <div class="col-md-6">
        <label for="city">Address</label>
        <input type="text" class="address1 supptext" name="address1" placeholder="Address Line 1">

             <div class="supprow"></div>
             <input type="text" class="suppaddress supptext" name="address2" placeholder="Line 2">

                 <div class="supprow"></div>
                 <div class="panel-body">
                <div class="form-group">
                    <label for="title">State <span class="requiredfield">*</span></label>
                    <select name="state" class="suppstate suppselect" id="selUser" class="form-control" required>
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

               <div class="supprow"></div>
                <div class="form-group">
                    <label for="title">City <span class="requiredfield">*</span></label>
                    <select class=" suppcity suppselect" name="city" id="selcity" class="form-control" required>
                    </select>
                    <input type="button" class="custaddcity" value="Add City" onclick="openWin()">
                </div>
          </div>
           <div class="supprow"></div>

                  <label for="accname">Pin Code </label>
        <input type="text" class="supppin supptext" name="pincode" onkeypress='validate(event)' placeholder="Enter Pin Code" maxlength="6" pattern="[0-9]{6}" >

             
<!--             <input type="text" class="custtext custpincode" name="pincode"  onkeypress='validate(event)'  placeholder="Enter Pin Code" value="" maxlength="6"  pattern="[0-9]{6}" >-->


    </div>

 </div>

            <hr>



      <br>
        <!-- end of customer deatils-->



            <h5 style="margin:-23px 16px 30px -15px; " ><u>Bank Details</u></h5>
    <div class="row supprow">
        <div class="col-md-6">
       <label for="name">A/c Holder Name</label>
   <input type="text"  class="suppacname supptext" name="suppAccname" placeholder="Enter A/c holder name" >
     <div class="supprow"></div>
        <label for="city">A/c Number</label>
        <input type="number" class=" suppacno supptext" name="suppAccountno" placeholder="Enter A/C no" maxlength="20"
oninput="maxLengthCheckphone(this)"  pattern="[0-9]{20}" >
  <div class="supprow"></div>


                    <label for="BankName">Bank Name</label>
                    <select class="suppbankname suppselect"  name="bankName">

                        <?php
                        $bankQuery = "SELECT * FROM hk_bank_name ORDER BY id DESC";
                        $bankExe=  mysqli_query($conn, $bankQuery);
                        while($bankRow = mysqli_fetch_array($bankExe)){
                        ?>
                        <option value="<?php echo $bankRow["id"]; ?>"><?php echo $bankRow["bank_name"]; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>





           <div class="col-md-6">
        <label for="address" >Branch Name</label>
        <input type="text" class="suppbranch supptext" name="suppBranch" placeholder="Enter branch name">

         <div class="supprow"></div>
              <label for="address" >IFSC</label>
        <input type="text" class="suppifsc supptext" name="suppIfsc" placeholder="Enter IFSC" maxlength="11">
    </div>

  </div>


            <div class="row suppsubmit">
<!--<input  type="submit"  value="Submit">-->
<!--<button class="buttonreset"><a  href="supplier_list.php" style="color: white;text-decoration: none;">Cancel</a></button>-->
    
         <button class="buttonsubmit" type="submit"><a >Submit</a></button>
   <a href="supplier_list.php" style=" text-decoration: none;" class="buttonreset">  <span >Cancel</span></a>   
            
            
            </div>

      </form>
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
            $('input[type=text]').val (function () {
    return this.value.toUpperCase();
})
      </script>
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
        $(document).ready(function(){

            // Initialize select2
            $("#selbank").select2();

            // Read selected option
            $('#but_read').click(function(){
                var username = $('#selbank option:selected').text();
                var userid = $('#selbank').val();

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
