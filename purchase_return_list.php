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
    <link href="css/purchasereturn_list.css" rel="stylesheet">
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
          <h6>Purchase Return Details</h6>
            <button class="purcadd" onclick="myFunction()"><i class="fa fa-refresh" style="color: white;"></i></button>
            <button class="purcaddbutton"><a href="purchase_return.php" style="color: white;"> <i class="fa fa-plus"> Add Purchase Return</i></a></button>
          </div>
        <div class="card-body">

          <div class="container">

            <form  action="purchaseReturn_account_print.php" method="post">

              <div class="row">

                <div class="col-md-3">
                  <input type="date" id="date" name="ondate" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" onchange="datepick()" class="form-control">
                </div>

                <div class="col-md-3 offset-3">
   <button class="printcash" type="submit" formtarget="_blank"><a>Print Details </a></button>
                </div>

                <div class="col-md-3">
                  <div class="form-group form-disp">

                    <input type="text" id="searchInput" placeholder="Search" class="form-control">
                    <i class="fa fa-search search-icon"></i>
                  </div>
                </div>


              </div>



            </form>



         </div>
         <hr>
          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="purchaseTable" width="100%" cellspacing="0">
              <thead>
                <tr class="custtd">
                  <th>Sl No </th>
                  <th>Return Date </th>
                  <th>Return Bill No.</th>
                  <th>Supplier name</th>
                  <th>Purchase Bill No.</th>
                  <th>Product Details</th>

                  <th>Return Amount</th>
                  <th>Transaction Type</th>
                  <th>Transaction Details</th>
                  <th>Print Bill</th>

                 </tr>
              </thead>
              <tbody id="table_body">
                <?php
                             require('dbconnect.php');
                             $purchasereturnlistq ="SELECT hkpret.id,hkpret.date,hkpret.amount_recieved,
                                                    hkpret.cheque_number,hkpret.transaction_id,
                                                    hkpret.purchase_return_bill_number,hkperson.first_name,
                                                    hkperson.last_name,hkp.bill_number,
                                                    hkptt.purchase_transaction_type
                                                    FROM hk_purchases_return AS hkpret
                                                    left JOIN hk_purchases AS hkp on hkp.id = hkpret.purchase_id
                                                    left JOIN hk_persons AS hkperson ON hkperson.id = hkp.person_id
                                                    left JOIN hk_purchase_transaction_type AS hkptt ON hkptt.id = hkpret.transaction_type_id
                                                    WHERE hkpret.date = '".date("Y-m-d")."'
                                                    ORDER BY hkpret.id DESC
                                                    ";
                             $exe = mysqli_query($conn,$purchasereturnlistq);

                                 $x=0;//To show Serial numbers irrespective of Order ID
                             while($row = mysqli_fetch_array($exe)){
//
$date = strtotime($row['date']);
$date = date("d-m-Y",$date);

                             ?>

                <tr class="custtd">
                  <td scope="row"><?php

                      echo  ++$x;
                  ?></td>
                  <td><?php echo $date ?></td>
                  <td><?php echo $row['purchase_return_bill_number']; ?> </td>
                  <td><?php echo $row['first_name']." ".$row['last_name']; ?></td>
                  <td><?php echo $row['bill_number']; ?></td>



                  <td><p data-placement="top" data-toggle="tooltip" title="Product">
                        <button data-target="#productModal" data-toggle="modal" onclick="f1(this)" class="btn btn-sm btn-primary" value="<?php echo $row['id'] ; ?>"><span class="fa fa-product-hunt"></span></button>
                    </td>

                  <td><?php echo $row['amount_recieved']; ?></td>
                       <td><?php echo $row['purchase_transaction_type']; ?></td>


                       <td>
                           <p data-placement="top" data-toggle="tooltip" title="Bank"> <button data-target="#myModal" data-toggle="modal" class="btn btn-sm btn-primary" onclick="bankModalValue('<?php echo $row['cheque_number']; ?>','<?php echo $row['transaction_id']; ?>')"><span class="fa fa-university"></span></button>
                         </td>
                         <td>
                             <form method="post" action="purchase_return_bill.php">
                                 <p data-placement="top" data-toggle="tooltip" title="Print"> <button type="submit" name="print" class="btn btn-primary btn-sm member" value="<?php echo $row["id"]; ?>"><span class="fa fa-print"></span>
                               </button></p>
                             </form>

                         </td>

                </tr>
              <?php } ?>

            </tbody>
            </table>
          </div>

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

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">TRANSACTION DETAILS</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered suppliertr"  width="100%" cellspacing="0">
              <thead>
                <tr >
                  <td class="suppliertd">Cheque Number :</td>
                    <td id="check_number"></td>
                  </tr>
                  <tr>
                  <td class="suppliertd">Transaction Id :</td>
                    <td id="transaction_id"></td>
                 </tr>
<!--
                  <tr>
                      <td class="suppliertd">Bank A/C No :</td>
                      <td></td>
                  </tr>
                  <tr>
                      <td class="suppliertd">Branch Name :</td>
                      <td></td>
                  </tr> -->

<!--
                  <tr>
                      <td class="suppliertd">IFSC</td>
                      <td></td>
                  </tr>
-->
              </thead>
              <tbody>

              </tbody>
            </table>

          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <!-- <a class="btn btn-primary" href="login.html">Logout</a> -->
          </div>
        </div>
      </div>
    </div>


      <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">PRODUCT DETAILS</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="refreshtable()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered suppliertr"  width="100%" cellspacing="0">
              <thead>
                <tr class="custtd">

                 <th>Product Name</th>
                    <th>Return Quantity</th>
                    <th>Rate</th>
                    <th>Amount</th>

                  </tr>
                  </thead>
                  <tbody id="productModaldata">
                  <tr class="custtd">
                      <td id="row_11"></td>
                      <td id="row_12"></td>
                      <td id="row_13"></td>
                      <td id="row_14"></td>


                 </tr>
                  <tr class="custtd">
                      <td id="row_21"></td>
                      <td id="row_22"></td>
                      <td id="row_23"></td>
                      <td id="row_24"></td>


                 </tr>
                  <tr class="custtd">
                      <td id="row_31"></td>
                      <td id="row_32"></td>
                      <td id="row_33"></td>
                      <td id="row_34"></td>


                 </tr>
                  <tr class="custtd">
                      <td id="row_41"></td>
                      <td id="row_42"></td>
                      <td id="row_43"></td>
                      <td id="row_44"></td>


                 </tr>
</tbody>

            </table>

          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal" onclick="refreshtable()">Cancel</button>
            <!-- <a class="btn btn-primary" href="login.html">Logout</a> -->
          </div>
        </div>
      </div>
    </div>





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
    <!-- Scripts for modal-->
    <script src="js/purchasereturn_list.js"></script>
    <script src="script/purchase_return_list.js"></script>

  <script>
  function bankModalValue(check_number,transaction_id) {
//          alert(""+name+" "+accname+" "+accnum+" "+branch+" "+ifsc);
      $("#check_number").html(check_number);
      $("#transaction_id").html(transaction_id);
  }
  </script>

  <?php
        if($_SESSION['role']=='STAFF'){
            echo "<script> function staff(){
              $('.staff').attr('disabled','disabled');
              $('.member').attr('disabled','disabled');
               $('.member').removeAttr('href');
               $('.staff').removeAttr('href');
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
    <script>

function refreshtable(){
                    for(j = 1;j<5;j++){
                     $("#row_"+j+1).text("");
                     $("#row_"+j+2).text("");
                     $("#row_"+j+3).text("");
                     $("#row_"+j+4).text("");
//                     $("#row_"+j+5).text("");
//                     $("#row_"+j+6).text("");
//                     $("#row_"+j+7).text("");
                   }
          }

      </script>


<!-- search script  -->

<script type="text/javascript">

$(document).ready(function(){
$("#searchInput").on("keyup", function() {
  var value = $(this).val().toLowerCase();
  $("#purchaseTable tbody tr").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
  });
});

});
</script>

<!-- search script ends -->
</body>

</html>
<?php } ?>
