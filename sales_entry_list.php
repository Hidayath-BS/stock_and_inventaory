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
  <link href="css/salesentry.css" rel="stylesheet">

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
          <h6>Sales Entry List</h6>
            <button class="salesaddbutton"><a href="sales_entry_direct.php" style="color: white;"> <i class="fa fa-plus"> Direct Sales Entry</i></a></button>
            <button class="salesadd" onclick="myFunction()"><i class="fa fa-refresh" style="color: white;"></i></button>
            <button class="salesaddbutton" hidden><a href="order_list.php" style="color: white;"> <i class="fa fa-plus"> View Orders</i></a></button>

          </div>
        <div class="card-body">


           <div class="container">
<form  action="sales_account_print.php" method="post">

  <div class="row">

    <div class="col-md-3">
      <input type="date" id="date"  name="ondate" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" onchange="datepick()" class="form-control">
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



          <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm" id="salesTable1" width="100%" cellspacing="0">
              <thead>
                <tr class="custtd">
                  <th>Sl No</th>
                  <th>Supplier Name </th>
                  <th>Bill No</th>
                  <th>Bill Date</th>
                  <th>Sales Transaction Type</th>
                 <!-- <th>Vehicle No</th>
                  <th>Weight Bill No</th> -->
<!--                  <th>Quantity Details</th>-->
                      <th>Driver PhoneNumber</th>
                    <th>Transaction Details</th>
<!--
                  <th>Total Amount</th>
                    <th> Transaction Id</th>
                  <th>Cheque No</th>

-->

				   <th>Product Details</th>
           <th>Print Bill</th>
           <th>Print Bill without balance</th>
                    <th >Edit</th>

                  <th class="staffdisplay">Delete</th>

                 </tr>
              </thead>


<!--
              <tfoot>
                <th>Customer Code</th>
                  <th>Customer Name</th>
                  <th>Customer Type</th>
                  <th>Address</th>
                  <th>City</th>
                  <th>State</th>
                  <th>Phone Number</th>
                  <th>Alternative No</th>
              </tfoot>
-->
              <tbody id="table_body">
<?php

                  require('dbconnect.php');
                    $slNo=1;
    $salesQuery = "SELECT hkp.first_name,hkp.last_name,hks.bill_number,hks.bill_date,
                   hkstt.sales_transaction_type,hks.total_amount,hks.total_amount_received,
                   hks.cheque_number,hks.transaction_id,hks.driver_phone,hks.id
                   FROM hk_sales as hks left JOIN hk_persons as hkp on hkp.id = hks.person_id
                   left JOIN hk_sales_transaction_type as hkstt on hkstt.id = hks.sales_transaction_type_id
                   where hks.sales_active = 1 AND hks.bill_date='".date("Y-m-d")."' ORDER BY hks.id DESC";
              $salesExe = mysqli_query($conn,$salesQuery);
              while($salesRow = mysqli_fetch_array($salesExe)){
                $date = strtotime($salesRow["bill_date"]);
                $date = date("d-m-Y",$date);
    ?>
                <tr class="custd">
                  <td><?php echo $slNo;  ?></td>
                  <td><?php echo $salesRow["first_name"]." ".$salesRow["last_name"] ;  ?></td>

                  <td><?php echo $salesRow["bill_number"];?></td>
                  <td><?php echo $date;?></td>
                  <td><?php echo $salesRow["sales_transaction_type"]; ?></td>

                  <td><?php echo $salesRow["driver_phone"];?></td>
                    <td>

                      <p data-placement="top" data-toggle="tooltip" title="Bank">   <button data-target="#transactionModal" data-toggle="modal" class="btn btn-primary btn-sm member" onclick="transmodal('<?php echo $salesRow["total_amount"]; ?>','<?php echo $salesRow["total_amount_received"]; ?>','<?php echo $salesRow["cheque_number"]; ?>','<?php echo $salesRow["transaction_id"]; ?>')"><span class="fa fa-university"></span></button>
                    </td>


				  <td>
                      <p data-placement="top" data-toggle="tooltip" title="Product">   <button value="<?php echo $salesRow["id"]; ?>" onclick="f1(this)" data-target="#myModal" data-toggle="modal" class="btn btn-primary btn-sm"> <span class="fa fa-product-hunt"></span></button></p>
                      <td>
                          <form method="post" action="new_sales_bill.php">
                              <p data-placement="top" data-toggle="tooltip" title="Print"> <button type="submit" name="print" class="btn btn-primary btn-sm member" value="<?php echo $salesRow["id"]; ?>"><span class="fa fa-print"></span>
                            </button></p>
                          </form>

                      </td>

                      <td>
                        <form method="post" action="new_sales_bill_2.php">
                          <p data-placement="top" data-toggle="tooltip" title="Print"> <button type="submit" name="print" class="btn btn-primary btn-sm member" value="<?php echo $salesRow["id"]; ?>"><span class="fa fa-print"></span>
                            </button></p>
                        </form>

                      </td>

                      <td>
                  <form method="post" action="sales_entry_module/sales_entry_edit.php">
                      <p data-placement="top" data-toggle="tooltip" title="Edit"> <button type="submit" name="sale_id" class="btn btn-primary btn-sm member" value="<?php echo $salesRow["id"];?>"><span class="fa fa-pencil"></span>
                    </button></p>
                  </form>

              </td>


              <td class="staffdisplay">
                  <p data-placement="top" data-toggle="tooltip" title="Delete"><button
                    class="btn btn-danger btn-sm staff"  name="delete" data-toggle="modal" data-target="#deleteModal" onclick="deleteModalvalue(<?php echo $salesRow["id"];?>,'<?php echo $salesRow["first_name"]; ?>')"><span class="fa fa-trash" ></span></button></p>
              </td>

                </tr>

    <?php
                  $slNo++;
                  }
                  ?>




              </tbody>
            </table>
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

<!--
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
      <div class="modal-dialog" role="document">
        <div class="modal-content" style="width:180%;">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">PRODUCT DETAILS</h5>
            <button class="close" type="button" data-dismiss="modal" onclick="refreshtable()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
             <div class="table-responsive">

          </div>

            </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" onclick="refreshtable()" data-dismiss="modal">Cancel</button>

          </div>
        </div>
      </div>
    </div>
-->









<!--   sales product Details   -->

       <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">TRANSACTION DETAILS</h5>
            <button class="close" type="button" data-dismiss="modal" onclick="refreshtable()" aria-label="Close">
              <span aria-hidden="true">&times</span>
            </button>
          </div>

            <div class="modal-body" id="">
                <div class="table-responsive">
                         <table class="table table-bordered "  width="100%" cellspacing="0">
              <thead>
                <tr >

                 <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Amount</th>

                  </tr>
                   </thead>
                    <tbody id="table_body_modal">
                  <tr >
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
            <h5 class="modal-title" id="exampleModalLabel">TRANSACTION DETAILS</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times</span>
            </button>
          </div>

            <div class="modal-body" id="">
                <div class="table-responsive">
                    <table class="table table-bordered ">
                        <tbody>
                            <tr >
                                <td >Amount Receivable</td>
                                <td id="receivable"></td>
                            </tr>
                            <tr>
                                <td>Amount Received</td>
                                <td id="received"></td>
                            </tr>
                            <tr>
                                <td>Cheque Number</td>
                                <td id="cheque"></td>
                            </tr>
                            <tr>
                                <td>Transacion Id</td>
                                <td id="transid"></td>
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
        function  transmodal(payable,paid,cheque,transid){
            $("#receivable").text(payable);
            $("#received").text(paid);
            $("#cheque").text(cheque);
            $("#transid").text(transid);
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
               <form method="post" action="sales_entry_module/sales_entry_delete_handler.php">
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
<script type="text/javascript" src="script/sales_products.js"></script>

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
<script type="text/javascript" src="script/sales_entry_list_data.js"></script>


<script type="text/javascript">

$(document).ready(function(){
$("#myInput").on("keyup", function() {
  var value = $(this).val().toLowerCase();
  $("#salesTable1 tbody tr").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
  });
});

});
</script>
  </div>
</body>

</html>
<?php } ?>
