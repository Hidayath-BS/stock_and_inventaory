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
    <link href="css/order.css" rel="stylesheet">

    <!--    Tab css-->
    <link href="css/order_list.css" rel="stylesheet">
  </head>

  <body class="fixed-nav sticky-footer bg-dark" id="page-top">
    <!-- Navigation-->
    <?php
    require('header.php');
    ?>
    <div class="content-wrapper">
      <div class="container-fluid">
  <div class="card mb-3">
    <div class="card-header">
      <a>Order List</a>
      <button class="orderadd" onclick="myFunction()"><i class="fa fa-refresh" style="color: white;"></i></button>
      <button class="orderaddbutton"><a href="add_order.php" style="color: white;"> <i class="fa fa-plus"> Add Order</i></a></button>
    </div>
    <div class="card-body1">

      <!--  Tab start       -->

      <section id="tabs">
        <div class="container">
          <div class="row">
            <div class="col-lg-12 ">
              <nav>
                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                  <a class="nav-item1 nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-home" aria-selected="true">Received</a>
                  <a class="nav-item1 nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Processing</a>
                  <a class="nav-item1 nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-contact" aria-selected="false">Delivered</a>
                </div>
              </nav>


              <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">

                <!--First Tab-->
                <div class="tab-pane fade show active" id="nav-contact" role="tabpanel" aria-labelledby="nav-home-tab">
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm" id="dataTable1" width="100%" cellspacing="0">
                      <thead>
                        <tr class="custtd">
                          <th>Sl No.</th>
                          <!--                    <th>Order ID</th>-->
                          <th>Order Date</th>
                          <th>Customer Name</th>
                          <th>Order Status</th>
                          <th>Product Details</th>

                          <th>Sale</th>

                        </tr>
                      </thead>

                      <tbody>
                        <?php
                        require('dbconnect.php');
                        $OrderQuery ="SELECT HKO.*,HKP.first_name,HKOST.order_status_type FROM `hk_orders` AS HKO
                        LEFT JOIN `hk_persons` AS HKP on HKO.person_id=HKP.id
                        LEFT JOIN `hk_orders_status_type` AS HKOST ON HKO.status_type_id=HKOST.id
                        WHERE HKO.status_type_id = '1' ORDER BY HKO.id DESC ";


                        $OrderEntryexe = mysqli_query($conn,$OrderQuery);
                        $i=1;


                        while($Order_row = mysqli_fetch_array($OrderEntryexe))
                        {
                          ?>

                          <tr class="custtd">
                            <td scope="row"><?php echo $i++; ?></td>
                            <td><?php echo $Order_row['date']; ?></td>
                            <td><?php echo $Order_row['first_name']; ?></td>


                            <td><?php echo $Order_row['order_status_type']; ?></td>

                            <td><p data-placement="top" data-toggle="tooltip" title="Product">
                                <button data-target="#myModal1" data-toggle="modal" class="btn btn-sm btn-primary order_details"  style="text-align:center;" onclick="orderdproducts(this)" value="<?php echo $Order_row["id"]; ?>"><span class="fa fa-product-hunt"></span></button></td>

                            <td class="custtd">
                              <form method="post" action="sales_entry.php">
                                <p data-placement="top" data-toggle="tooltip" title="Sale">   <button type="submit" name="sale_id" class="btn btn-primary btn-sm" value="<?php echo $Order_row['id']; ?>">
                                  <span class="fa fa-shopping-cart"></span>
                                </button></p>

                              </form>
                            </td>

                          </tr>
                        <?php } ?>


                      </tbody>
                    </table>
                    <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">PRODUCT DETAILS</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="table-responsive">
                              <table class="table table-bordered suppliertr" width="100%" cellspacing="0">
                                <thead>
                                  <th>Product Name</th>
                                  <th>Quantity</th>



                                </thead>
                                <tbody>
                                  <tr class="custtd">
                                    <td id="row_11"></td>
                                    <td id="row_12"></td>




                                  </tr>
                                  <tr class="custtd">
                                    <td id="row_21"></td>
                                    <td id="row_22"></td>




                                  </tr>
                                  <tr class="custtd">
                                    <td id="row_31"></td>
                                    <td id="row_32"></td>




                                  </tr>
                                  <tr class="custtd">
                                    <td id="row_41"></td>
                                    <td id="row_42"></td>
                                    <td id="row_43"></td>



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

                  </div>
                </div>

                <!--Second Tab-->
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm" id="dataTable2" width="100%" cellspacing="0">
                      <thead>
                        <tr class="custtd">
                          <th>Sl NO</th>
                          <!--                    <th>Order ID</th>-->
                          <th>Order Date</th>
                          <th>Customer Name</th>

                          <th>Order Status</th>
                          <th>Product Details</th>

                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        require('dbconnect.php');
                        $OrderQuery ="SELECT HKO.*,HKP.first_name,HKOST.order_status_type FROM `hk_orders` AS HKO
                        LEFT JOIN `hk_persons` AS HKP on HKO.person_id=HKP.id
                        LEFT JOIN `hk_orders_status_type` AS HKOST ON HKO.status_type_id=HKOST.id
                        WHERE HKO.status_type_id = '2' ORDER BY HKO.id DESC ";


                        $OrderEntryexe = mysqli_query($conn,$OrderQuery);
                        $i=1;


                        while($Order_row = mysqli_fetch_array($OrderEntryexe))
                        {
                          ?>


                          <tr class="custtd">
                            <td scope="row"><?php echo $i++; ?></td>
                            <td><?php echo $Order_row['date']; ?></td>
                            <td><?php echo $Order_row['first_name']; ?></td>



                            <td><?php echo $Order_row['order_status_type']; ?></td>
                            <td>
                                <p data-placement="top" data-toggle="tooltip" title="Product"> <button data-target="#myModal2" data-toggle="modal" class="btn btn-sm btn-primary order_details"  style="text-align:center;" onclick="orderdproducts(this)" value="<?php echo $Order_row["id"]; ?>"><span class="fa fa-product-hunt"></span></button></td>

                          </tr>
                        <?php } ?>


                      </tbody>
                    </table>
                    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">PRODUCT DETAILS</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="table-responsive">
                              <table class="table table-bordered suppliertr" width="100%" cellspacing="0">
                                <thead>
                                  <th>Product Name</th>
                                  <th>Quantity</th>




                                </thead>
                                <tbody>
                                  <tr class="custtd">
                                    <td id="prorow_11"></td>
                                    <td id="prorow_12"></td>




                                  </tr>
                                  <tr class="custtd">
                                    <td id="prorow_21"></td>
                                    <td id="prorow_22"></td>




                                  </tr>
                                  <tr class="custtd">
                                    <td id="prorow_31"></td>
                                    <td id="prorow_32"></td>




                                  </tr>
                                  <tr class="custtd">
                                    <td id="prorow_41"></td>
                                    <td id="prorow_42"></td>




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

                  </div>
                </div>

                <!--Third Tab-->
                <div class="tab-pane fade " id="nav-home" role="tabpanel" aria-labelledby="nav-contact-tab">
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm" id="dataTable3" width="100%" cellspacing="0">
                      <thead>
                        <tr class="custtd">
                          <th>Sl NO</th>
                          <!--                    <th>Order ID</th>-->
                          <th>Order Date</th>
                          <th>Customer Name</th>

                          <th>Order Status</th>
                          <th>Product Details</th>
<!--                          <th>Delete</th>-->
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                        require('dbconnect.php');
                        $OrderQuery ="SELECT HKO.*,HKP.first_name,HKOST.order_status_type FROM `hk_orders` AS HKO
                        LEFT JOIN `hk_persons` AS HKP on HKO.person_id=HKP.id
                        LEFT JOIN `hk_orders_status_type` AS HKOST ON HKO.status_type_id=HKOST.id
                        WHERE HKO.status_type_id = '3' ORDER BY HKO.id DESC ";


                        $OrderEntryexe = mysqli_query($conn,$OrderQuery);
                        $i=1;


                        while($Order_row = mysqli_fetch_array($OrderEntryexe))
                        {
                          ?>


                          <tr class="custtd">
                            <td scope="row"><?php echo $i++; ?></td>
                            <td><?php echo $Order_row['date']; ?></td>
                            <td><?php echo $Order_row['first_name']; ?></td>


                            <td><?php echo $Order_row['order_status_type']; ?></td>



                            <td><p data-placement="top" data-toggle="tooltip" title="Product">
                                <button data-target="#myModal3" data-toggle="modal" class="btn btn-sm btn-primary order_details"  style="text-align:center;" onclick="orderdproducts(this)" value="<?php echo $Order_row["id"]; ?>"><span class="fa fa-product-hunt"></span></button></td>

                          </tr>
                        <?php } ?>


                      </tbody>
                    </table>

                    <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">PRODUCT DETAILS</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="table-responsive">
                              <table class="table table-bordered suppliertr" width="100%" cellspacing="0">
                                <thead>
                                  <th>Product Name</th>
                                  <th>Quantity</th>




                                </thead>
                                <tbody>
                                  <tr class="custtd">
                                    <td id="drow_11"></td>
                                    <td id="drow_12"></td>




                                  </tr>
                                  <tr class="custtd">
                                    <td id="drow_21"></td>
                                    <td id="drow_22"></td>




                                  </tr>
                                  <tr class="custtd">
                                    <td id="drow_31"></td>
                                    <td id="drow_32"></td>




                                  </tr>
                                  <tr class="custtd">
                                    <td id="drow_41"></td>
                                    <td id="drow_42"></td>



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
                  </div>
                </div>

</div>

</div>
</div>
</div>
</section>



<!--  Tab Ends       -->

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
<!-- Logout Modal-->
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
</div> -->
<!-- delete modal   -->

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
        <form method="post" action="order_module/order_delete_handler.php">
          <button class="btn btn-default" type="submit" name="delete" id="deleteModalButton" value="" style="margin-bottom:-14px;">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- delete modal   -->

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
function deleteModalvalue(deleteId, name){
  $('#deleteModalButton').val(deleteId);
  $('#deleteModalName').html("Hey!.. "+ id +" will get deleted soon..");
}
function flushValues(){
  $('#deleteModalButton').val("");
}</script>
<script>
function myFunction() {
  location.reload();
}
</script>
<?php
if($_SESSION['role']=='STAFF'){
  echo "<script> function staff(){
    $('.staff').attr('disabled','disabled');
    $('.member').attr('disabled','disabled');
    $('.member').removeAttr('href');
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
    $("#row_"+j+5).text("");
    $("#row_"+j+6).text("");
    $("#row_"+j+7).text("");
  }
}

</script>
<script>
function bankModalValue(pname,p_qty,p_qty_type) {
  $("#pname").html(pname);
  $("#p_qty").html(p_qty);
  $("#p_qty_type").html(p_qty_type);
  console.log(pname);
  //          $("#bankname").html(bankname);
  //          $("#branch").html(branch);
  //          $("#ifsc").html(ifsc);
}
</script>
<!--   script for ajax ordered products   -->
<script type="text/javascript" src="script/ordered_products.js"></script>
<!-- Go back when click on cancel-->


</div>
</body>

</html>
<?php } ?>
