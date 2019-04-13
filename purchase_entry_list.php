<?php
session_start();
require("logout.php");

if($_SESSION['username']==""){
    header("Location: loginn.php");
}
else{
  require('dbconnect.php');
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
  <link href="css/purchaseentrylist.css" rel="stylesheet">

  <style media="screen">
    /* .staffdisplay{
      display: unset;
    } */
  </style>

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
          <h6>Purchase Entry List</h6>
            <button class="puradd" onclick="myFunction()"><i class="fa fa-refresh" style="color: white;"></i></button>
            <button class="puraddbutton"><a href="purchase_entry.php" style="color: white;"> <i class="fa fa-plus"> Add Purchase Entry</i></a></button>
          </div>
        <div class="card-body">

          <div class="container">
            <form class="" action="purchase_account_print.php" method="post">


            <div class="row">

              <div class="col-md-3">
                <input type="date" id="date" name="ondate" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" onchange="datepick()" class="form-control">

              </div>
              <div class="col-md-3 offset-3">
<button class="printcash" type="submit" formtarget="_blank"><a>Print Details </a></button>
              </div>
              <div class="col-md-3">
                <div class="form-group form-disp">

                  <input type="text" id="myInput" placeholder="Search" class="form-control">
                  <i class="fa fa-search search-icon"></i>
                </div>

              </div>

            </div>
</form>
          </div>

<hr>
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm" id="purchaseTable" width="100%" cellspacing="0">
              <thead>
                <tr style="font-size: 14px;">
                  <th>Sl No</th>
                  <th>Supplier Name </th>
                  <th>Bill No</th>
                  <th>Bill Date</th>
                  <th>Transaction Type</th>

                  <th>Location</th>
                     <th>Transaction Details</th>
				   <th>Product Details</th>
                    <th>Print Bill</th>
                    <th >EDIT</th>
                  <th class="staffdisplay" >Delete</th>
                 </tr>
              </thead>



              <tbody id="table_body">
<?php


                    $slNo=1;
    $purchaseQuery = "SELECT hkp.first_name,hkp.last_name,hkpu.bill_number,hkpu.bill_date,hkptt.purchase_transaction_type,hkpu.net_weight,hkpu.amount_payable,hkpu.amount_paid, hkpu.cheque_number,hkpu.transaction_id,hkpu.location,hkpu.id, hkpu.paid_to FROM hk_purchases as hkpu left JOIN hk_persons as hkp on hkp.id = hkpu.person_id
left JOIN hk_purchase_transaction_type as hkptt on hkptt.id = hkpu.purchase_transaction_type_id where hkpu.purchases_active = 1 AND hkpu.bill_date='".date("Y-m-d")."' ORDER BY hkpu.id DESC";
              $purchaseExe = mysqli_query($conn,$purchaseQuery);
              while($purchaseRow = mysqli_fetch_array($purchaseExe)){
                $date = strtotime($purchaseRow["bill_date"]);
                $date = date("d-m-Y",$date);
    ?>
                <tr style="font-size: 14px;">
                  <td><?php echo $slNo;  ?></td>
                  <td><?php echo $purchaseRow["first_name"]." ".$purchaseRow["last_name"] ;  ?></td>

                  <td><?php echo $purchaseRow["bill_number"];?></td>
                  <td><?php echo $date;?></td>
                  <td><?php echo $purchaseRow["purchase_transaction_type"]; ?></td>

                     <td><?php echo $purchaseRow["location"];?></td>
                    <td>

                        <p data-placement="top" data-toggle="tooltip" title="Bank"> <button data-target="#transactionModal" data-toggle="modal" class="btn btn-primary btn-sm " onclick="transmodal('<?php echo $purchaseRow["amount_payable"]; ?>','<?php echo $purchaseRow["amount_paid"]; ?>','<?php echo $purchaseRow["cheque_number"]; ?>','<?php echo $purchaseRow["transaction_id"]; ?>','<?php echo $purchaseRow["paid_to"]; ?>')"><span class="fa fa-university"></span></button></p>
                    </td>


				  <td>

                      <p data-placement="top" data-toggle="tooltip" title="Product">  <button value="<?php echo $purchaseRow["id"]; ?>" onclick="f1(this)" data-target="#myModal" data-toggle="modal" class="btn btn-primary btn-sm" ><span class="fa fa-product-hunt"></span></button></p>

 
                    </td>
                    <td>
                        <form method="post" action="purchase_bill.php">
                            <p data-placement="top" data-toggle="tooltip" title="Print"> <button type="submit" name="print" formtarget="_blank" class="btn btn-primary btn-sm member" value="<?php echo $purchaseRow["id"]; ?>"><span class="fa fa-print"></span>
                          </button></p>
                        </form>

                    </td>
                    <td >
                        <form method="post" action="purchase_entry_module/purchase_entry_edit.php">
                            <p data-placement="top" data-toggle="tooltip" title="Edit"> <button type="submit" name="edit" class="btn btn-primary btn-sm member" value="<?php echo $purchaseRow["id"]; ?>"><span class="fa fa-pencil"></span>
                          </button></p>
                        </form>

                    </td>


                    <td class="staffdisplay" >
                        <p data-placement="top" data-toggle="tooltip" title="Delete">    <button   class="btn btn-danger btn-sm staff"  name="delete" data-toggle="modal" data-target="#deleteModal" onclick="deleteModalvalue(<?php echo $purchaseRow["id"];?>,'<?php echo $purchaseRow["first_name"]; ?>')"><span class="fa fa-trash" ></span></button></p>
                    </td>
                </tr>

    <?php
                  $slNo++;
                  }
                  ?>
                  <!--
                <tr>
                  <td>Garrett Winters</td>
                  <td>Accountant</td>
                  <td>Tokyo</td>
                  <td>63</td>
                  <td>2011/07/25</td>
                  <td>61</td>
                  <td>2011/04/25</td>

                </tr>
-->



              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
<!--
               <div id="myModal" class="smodal">

  <div class="smodal-content">
    <div class="smodal-header">
      <span class="sclose">&times;</span>
      <h2>Quantity Details</h2>
    </div>
    <div class="smodal-body">
      <div class="table-responsive">
            <table class="table table-bordered suppliertr" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr >
                  <td class="suppliertd">Supplier Name :</td>
                    <td></td>
                  </tr>
                  <tr>
                  <td class="suppliertd">Empty Weight :</td>
                    <td></td>
                 </tr>
                  <tr>
                      <td class="suppliertd">Loaded Weight :</td>
                      <td></td>
                  </tr>
                  <tr>
                      <td class="suppliertd">Net Weight :</td>
                      <td></td>
                  </tr>
                  <tr>
                      <td class="suppliertd">Shrink :</td>
                      <td></td>
                  </tr>
                   <tr>
                      <td class="suppliertd">Final Quantity :</td>
                      <td></td>
                  </tr>
                   <tr>
                      <td class="suppliertd">Unit Price :</td>
                      <td></td>
                  </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
    </div>

  </div>
</div>
-->

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
            <h5 class="modal-title" id="exampleModalLabel">Product Details</h5>
            <button class="close" type="button" data-dismiss="modal" onclick="refreshtable()" aria-label="Close">
              <span aria-hidden="true">&times</span>
            </button>
          </div>
            <div class="modal-body">
                <div class="table-responsive">

            <table class="table table-bordered suppliertr" width="100%" cellspacing="0">
              <thead>
                <tr class="custtd">

                 <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Shrink</th>
                    <th>Final Quantity</th>
                    <th>Unit Price</th>
                    <th>Amount</th>

                  </tr>

                   </thead>

                     <tbody id="table_body_modal">
                  <tr class="custtd">
                      <td id="row_11"></td>
                      <td id="row_12"></td>
                      <td id="row_13"></td>
                      <td id="row_14"></td>
                      <td id="row_16"></td>
                      <td id="row_17"></td>

                 </tr>
                  <tr class="custtd">
                      <td id="row_21"></td>
                      <td id="row_22"></td>
                      <td id="row_23"></td>
                      <td id="row_24"></td>
                      <td id="row_26"></td>
                      <td id="row_27"></td>

                 </tr>
                  <tr class="custtd">
                      <td id="row_31"></td>
                      <td id="row_32"></td>
                      <td id="row_33"></td>
                      <td id="row_34"></td>
                      <td id="row_36"></td>
                      <td id="row_37"></td>

                 </tr>
                  <tr class="custtd">
                      <td id="row_41"></td>
                      <td id="row_42"></td>
                      <td id="row_43"></td>
                      <td id="row_44"></td>
                      <td id="row_46"></td>
                      <td id="row_47"></td>

                 </tr>




              </tbody>
            </table>

                </div>


            </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" onclick="refreshtable()" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>





<!--  transaction Modal    -->


      <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Transaction Details</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times</span>
            </button>
          </div>
            <div class="modal-body" id="deleteModalName">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Amount Payable</th>
                                <td id="payable"></td>
                            </tr>
                            <tr>
                                <th>Amount Paid</th>
                                <td id="paid"></td>
                            </tr>
                            <tr>
                                <th>Cheque Number</th>
                                <td id="cheque"></td>
                            </tr>
                            <tr>
                                <th>Transacion Id</th>
                                <td id="transid"></td>
                            </tr>
                            <tr>
                                <th>Paid To</th>
                                <td id="paidto"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>


            </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>




      <script>
        function  transmodal(payable,paid,cheque,transid,paidTo){
            $("#payable").text(payable);
            $("#paid").text(paid);
            $("#cheque").text(cheque);
            $("#transid").text(transid);
            $("#paidto").text(paidTo);
        }

      </script>













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
               <form method="post" action="purchase_entry_module/purchase_entry_delete_handler1.php">
                        <button class="btn btn-default" type="submit" name="delete" id="deleteModalButton" value="">DELETE</button>
                </form>
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
    <script src="js/supplier_list.js"></script>

<!--   script for ajax purchased products   -->
<script type="text/javascript" src="script/purchasedProducts.js"></script>


      <script>
      function bankModalValue(empty,loaded,net,shrink,final,unitprice) {
//          alert(""+name+" "+accname+" "+accnum+" "+branch+" "+ifsc);
          $("#name").html(empty+" TONNE");
          $("#acname").html(loaded+" TONNE");
          $("#accnum").html(net+" Kg");
          $("#branch").html(shrink+" Kg");
          $("#ifsc").html(final+" Kg");
          $("#unitprice").html("Rs "+unitprice);
      }
       function deleteModalvalue(deleteId, name){
         $('#deleteModalButton').val(deleteId);
          $('#deleteModalName').html("Hey!.. "+ name +" will get deleted soon..");
    }

    function flushValues(){
            $('#deleteModalButton').val("");
     }
      </script>
      <script>
  function myFunction() {
      location.reload();
  }</script>

      <script>
          function refreshtable(){
                    for(j = 1;j<5;j++){
                     $("#row_"+j+1).text("");
                     $("#row_"+j+2).text("");
                     $("#row_"+j+3).text("");
                     $("#row_"+j+4).text("");
                     $("#row_"+j+5).text("");
                     $("#row_"+j+6).text("");
                     $("#row_"+j+7).text("");
                   }
          }

      </script>



  <?php
        if($_SESSION['role']=='STAFF'){
            echo "<script> function staff(){
              $('.staff').attr('disabled','disabled');
              $('.member').attr('disabled','disabled');
               $('.member').removeAttr('href');
               $('.staff').removeAttr('href');
               $('.staffdisplay').css('display','none');
            }
            staff();


          </script>";

          echo "<style>
            .staffdisplay{
              display:none;
            }
          </style>";



        }elseif($_SESSION['role']=='MEMBER'){
            echo "<script>
                      function member(){
                      $('.staff').attr('disabled','disabled');
                      $('.staffdisplay').css('display','none');
                      }
              member();
                  </script>";

                  echo "<style>
                    .staffdisplay{
                      display:none;
                    }
                  </style>";

        }


        ?>


<script type="text/javascript" src="script/purchase_entry_list_data.js"></script>

<script type="text/javascript">

$(document).ready(function(){
$("#myInput").on("keyup", function() {
  var value = $(this).val().toLowerCase();
  $("#purchaseTable tbody tr").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
  });
});

});
</script>

  </div>
</body>

</html>
<?php } ?>
