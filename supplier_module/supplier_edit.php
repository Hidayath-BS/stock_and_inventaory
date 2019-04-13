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
    <link href="../css/supplierdetails.css" rel="stylesheet">
     <link href="../css/select1.min.css" rel="stylesheet">

</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
 <?php require('header.php');

    require('../dbconnect.php');
    ?>
  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->

        <form class="cust_line" method="post" action="supplier_edit_handler.php">
            <?php
            $id = $_POST["edit"];
            $suppeditorQuery = "select HKP.*,HKPRT.id as person_role_type from `hk_persons` AS HKP  left join hk_person_role_type as HKPRT on HKP.person_role_type_id = HKPRT.id where HKP.id='$id'";
            $suppeditorExe = mysqli_query($conn,$suppeditorQuery);
            while($suppeditorRow = mysqli_fetch_array($suppeditorExe)){
            ?>


            <div class="row"> <h5 style="margin:-18px 0px 18px 0px " ><u>Edit Supplier</u></h5>
    <pre style="margin-top:-12px;margin-bottom: 2em !important;">       								 (Note: Fields with <i class="fa fa-asterisk" style="font-size:10px;color:red"></i> mark are compulsory)</pre></div>
    <div class="row supprow">
        <div class="col-md-6">
       <label for="name">First Name <span class="requiredfield">*</span></label>
   <input type="text"  class="suppname supptext" name="supplierName" value="<?php echo $suppeditorRow["first_name"]; ?>" placeholder="Supplier Name"  required>
            <div class="supprow"></div>

            <label for="address" >Last name</label>
        <input type="text" class="lname supptext" name="lastname" value="<?php echo $suppeditorRow["last_name"]; ?>" placeholder="Supplier Surname" >

           <div class="supprow"></div>

               <label for="address" >Supplier Type <span class="requiredfield">*</span></label>
<!--        <input type="text" id="address" name="address" placeholder="Your address.." >-->
         <select  class="supptype suppselect" name="supplierType" required>
             <?php
             require('../dbconnect.php');
             $supplierType_Querry = " SELECT * FROM `hk_person_role_type` WHERE person_type_id = 1";
             $supplierType_exe = mysqli_query($conn,$supplierType_Querry);
             while($supplierType_row = mysqli_fetch_array($supplierType_exe)){
             ?>
            <option value="<?php echo $supplierType_row["id"];?>"  <?=$supplierType_row["id"] == $suppeditorRow["person_role_type"] ? 'selected="selected"':'' ?>><?php echo $supplierType_row["person_role_type"]; ?></option>

             <?php } ?>


</select>


           <div class="supprow"></div>

             <label for="phone">Mobile Number <span class="requiredfield">*</span></label>
        <input type="tel"  class="suppphone supptext" name="supplierPhone" placeholder="Supplier Mobile No" maxlength="10" oninput="maxLengthCheckphone(this)"  onblur="checkLength(this)" onkeypress='validate(event)' pattern="[0-9]{3}[0-9]{3}[0-9]{4}" value="<?php echo $suppeditorRow["mobile_number"]; ?>"    required>

           <div class="supprow"></div>

            <label for="phone">Landline Number</label>
        <input type="text"  class="suppphone1 supptext" name="altPhone" value="<?php echo $suppeditorRow["landline_number"]; ?>" placeholder="Supplier Landline No"  >

            <div class="supprow"></div>
            <label for="phone"  >Email Id </label>
            <input type="email" class="suppemail supptextemail" name="email1" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"  value="<?php echo $suppeditorRow["email"]; ?>" placeholder="Supplier Email Id" value="">

      </div>
         <div class="col-md-6">
        <label for="city">Address line1</label>
        <input type="text" class="address2 supptext" name="address1" value="<?php echo $suppeditorRow["address_line_1"]; ?>" placeholder="Address Line 1" >

             <div class="supprow"></div>
             <input type="text" class="suppaddress supptext" name="address2" value="<?php echo $suppeditorRow["address_line_2"]; ?>" placeholder="Address Line 2">

                <div class="supprow"></div>

                <label for="state">State <span class="requiredfield">*</span></label>
     <select id="state" class="suppstate suppselect"  name="supplierState" required>
                   <?php
                   $stateQuery = "SELECT * from `hk_states`";
                   $stateExe = mysqli_query($conn,$stateQuery);
                   while($staterow = mysqli_fetch_array($stateExe)){
                   ?>
                   <option value="<?php echo $staterow['id']; ?>"<?=$staterow['id'] == $suppeditorRow["state_id"] ? 'selected="selected"':'' ?>><?php echo $staterow['state_name']; ?></option>
                   <?php
                   }
                   ?>
               </select>


               <div class="supprow"></div>

               <label for="city">City <span class="requiredfield">*</span></label>
 <select class="suppcity suppselect" name="supplierCity" id="selcity" required>
     <?php
     $cityQuery= "select * from  `hk_cities`";
     $cityExe = mysqli_query($conn,$cityQuery);
     while($cityRow= mysqli_fetch_array($cityExe)){
     ?>
     <option value="<?php echo $cityRow["id"]; ?>" <?=$cityRow["id"] == $suppeditorRow["city_id"] ? 'selected="selected"':'' ?>><?php echo$cityRow["city_name"]; ?></option>
     <?php
     }
     ?>
</select>
<input type="button" class="custaddcity" value="Add City" onclick="openWin()">


            <div class="supprow"></div>

                  <label for="accname">Pin Code </label>
        <input type="text" class="supppin supptext" name="pincode" value="<?php echo $suppeditorRow["pincode"]; ?>"
placeholder="Enter Pin Code" maxlength="6" onkeypress='validate(event)' pattern="[0-9]{6}">



             <input type="number" style="display:none" name="code" readonly value="<?php echo $suppeditorRow["id"]; ?>">


    </div>

 </div>

            <hr>



      <br>
        <!-- end of customer deatils-->



            <h5 style="margin:-23px 16px 30px -15px; " ><u>Bank Details</u></h5>
    <div class="row supprow">
        <div class="col-md-6">
       <label for="name">A/c Holder Name</label>
   <input type="text"  class="suppacname supptext" name="suppAccname" value="<?php echo $suppeditorRow["ac_holders_name"]; ?>"
placeholder="Enter A/c holder name">
      <div class="supprow"></div>
        <label for="city">A/c Number</label>
        <input type="number" class=" suppacno supptext" name="suppAccountno" value="<?php echo $suppeditorRow["bank_ac_number"]; ?>"
 placeholder="Enter A/C no" maxlength="20" oninput="maxLengthCheckphone(this)"  pattern="[0-9]{20}">

   <div class="supprow"></div>


          <label for="BankName">Bank Name</label>
                    <select class="suppbankname suppselect"  name="bankName">
                        <?php
                        $bankQuery = "SELECT * FROM hk_bank_name ORDER BY id DESC";
                        $bankExe=  mysqli_query($conn, $bankQuery);
                        while($bankRow = mysqli_fetch_array($bankExe)){
                        ?>
                        <option value="<?php echo $bankRow["id"]; ?>"<?=$bankRow["id"] == $suppeditorRow["bank_id"] ? 'selected="selected"':'' ?>>
			<?php echo $bankRow["bank_name"]; ?></option>
                        <?php
                        }
                        ?>
                    </select>
            </div>
            <div class="col-md-6">

        <label for="address" >Branch Name</label>
        <input type="text" class="suppbranch supptext" name="suppBranch" value="<?php echo $suppeditorRow["branch"]; ?>"
	placeholder="Enter branch name" >

                <div class="supprow"></div>
              <label for="address" >IFSC</label>
        <input type="text" class="suppifsc supptext" name="suppIfsc" value="<?php echo $suppeditorRow["ifsc_code"]; ?>"
	placeholder="Enter IFSC" maxlength="11">

    </div>

 </div>


            <div class="row suppsubmit">

<!--    <input  type="submit"  value="Submit">-->
<!--        <button class="buttonreset"><a  href="../supplier_list.php" style="color: white;text-decoration: none;">Cancel</a></button>-->


                <button class="buttonsubmit" type="submit"><a >Submit</a></button>
   <a href="../supplier_list.php" style=" text-decoration: none;" class="buttonreset">  <span >Cancel</span></a>



            </div>
            <?php
            }
            ?>
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
         <script src="../js/jquery-3.2.1.min.js"></script>
      <script src="../js/select2.min.js"></script>
        <script>
            $('input[type=text]').val (function () {
    return this.value.toUpperCase();
})
      </script>

      <script>
        $(document).ready(function(){

            // Initialize select2
            $("#state").select2();

            // Read selected option
            $('#but_read').click(function(){
                var username = $('#state option:selected').text();
                var userid = $('#state').val();

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
            window.open("../add_city.php");
        }
        </script>



  </div>
</body>

</html>
<?php } ?>
