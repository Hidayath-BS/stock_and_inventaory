<?php session_start(); 
require("logout.php"); 
if($_SESSION['username']==""){
	header("Location: loginn.php"); } else{ ?>


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
    <link href="../css/order.css" rel="stylesheet">
    
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->

      <?php
    require('header.php');
     require('../dbconnect.php');
 ?>
  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->

        <form class="cust_line" method="post" action="order_edit_handler.php">
            <?php
                $id = $_POST["edit"];
                $editdata = "SELECT HKO.*,HKOS.order_status_type,HKOS.id AS ostid, HKC.first_name,HKP.name,HKP.type, HKP.id AS prid, HKQT.quantity_type, HKC.id AS custid FROM `hk_orders` AS HKO LEFT JOIN `hk_orders_status_type` AS HKOS ON HKO.status_type_id=HKOS.id
                            LEFT JOIN `hk_customers` AS HKC ON HKO.customer_id=HKC.id
                            LEFT JOIN `hk_products` AS HKP ON HKO.product_id=HKP.id
                            left JOIN hk_quantity_type as HKQT ON HKQT.id = HKP.quantity_type_id
                            WHERE HKO.id=".$id;
                $data = mysqli_query($conn,$editdata);
                if($data){
                while($datarow = mysqli_fetch_array($data)){


            ?>
           
            <div class="row"> <h5 style="margin: -18px 0px 8px 0px"><u>Edit Order</u></h5>
    <pre style="float:right">       								 (Note: Fields with <i class="fa fa-asterisk" style="font-size:13px;color:red"></i> mark are compulsory)</pre></div>
            <div class="row">
        <div class="col-md-6">
          <label for="address" >Customer Name <span class="requiredfield">*</span></label>
         <select name="customer_id" required>

            <?php
             require('../dbconnect.php');
            $c_type_q = " SELECT * FROM `hk_customers` WHERE customers_active = 1";
            $custexe = mysqli_query($conn,$c_type_q);
            while($custrow = mysqli_fetch_array($custexe)){
            ?>

            <option value="<?php echo $custrow['id']; ?>"<?=$custrow['id'] == $datarow['custid'] ? 'selected="selected"':'' ?>><?php echo $custrow['first_name']; ?></option>
            <?php
            }
            ?>
</select>
            <div class="orderrow"></div>


         
<!--        <input type="text" id="address" name="address" placeholder="Your address.." >-->
<!--
         <select style=" margin-left: 13px;" name="product_id" id="employee" required>
             <?php
             require('../dbconnect.php');
            $c_type_q = " SELECT HKP.*,HKQT.quantity_type FROM `hk_products` HKP left JOIN hk_quantity_type as HKQT on HKQT.id = HKP.quantity_type_id WHERE HKP.products_active = 1";
            $proexe = mysqli_query($conn,$c_type_q);
            while($prorow = mysqli_fetch_array($proexe)){
                $quantityType = $prorow['quantity_type'];
            ?>
            <option value="<?php echo $prorow['id']; ?>"<?=$prorow['id'] == $datarow['prid'] ? 'selected="selected"':'' ?>><?php echo $prorow['name']."-".$prorow['type']; ?></option>
            <?php
            }
            ?>

</select>
-->
             <label for="address" >Product Name <span class="requiredfield">*</span></label>
            <select id="employee" class="orderproduct" name="product_id" required>
		              <?php
		                      $sql = " SELECT HKP.*,HKQT.quantity_type FROM `hk_products` HKP left JOIN hk_quantity_type as HKQT on HKQT.id = HKP.quantity_type_id WHERE HKP.products_active = 1";
		                      $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
		                      while( $rows = mysqli_fetch_assoc($resultset) ) {
                                  $quantityType= $rows['quantity_type'];
		                  ?>
		              <option value="<?php echo $rows["id"]; ?>"<?=$rows['id'] == $datarow['prid'] ? 'selected="selected"':'' ?>><?php echo $rows['name']."-".$rows['type']." ". $rows['quantity_type']; ?></option>
		              <?php }	?>
		  </select>
        </div>



          <div class="col-md-6">

        <label for="address" style="margin-right: 53px">Quantity <span class="requiredfield">*</span></label>
        <input type="text" class="orderqty" name="quantity"  placeholder="Enter Quantity" value="<?php echo $datarow['quantity']; ?>" required >
              <b  id="quantityType"><?php echo $datarow["quantity_type"]; ?></b>
<!--
              <select class="purunit1" required>
                             <option value="1">Kg</option>
                              <option value="2">Crates</option>
                   <option value="3">Pieces</option>
                        </select>
-->
   <div class="orderrow"></div>
        <label for="address" style="margin-right: 10px;">Order Status <span class="requiredfield">*</span></label>
<!--        <input type="text" id="address" name="address" placeholder="Your address.." >-->
         <select id="address" name="order_status_id" required>
             <?php
             require('../dbconnect.php');
            $orc_type_q = " SELECT * FROM `hk_orders_status_type`";
            $orexe = mysqli_query($conn,$orc_type_q);
            while($orrow = mysqli_fetch_array($orexe)){
            ?>
             
            <option value="<?php echo $orrow['id']; ?>"<?=$orrow['id'] == $datarow['ostid'] ? 'selected="selected"':'' ?>><?php echo $orrow['order_status_type']; ?></option>
            <?php
            }
            ?>

</select>    </div>
                </div>



<!--
              <div class="row" style="margin-top: -18px;">

            <div class="col-md-6">
        <label for="state">State</label>
        <input type="text" id="state" name="state" placeholder="Enter your state.." required>
     </div>

    <div class="col-md-6">
        <label for="phone">Phone Number</label>
        <input type="number" id="phone1" name="phone" placeholder="Enter your phone no.." maxlength="10" oninput="maxLengthCheckphone(this)" pattern="[0-9]{10}" required>
                  </div></div>
                    <div class="row" style="margin-left: 1px;margin-top: -7px;">

          <label for="phone" style="margin-top: -10px;" >Alternative Number</label>
        <input type="number" id="phone" name="phone" placeholder="Enter your phone no.." maxlength="10" oninput="maxLengthCheckphone(this)" pattern="[0-9]{10}" required>
          </div>
-->



    <div class="row">


         <button class="buttonsubmit" type="submit" name="submit" value="<?php echo $id; ?>"><a style="color: white;">Submit</a></button>
        <button class="buttonreset"><a style="color: white;text-decoration:none;" href="../order_list.php">Cancel</a></button>
<!--
        <button class="buttonsubmit"><a href="#" style="color: white;">Submit</a></button>
        <button class="buttonreset" ><a href="#" style="color: white;">Cancel</a></button>
-->
    </div>
            <?php }}else{
                    echo "sorry".mysqli_error($conn);
                } ?>
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
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="../script/getQuantity.js"></script>

  </div>
</body>

</html>
<?php
//require('../dbconnect.php');
//$name = $_POST["name"];
//$cust_type = $_POST["cust_type"];
//$address = $_POST["address"];
//$city = $_POST["city"];
//$state = $_POST["state"];
//$phone = $_POST["phone"];
//$altphone = $_POST["altphone"];
//
//
//
//
//$updatequery ="UPDATE `hk_customers` SET name='$name', customer_type_id='$cust_type', address='$address',city='$city',state='$state',phone_number='$phone' WHERE id='$id'";
//
////UPDATE 'hk_customers' SET 'filed_name' = 'value' WHERE id=;
//
//
//
//if(mysqli_query($conn,$updatequery)){
//    echo "success query exicuted";
//}
//else{
//    echo "sorry".mysqli_error($conn);
//}
//

?>
<?php } ?>