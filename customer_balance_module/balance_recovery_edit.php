<?php
session_start();
require('../dbconnect.php');

$trackerId = $_POST["submit"];

$trackerQ = "SELECT HKTRK.*,HKP.first_name,HKP.last_name FROM `hk_balance_recovery_tracker` as HKTRK left JOIN hk_persons as HKP on HKP.id=HKTRK.person_id WHERE HKTRK.id ='$trackerId'";

// echo $trackerQ;


$trackerExe = mysqli_query($conn,$trackerQ);
while($trackerRow = mysqli_fetch_array($trackerExe)){
	$person_id = $trackerRow["person_id"];
	$cashbook_id = $trackerRow["cashbook_id"];
	$account_id = $trackerRow["account_id"]; 
	$customerName = $trackerRow["first_name"]." ".$trackerRow["last_name"];
}


// get particulars
$particularQ = "SELECT * FROM hk_account_".$person_id." WHERE id ='$account_id'";

$particularExe = mysqli_query($conn,$particularQ);
while($particularsRow = mysqli_fetch_array($particularExe)){
	$date = $particularsRow["date"];
	$particulars = $particularsRow["particulars"];
	$amount = $particularsRow["dr"]+$particularsRow["cr"];
}



?>


<!DOCTYPE html>
<html>
<head>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>HKH</title>
  <!-- Bootstrap core CSS-->
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="../vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="../css/sb-admin.css" rel="stylesheet">
<!--    <link href="css/supplier.css" rel="stylesheet">-->
    <link href="../css/balance.css" rel="stylesheet">
<style type="text/css">
  .align-left{
    text-align: left;
  }
  .align-right{
    text-align: right;
  }
  .disply-none{
  	display: none;
  }
</style>


</head>

</head>
<body class="fixed-nav sticky-footer bg-dark" id="page-top">
	<?php require('header.php');
    ?>

  <div class="content-wrapper">
    <div class="container-fluid">
    	<div class="row">
    		<div class="col-md-12">
    			<form method="post"  action="balance_recovery_edit_handler.php" class="cust_line">
    				<div class="row">
    					<div class="col-sm-6">
    						
    						<div class="align-right">
    							<label>Date</label>
    							<input type="date" name="date" value="<?php echo $date; ?>" class="datecustrecev">	
    						</div>
    						
    						<div class="align-right">
    							<label>Customer Name</label>
    							<input type="text" name="name" class="datecustrecev" 
    							value="<?php echo $customerName; ?>"/>	
    						</div>

    						<div class="align-right">
    							<label>Edit Amount</label>
    							<input type="text" name="amount" value="<?php echo $amount; ?>" class="datecustrecev">	
    						</div>

    						<div class="align-right">
    							<label>Edit Remarks</label>
    							<input type="text" name="Remarks" value="<?php echo $particulars; ?>" class="datecustrecev">	
    						</div>
    						<div class="align-right disply-none">
    							<label>Person Id</label>
    							<input type="text" name="person_id" value="<?php echo $person_id;?>"class="datecustrecev" readonly>
    						</div>

    						<div class="align-right disply-none">
    							<label>Person Account Id</label>
    							<input type="text" name="account_id" value="<?php echo $account_id;?>"class="datecustrecev" readonly>
    						</div>
    						<div class="align-right disply-none">
    							<label>Cash Book Id</label>
    							<input type="text" name="cash_id" value="<?php echo $cashbook_id;?>"class="datecustrecev" readonly>
    						</div>
    						
    						<div class="align-right">
    							<button type="submit" class="btn btn-primary"> SUBMIT</button>
    						</div>

    					</div>
    				</div>
    			</form>
    		</div>
    	</div>
    </div>
   </div>


<?php require("logout.php"); ?>

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
    <script>
    function calcDifference(){
       var due = $("#balance_amount").val();
       var paid = $("#enterd_amount").val();
       var diffrence = parseFloat(due)-parseFloat(paid);
       $("#remainingdue").val(diffrence);
    }
    </script>


</body>
</html>