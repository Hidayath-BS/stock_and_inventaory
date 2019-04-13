
<?php
session_start();
require("logout.php");

if($_SESSION['username']==""){
    header("Location: loginn.php");
}
else{
  require("dbconnect.php");
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

      <form class="cust_line" method="post" action="customer_balance_module/customer_addbalance_handler.php">
        <div class="row"><h5 style="margin:-8px 2px 8px 0px " ><u>Add Customer Balance</u></h5>
    <pre style="float:right">       								 (Note: Fields with <i class="fa fa-asterisk" style="font-size:10px;color:red"></i> mark are compulsory)</pre></div>
        <div class="row">

          <div class="col-md-6" >

            <?php
            $customerQ = "SELECT id,first_name,last_name FROM hk_persons WHERE person_type_id=2";

            // select only customers form persons table
            $customerExe = mysqli_query($conn,$customerQ);


             ?>

             <div class="form-group">
               <label>Select customer Here:</label>
               <select  name="customer_id" class="form-control" id='selUser' required="required">
                <option> --- select Customer --- </option>
                 <?php
                 while ($customerRow = mysqli_fetch_array($customerExe)) {
                 ?>

                 <option value="<?php echo $customerRow["id"]; ?>"> <?php echo $customerRow["first_name"]." ".$customerRow["last_name"]; ?> </option>

                 <?php
               }
                 ?>

               </select>
             </div>
             <div class="form-group">
               <label>Particulars*</label>
               <input type="text" name="particulars" class="m-25" placeholder="Particulars" required="required">
             </div>
          </div>

          <div class="col-md-6">


            <div class="form-group">
              <label>Date*</label>
              <input type="date" name="date" class="m-25"  required="required">
            </div>
            <div class="form-group">
              <label>Enter Amount*</label>
              <input type="number" name="amount" class="m-25" placeholder="Please enter balance amount" required="required">
            </div>


          </div>

        </div>



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
