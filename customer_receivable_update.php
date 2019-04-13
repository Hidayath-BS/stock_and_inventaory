<?php
require('dbconnect.php');
session_start();
$person_id = $_POST['submit'];
$query = "SELECT first_name,last_name FROM `hk_persons` WHERE id = ".$_POST['submit'];
$exe = mysqli_query($conn,$query);
$row = mysqli_fetch_array($exe);
$customer_name = $row['first_name']." ".$row['last_name'];

$query1 = "SELECT SUM(cr) as credit,SUM(dr) as debit FROM hk_account_".$person_id." WHERE `active`='1'";
$exe = mysqli_query($conn,$query1);
$row = mysqli_fetch_array($exe);
$balance_amount = ($row['debit']-$row['credit']) * (-1);

setlocale(LC_MONETARY, 'en_IN');

$date1 = $_SESSION['date'];
?>

<!DOCTYPE HTML>
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
    <link href="css/balance.css" rel="stylesheet">
<style type="text/css">
  .align-left{
    text-align: left;
  }
  .align-right{
    text-align: right;
  }
  .cust-label{
    padding: 0px;
  }
  .float-left{
    float: left;
  }
  .float-right{
    float: right;
  }
</style>

<?php

if( $_SESSION['role'] == 'MEMBER' || $_SESSION['role'] =='STAFF'){
  ?>

<style type="text/css">
  .del{
    display: none;
  }
  .edit{
    display: none;
  }
</style>

  <?php
}else{

  ?>
<style type="text/css">
  .del{
    display: block;
  }
  .edit{
    display: block;
  }
</style>
<?php
}

 ?>

</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
     <?php require('header.php');
    ?>

  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->

        <form class="cust_line" method="post" action="customer_balance_module/customer_balance_update_creation_handler.php">
              <div class="row"><h5 style="margin:-8px 2px 8px 0px " ><u>Customer Receivable Update</u></h5>
    <pre style="float:right">                                                               (Note: Fields with <i class="fa fa-asterisk" style="font-size:13px;color:red"></i> mark are compulsory)</pre></div>

    <div class="row">
        <div class="col-md-12">
          <p style="float: right;">Note: <i class="fa fa-asterisk" style="font-size:13px;color:red"></i> Only Admins can recover the balance </p>
        </div>
    </div>

<br>
    <div class="row">
        <div class="col-md-6">

            <label class="cust-label float-left" for="name">Date<span class="requiredfield">*</span></label>
       <input type="date" name = "date" class="datecustrecev float-right" value="<?php echo $date1; ?>" required>
    

       <input type="text" style="display:none" class="duetext dueamt1" name="id" id="id" value = "<?php echo $person_id; ?>" readonly>

            
      

   
      </div>

      <div class="col-md-6">
          <label class="cust-label float-left" for="name">Customer Name<span class="requiredfield">*</span></label>
                 <input type="text" class="duetext baltext dueamt1 float-right" name="customer_name" id="customer_name" value = "<?php echo $customer_name; ?>" readonly>


      </div>


      </div>

      <div class="row">
        <div class="col-md-6">
          <label class="cust-label float-left" for="name">Balance Amount<span class="requiredfield">*</span></label>
       <input type="text" class="baltext balamt1 float-right" name="balance_amount" id="balance_amount" value = "<?php echo $balance_amount; ?>" readonly>
        </div>
        <div class="col-md-6">
          <label class="cust-label float-left" for="name">Amount Paying<span class="requiredfield">*</span></label>
   <input type="text" class="baltext balamt float-right" name="enterd_amount" id="enterd_amount" onblur="calcDifference()" placeholder="Enter Amount.." required>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
           <label class="cust-label float-left" for="name">Remaining Balance<span class="requiredfield">*</span></label>
   <input type="text" class="baltext balamt2 float-right" name="remainingBalance"  id="remainingdue" readonly>

        </div>
        <div class="col-md-6">
          <label class="cust-label float-left" for="name">Remarks</label>
<input type="text" class="baltext balamt2 float-right" name="particulars"  id="particulars" >
        </div>
      </div>


      

    </div>


     <div class="row balsubmit">

      <div class="col-md-6 offset-3">
        <input  type="submit"  value="Submit" >
            <button class="buttonreset"><a href="customer_receivable_list.php" style="color: white; text-decoration: none;">Cancel  </a></button>
      </div>
        

<!--
        <button class="buttonsubmit"><a href="#" style="color: white;">Add Expense</a></button>
        <button class="buttonreset" ><a href="#" style="color: white;">Add Income</a></button>
-->
    </div>
  </form>


  <div class="row">
      <div class="col-md-12">
        <p><b>Please Note :</b> This is a list of Balance Recovered</p> 
        <table class="table">
          <thead>
            <tr>
              <th>Sl No.</th>
              <th>Date</th>
              <th class="align-left">Particulars</th>
              <th class="align-right">Dr</th>
              <th class="align-right">Cr</th>
              <th>Edit</th>
              <th class="del">Delete</th>
            </tr>
          </thead>
          <tbody>

            <?php


            function accDetails($person_id,$entry_id,$tracker_id){
              require('dbconnect.php');
              $query = "SELECT * FROM hk_account_".$person_id." WHERE id= '$entry_id'";
              $exe = mysqli_query($conn,$query);
              $details = array();

              while ($row = mysqli_fetch_array($exe)) {
                 $details["date"]=$row["date"];
                 $details["particulars"]=$row["particulars"];
                 $details["cr"] = $row["cr"];
                 $details["dr"] = $row["dr"];
                 $details["tracker_id"] = $tracker_id;
               } 

               return $details;
            }


            // get transaction from tracker
            $trackerQ = "SELECT * FROM hk_balance_recovery_tracker WHERE active=1 AND person_id = '$person_id'";
            $trackExe = mysqli_query($conn,$trackerQ);
            $rowdetails = array();
            $row = 0;
            while ($trackRow = mysqli_fetch_array($trackExe)) {
              $rowdetails[$row] = accDetails($trackRow["person_id"],$trackRow["account_id"],$trackRow["id"]);
              $row++;
            }



             ?>


<?php
$sl_no = 1;
  for($i=0;$i<count($rowdetails);$i++){
 ?>
            <tr>
              <td><?php echo $sl_no; ?></td>
              <td><?php echo $rowdetails[$i]["date"]; ?></td>
              <td class="align-left"><?php echo strtoupper($rowdetails[$i]["particulars"]); ?></td>
              <td class="align-right"><?php echo money_format("%!i", $rowdetails[$i]["dr"]) ; ?></td>
              <td class="align-right"><?php echo money_format('%!i', $rowdetails[$i]["cr"]); ?></td>
              <td>

                <form method="post" action="customer_balance_module/balance_recovery_edit.php">
                  <button type="submit" class="btn btn-primary" name="submit" value="<?php echo $rowdetails[$i]["tracker_id"];?> "> <i class="fa fa-pencil"></i> </button>
                </form>

                

              </td>
              <td>
                <form method="post" action="customer_balance_module/delete_account_entry.php">
                  <button class="btn btn-danger del" name="submit" type="submit" value="<?php echo $rowdetails[$i]["tracker_id"];?>"><i class="fa fa-trash"></i></button>
                </form>

                
              </td>

            </tr>
<?php
$sl_no++;
}
 ?>

          </tbody>

        </table>
      </div>
  </div>


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
    <?php 
    require("logout.php");
     ?>
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
    function calcDifference(){
       var due = $("#balance_amount").val();
       var paid = $("#enterd_amount").val();
       var diffrence = parseFloat(due)-parseFloat(paid);
       $("#remainingdue").val(diffrence);
    }
    </script>

  </div>
</body>

</html>
