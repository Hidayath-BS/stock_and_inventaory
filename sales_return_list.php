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
  <div class="content-wrapper ">
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
          <h6>Sales Return Details</h6>

            <button class="purcadd" onclick="myFunction()"><i class="fa fa-refresh" style="color: white;"></i></button>
            <button class="purcaddbutton"><a href="sales_return_direct.php" style="color: white;"> <i class="fa fa-plus"> Add Sales Return</i></a></button>
          </div>
        <div class="card-body">

          <div class="container">

<form  action="salesReturn_account_print.php" method="post">

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
            <table class="table table-bordered table-hover" id="salesReturnTable" width="100%" cellspacing="0">
              <thead>
                <tr style="font-size: 14px;">
                  <th>Sl.No </th>
                  <th>Return Date </th>
                  <th>Return Bill No.</th>
                  <th>Customer Name</th>
                  <th>Sales Bill No.</th>
                    <th>Product Details</th>
                  <th>Return Amount</th>
                  <th>Transaction Type</th>
                  <th>Bank Details</th>
                  <th>Print Bill</th>
                 </tr>
              </thead>
              <tbody id="table_body">
                <?php
                             require('dbconnect.php');
                             $salesreturnlistq ="SELECT HKSR.*,HKS.bill_number as sales_bill_number,
                              HKP.first_name,HKS.bill_number as sales_bill_number,
                             HKP.last_name,HKSTT.sales_transaction_type AS transaction_type
                             FROM `hk_sales_return` AS HKSR
                             LEFT JOIN `hk_sales` AS HKS ON HKSR.sales_id=HKS.id
                             LEFT JOIN `hk_persons` AS HKP ON HKS.person_id=HKP.id
                             LEFT JOIN `hk_sales_transaction_type` AS HKSTT ON HKSR.transaction_type_id=HKSTT.id
                             WHERE HKSR.date='".date("Y-m-d")."'
                              ORDER BY HKSR.id DESC";
                             $exe = mysqli_query($conn,$salesreturnlistq);
                             function getname($person_id){
                               require('dbconnect.php');
                               $selectname="select first_name,last_name from hk_persons WHERE
                              id=$person_id";
                               $exeselectname = mysqli_query($conn,$selectname);
                               while($row = mysqli_fetch_array( $exeselectname)){
  //
              $cust_name=$row['first_name']." ".$row['last_name'];

            }
            return $cust_name;
                             }

                                 $x=0;//To show Serial numbers irrespective of Order ID
                             while($row = mysqli_fetch_array($exe)){
//
            $customer_name=$row['first_name']." ".$row['last_name'];
            if($customer_name==" "){
            $person_id=$row["person_id"];
            $customer_name= getname($person_id);
          }
$date = strtotime($row['date']);
$date = date("d-m-Y",$date);


                             ?>

                <tr class="custtd">
                  <td scope="row"><?php

                      echo  ++$x;
                  ?></td>
                  <td><?php echo $date; ?></td>
                  <td><?php echo $row['sales_return_bill_number']; ?> </td>
                  <td><?php echo $customer_name; ?></td>
                  <td><?php echo $row['sales_bill_number']; ?></td>
                  <td><p data-placement="top" data-toggle="tooltip" title="Product">
                        <button data-target="#productModal" data-toggle="modal" class="btn btn-sm btn-primary" value="<?php echo $row['id'] ; ?>" onclick="f1(this)"><span class="fa fa-product-hunt"></span>
                        </button>
                    </td>

                  <td><?php echo $row['amount_paid']; ?></td>
                       <td><?php echo $row['transaction_type']; ?></td>


                       <td>
                           <p data-placement="top" data-toggle="tooltip" title="Bank">  <button data-target="#myModal" data-toggle="modal" class="btn btn-sm btn-primary" onclick="bankModalValue('<?php echo $row['check_number']; ?>','<?php echo $row['transaction_id']; ?>')"><span class="fa fa-university"></span></button>
                         </td>
<!--
                    <td>
                        <p data-placement="top" data-toggle="tooltip" title="Edit"> <button type="submit" name="edit" class="btn btn-primary btn-sm" value=""><span class="fa fa-pencil"></span>
                          </button></p>
                    </td>
-->
<!--                    <td><p data-placement="top" data-toggle="tooltip" title="Delete">    <button   class="btn btn-danger btn-sm"  name="delete" data-toggle="modal" data-target="#deleteModal" ><span class="fa fa-trash" ></span></button></p></td>-->

<td>
    <form method="post" action="sales_return_bill.php">
        <p data-placement="top" data-toggle="tooltip" title="Print"> <button type="submit" name="print" class="btn btn-primary btn-sm member" value="<?php echo $row["id"]; ?>"><span class="fa fa-print"></span>
      </button></p>
    </form>

</td>
                </tr>
              <?php } ?>

            </tbody>
                <!-- <tbody>

                <tr style="font-size: 14px;">
                  <td>1</td>
                  <td>28/5/2017</td>
                  <td>10122 </td>
                  <td>Ramesh</td>
                  <td>10099</td>
                  <td>Mango</td>
                  <td>40</td>
                  <td>743474</td>
                  <td>8648875</td>
                  <td>cash</td>
                  <td><button id="myBtn" class="modalbutton">Bank Details</button></td>
                    <td>
                        <p data-placement="top" data-toggle="tooltip" title="Edit"> <button type="submit" name="edit" class="btn btn-primary btn-sm" value=""><span class="fa fa-pencil"></span>
                          </button></p></td>
                    <td>
                        <p data-placement="top" data-toggle="tooltip" title="Delete">    <button   class="btn btn-danger btn-sm"  name="delete" data-toggle="modal" data-target="#deleteModal" ><span class="fa fa-trash" ></span></button></p></td>

                </tr>

            </tbody> -->
            </table>
          </div>

        </div>
      </div>
    </div>
               <div id="myModal1" class="smodal">
  <!-- Modal content -->
  <div class="smodal-content">
    <div class="smodal-header">
      <span class="sclose">&times;</span>
      <h2>Bank Details</h2>
    </div>
    <div class="smodal-body">
      <div class="table-responsive">
            <table class="table table-bordered suppliertr" id="bank_table" width="100%" cellspacing="0">
              <thead>
                <tr >
                  <td class="suppliertd">Cheque Number :</td>
                    <td></td>
                  </tr>
                  <tr>
                  <td class="suppliertd">Transaction Id :</td>
                    <td></td>
                 </tr>
<!--
                  <tr>
                      <td class="suppliertd">Bank A/C No :</td>
                      <td></td>
                  </tr>
                  <tr>
                      <td class="suppliertd">Branch Name :</td>
                      <td></td>
                  </tr>
-->
<!--
                  <tr>
                      <td class="suppliertd">IFSC</td>
                      <td></td>
                  </tr>
-->
              </thead>
              <!-- <tbody>
                <?php
                             require('dbconnect.php');
                             $salesreturnlistq =" SELECT HKSR.*,HKS.bill_number as sales_bill_number,
                             HKP.name as product_name,HKP.type AS product_type,
                             HKC.first_name,HKC.last_name,HKSTT.type AS transaction_type
                             FROM `hk_sales_return` AS HKSR
                             LEFT JOIN `hk_sales` AS HKS ON HKSR.sales_id=HKS.id
                             LEFT JOIN `hk_orders` AS HKO ON HKS.order_id=HKO.id
                             LEFT JOIN `hk_products` AS HKP ON HKO.product_id=HKP.id
                             LEFT JOIN `hk_customers` AS HKC ON HKS.customer_id=HKC.id
                             LEFT JOIN `hk_sales_transaction_type` AS HKSTT ON HKSR.transaction_type_id=HKSTT.id";
                             $exe = mysqli_query($conn,$salesreturnlistq);

                                 $x=0;//To show Serial numbers irrespective of Order ID
                             while($row = mysqli_fetch_array($exe)){
//


                             ?>

                <tr class="custtd">
                  <td scope="row"><?php

                      echo  ++$x;
                  ?></td>
                  <td><?php echo $row['date']; ?></td>
                  <td><?php echo $row['sales_return_bill_number']; ?> </td>
                  <td><?php echo $row['first_name']." ".$row['last_name']; ?></td>
                  <td><?php echo $row['bill_number']; ?></td>
                  <td><?php echo $row['product_name']; ?></td>
                  <td><?php echo $row['sales_return_quantity']; ?></td>
                  <td><?php echo $row['amount_paid']; ?></td>
                       <td><?php echo $row['transaction_type']; ?></td>
                       <td>
                           <button data-target="#myModal" data-toggle="modal" class="modalbutton" onclick="bankModalValue('<?php echo $row['check_no']; ?>','<?php echo $row['transaction_id']; ?>')">Bank Details</button>
                         </td>
                    <td> <p data-placement="top" data-toggle="tooltip" title="Edit"> <button type="submit" name="edit" class="btn btn-primary btn-sm" value=""><span class="fa fa-pencil"></span>
                          </button></p></td>
                    <td><p data-placement="top" data-toggle="tooltip" title="Delete">    <button   class="btn btn-danger btn-sm"  name="delete" data-toggle="modal" data-target="#deleteModal" ><span class="fa fa-trash" ></span></button></p></td>


                </tr>
              <?php } ?>

            </tbody> -->
            </table>
          </div>
    </div>
<!--
    <div class="smodal-footer">
      <h3>Modal Footer</h3>
    </div>
-->
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
    <!-- Logout Modal-->
    <!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
          </div>
        </div>
      </div>
    </div> -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">BANK DETAILS</h5>
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
          <button class="close" type="button" data-dismiss="modal" onclick="refreshtable()" aria-label="Close">
            <span aria-hidden="true">&times</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered suppliertr" id="productDetails" width="100%" cellspacing="0">
            <thead>
              <tr class="custtd">

               <th>Product Name</th>
                  <th>Return Quantity</th>
                  <th>Rate</th>
                  <th>Amount</th>

                </tr>

            </thead>
            <tbody>

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
      <script src="script/sales_return_list.js"></script>
    <script>
    function bankModalValue(check_number,transaction_id) {
  //          alert(""+name+" "+accname+" "+accnum+" "+branch+" "+ifsc);
        $("#check_number").html(check_number);
        $("#transaction_id").html(transaction_id);
    }
    </script>
    <script>
function myFunction() {
    location.reload();
}</script>
  </div>

  <!-- search script  -->

  <script type="text/javascript">

  $(document).ready(function(){
  $("#searchInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#salesReturnTable tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

  });
  </script>

  <!-- search script ends -->


</body>

</html>
<?php } ?>
