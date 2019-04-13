<?php
session_start();
// require("logout.php");

if($_SESSION['username']==""){
    header("Location: loginn.php");
}
else{
?>

<?php
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
  <link href="css/salesentry.css" rel="stylesheet">
  <script src="script/balance_fetch.js"></script>
  <link href="css/select1.min.css" rel="stylesheet">

    <script>
        function salescolum(){


        for(pro = 2;pro<13; pro++){

                $("#salestable > thead > tr:nth-child("+pro+") > td:nth-child(1)").css('display','none');
        }
}



    </script>

<style media="screen">
  #dataTable > thead > tr:nth-child(n) > td:nth-child(6){
    display: none;
  }
</style>

</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <?php
    require('header.php');
    ?>
  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->
        <div class="row">
                <h5 style="margin:2px 2px -20px 15px"><u>Sales Entry</u></h5>
                <pre style="float:right">                                                                       (Note:Fields with <i class="fa fa-asterisk" style="font-size:10px;color:red;"></i> make are compulsory)</pre>
            </div>

        <form class="cust_line" id="sales_form" onsubmit="return sformValidate()" method="POST" action="sales_entry_module/sales_entry_add_direct.php">
<!--            <h5 style="margin: -18px 0px 8px 0px"><u>Sales Entry</u></h5>-->


            <div class="row" style="margin-top:-12px; margin-left: 1px;">
            <h5><u>Sales Transaction Type:</u></h5>
     <label class="radio-inline"  style="margin-left: 20px; margin-top: -10px;">
      <input type="radio" name="transType" id="scash" value="1" >CASH
    </label>
    <label class="radio-inline" style="margin-left: 18px; margin-top: -10px;">
      <input type="radio" name="transType" id="scredit" value="2" checked>CREDIT
    </label>

                 <label for="date" class="adddate1">Sales Date<span class="requiredfield">*</span></label>
     <input type="date" id="ondate" class="adddate" name="ondate" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" required>
            </div>

     <div class="row">
        <div class="col-md-6">
            <table class="stablewidth">
                <tbody>
                <tr>
                    <th>Customer Name<span class="requiredfield">*</span></th>
                    <td>
                        <select class="saleslabel" onchange="f2(this)" id="cust_id" name="cust_name" required>
                        <option>Select Person</option>
                            <?php
        $sqlpname = "SELECT * FROM `hk_persons` WHERE person_type_id = 2 ORDER BY `first_name`";
        $resultset = mysqli_query($conn, $sqlpname) or die("database error:". mysqli_error($conn));
        while( $rowscust = mysqli_fetch_assoc($resultset) ) {
        ?>

        <option value="<?php echo $rowscust["id"]; ?>">
            <?php echo $rowscust["first_name"]." ".$rowscust["last_name"]; ?>
        </option>
     <?php } ?>
                        </select>
                    </td>
                    </tr>

                    <tr>
                    <th>Bill Number<span class="requiredfield">*</span></th>

                          <td>
                            <?php



                            $month = date("m");

                            if($month>=4){
                            	$year = date("Y");
                            	$toDate = $year."-03-31";
                            	$toDate = strtotime("$toDate+ 1 year");
                            	$toDate = date("Y-m-d",$toDate);
                            	$fromDate = $year."-04-01";
                            	// echo "<br>$fromDate <br> $toDate";
                            }
                            else{
                            	$year = date("Y");
                            	$fromDate = $year."-04-01";
                            	$fromDate = strtotime("$fromDate- 1 year");
                            	$fromDate = date("Y-m-d",$fromDate);
                            	$toDate = $year."-03-31";
                            	// echo "<br>$fromDate <br> $toDate";
                            }





                            require('dbconnect.php');
                            $calcBillQ = "select MAX(bill_number) as bill_number from `hk_sales` WHERE bill_date BETWEEN '$fromDate' and '$toDate'";

                            $calcBillExe = mysqli_query($conn,$calcBillQ);
                            $rows = mysqli_num_rows($calcBillExe);

                            if($rows == 0){
                              $billval =0;
                            }else{
                              while($calcBillRow = mysqli_fetch_array($calcBillExe)){
                                  $billval = $calcBillRow["bill_number"];
                              }

                            }

                            $billval = 1+$billval;
                            $billNumber = $billval;
                            ?>
                             <input type="text" class="salestext1"  name="bill_number" placeholder="Enter Bill  Number"
                             value="<?php echo $billNumber; ?>" required readonly>
                        </td>
                    </tr>
                    <tr>
                     <th>Weigh Bill Number</th>
                         <td>
                             <input type="text" class="salestext1"  name="weigh_number" placeholder="Enter Weigh Bill Number" >
                        </td>
                    </tr>
                     <tr>
                     <th>Vehicle Number</th>
                          <td>
                             <input type="text" class="salestext1"  name="vehicle_number" placeholder="Enter Vechicle Number" >
                        </td>

                    </tr>
                    <tr>
                    <th>Driver Phone Number</th>
                         <td>
                       <input type="text"  class="salestext1" name="driver_phone" placeholder="Driver Phone Number" >
                    </td>
                    </tr>
                </tbody>
            </table>
        </div>



  <div class="col-md-6">
            <table class="stablewidth">
                <tbody>
                  <tr>
                    <th>Product  Id<span class="requiredfield">*</span></th>
                    <td><input type="text"  class="selproduct" id="product_id" name="product_id" required>
                      <button class="btn btn-default" type="button" onclick="enterprod()">OK</button>
                    </td>
                  </tr>
                   <tr>
                    <th>Product Name<span class="requiredfield">*</span></th>
                    <td>

                         <select id="product_type" class="salesqty3" name="product_type">
                        <option>Select Products</option>
        <?php
        $sql = "SELECT * FROM `hk_products`";
        $resultsetP = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
        while( $rows = mysqli_fetch_assoc($resultsetP) ) {
        ?>

        <option value="<?php echo $rows["id"]; ?>"><?php echo $rows["name"]." ".$rows["type"]." ".$rows["quantity_type"]; ?></option>
     <?php } ?>
                        </select>

                    </td>
                    </tr>
                   <tr>

                    <th>Sales Quantity<span class="requiredfield">*</span></th>
                        <td>
                             <input type="text" class="salestextt1" id="quantity" name="sale_quantity" onkeypress='validate(event)' placeholder="Sales Quantity" value="" >
 <!-- onchange="f3(this)" -->

                        </td>

                    </tr>

                    <tr>
                    <th>Unit Price<span class="requiredfield">*</span></th>
                         <td>
                             <input type="text" class="salestext3" onkeypress='validate(event)' id="unit_price" onchange="finalQuantity()"
        name="unit_price" placeholder="Enter Unit Price" >
                        </td>

                    </tr>

                    <tr>
                    <th>Amount<span class="requiredfield">*</span></th>
                         <td>
                            <input type="number" class="salestext3" id="total" name="product_amount" placeholder="Total Product Amount"  readonly>
                        </td>

                    </tr>

                    <!--  <tr>
                    <th>Available Stock<span class="requiredfield">*</span></th>
                         <td>
     <input type="number" id="avail" class="salestext3" name="stock_qty" placeholder="Available Stock.." readonly>

                        </td>
                    </tr> -->


                </tbody>
            </table>
         </div>
     </div>
            <div class="row sedit2">


 <button class="buttonsave btn btn-primary" type="button" onclick="addHtmlTableRow()">Add</button>
<button class="buttonsave1 btn btn-warning" type="button" onclick="editHtmlTbleSelectedRow();">Edit</button>
 <button class="buttonsave2 btn btn-danger" type="button" onclick="removeSelectedRow()">Remove</button>



     </div>
            <div class="row">
         <div class="card-body">
             <h6><u>Selected Products</u></h6>
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr class="custtd">
<!--                  <th>Product ID </th> -->
                   <th>Product Name </th>
                    <th>Quantity </th>
                    <!-- <th>Quantity Type </th> -->
                    <th>Unit Price</th>
                    <th>Amount</th>
<!--
                     <th>Product ID</th>
                     <th>Qty Type ID</th>
-->
                     <!-- <th>Available Stock</th> -->

                 </tr>



              </thead>
             <tbody>


              </tbody>
            </table>
               <button onclick="tableone()" class="buttonsave3 btn btn-success" type="button">Save</button>


              <table class="table" id="inputtable" hidden>

                    <tr>
                        <td><input type="text" id="row_21" name="sale[0]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_22" name="sale[0]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_23" name="sale[0]['quantity_entered']" class="form-control"></td>
                        <td><input type="text" id="row_24" name="sale[0]['qty_type']" class="form-control"></td>
                        <td><input type="text" id="row_25" name="sale[0]['qty_type_id']" class="form-control"></td>
                    </tr>
                  <tr>
                        <td><input type="text" id="row_31" name="sale[1]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_32" name="sale[1]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_33" name="sale[1]['quantity_entered']" class="form-control"></td>
                        <td><input type="text" id="row_34" name="sale[1]['qty_type']" class="form-control"></td>
                        <td><input type="text" id="row_35" name="sale[1]['qty_type_id']" class="form-control"></td>

                    </tr>
                  <tr>
                        <td><input type="text" id="row_41" name="sale[2]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_42" name="sale[2]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_43" name="sale[2]['quantity_entered']" class="form-control"></td>
                        <td><input type="text" id="row_44" name="sale[2]['qty_type']" class="form-control"></td>
                        <td><input type="text" id="row_45" name="sale[2]['qty_type_id']" class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_51" name="sale[3]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_52" name="sale[3]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_53" name="sale[3]['quantity_entered']" class="form-control"></td>
                        <td><input type="text" id="row_54" name="sale[3]['qty_type']" class="form-control"></td>
                        <td><input type="text" id="row_55" name="sale[3]['qty_type_id']" class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_61" name="sale[4]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_62" name="sale[4]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_63" name="sale[4]['quantity_entered']" class="form-control"></td>
                        <td><input type="text" id="row_64" name="sale[4]['qty_type']" class="form-control"></td>
                        <td><input type="text" id="row_65" name="sale[4]['qty_type_id']" class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_71" name="sale[5]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_72" name="sale[5]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_73" name="sale[5]['quantity_entered']" class="form-control"></td>
                        <td><input type="text" id="row_74" name="sale[5]['qty_type']" class="form-control"></td>
                        <td><input type="text" id="row_75" name="sale[5]['qty_type_id']" class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_81" name="sale[6]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_82" name="sale[6]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_83" name="sale[6]['quantity_entered']" class="form-control"></td>
                        <td><input type="text" id="row_84" name="sale[6]['qty_type']" class="form-control"></td>
                        <td><input type="text" id="row_85" name="sale[6]['qty_type_id']" class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_91" name="sale[7]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_92" name="sale[7]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_93" name="sale[7]['quantity_entered']" class="form-control"></td>
                        <td><input type="text" id="row_94" name="sale[7]['qty_type']" class="form-control"></td>
                        <td><input type="text" id="row_95" name="sale[7]['qty_type_id']" class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_101" name="sale[8]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_102" name="sale[8]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_103" name="sale[8]['quantity_entered']" class="form-control"></td>
                        <td><input type="text" id="row_104" name="sale[8]['qty_type']" class="form-control"></td>
                        <td><input type="text" id="row_105" name="sale[8]['qty_type_id']" class="form-control"></td>
                    </tr>


                    <tr>
                            <td><input type="text" id="row_111" name="sale[9]['prod_id']" class="form-control"></td>
                            <td><input type="text" id="row_112" name="sale[9]['prod_name']" class="form-control"></td>
                            <td><input type="text" id="row_113" name="sale[9]['quantity_entered']" class="form-control"></td>
                            <td><input type="text" id="row_114" name="sale[9]['qty_type']" class="form-control"></td>
                            <td><input type="text" id="row_115" name="sale[9]['qty_type_id']" class="form-control"></td>
                        </tr>

                        <tr>
                                <td><input type="text" id="row_121" name="sale[10]['prod_id']" class="form-control"></td>
                                <td><input type="text" id="row_122" name="sale[10]['prod_name']" class="form-control"></td>
                                <td><input type="text" id="row_123" name="sale[10]['quantity_entered']" class="form-control"></td>
                                <td><input type="text" id="row_124" name="sale[10]['qty_type']" class="form-control"></td>
                                <td><input type="text" id="row_125" name="sale[10]['qty_type_id']" class="form-control"></td>
                            </tr>

                            <tr>
                                    <td><input type="text" id="row_131" name="sale[11]['prod_id']" class="form-control"></td>
                                    <td><input type="text" id="row_132" name="sale[11]['prod_name']" class="form-control"></td>
                                    <td><input type="text" id="row_133" name="sale[11]['quantity_entered']" class="form-control"></td>
                                    <td><input type="text" id="row_134" name="sale[11]['qty_type']" class="form-control"></td>
                                    <td><input type="text" id="row_135" name="sale[11]['qty_type_id']" class="form-control"></td>
                                </tr>

                                <tr>
                                        <td><input type="text" id="row_141" name="sale[12]['prod_id']" class="form-control"></td>
                                        <td><input type="text" id="row_142" name="sale[12]['prod_name']" class="form-control"></td>
                                        <td><input type="text" id="row_143" name="sale[12]['quantity_entered']" class="form-control"></td>
                                        <td><input type="text" id="row_144" name="sale[12]['qty_type']" class="form-control"></td>
                                        <td><input type="text" id="row_145" name="sale[12]['qty_type_id']" class="form-control"></td>
                                    </tr>

                                    <tr>
                                            <td><input type="text" id="row_151" name="sale[13]['prod_id']" class="form-control"></td>
                                            <td><input type="text" id="row_152" name="sale[13]['prod_name']" class="form-control"></td>
                                            <td><input type="text" id="row_153" name="sale[13]['quantity_entered']" class="form-control"></td>
                                            <td><input type="text" id="row_154" name="sale[13]['qty_type']" class="form-control"></td>
                                            <td><input type="text" id="row_155" name="sale[13]['qty_type_id']" class="form-control"></td>
                                        </tr>


                                            <tr>
                                                <td><input type="text" id="row_161" name="sale[14]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_162" name="sale[14]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_163" name="sale[14]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_164" name="sale[14]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_165" name="sale[14]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_171" name="sale[15]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_172" name="sale[15]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_173" name="sale[15]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_174" name="sale[15]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_175" name="sale[15]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_181" name="sale[16]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_182" name="sale[16]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_183" name="sale[16]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_184" name="sale[16]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_185" name="sale[16]['qty_type_id']" class="form-control"></td>
                                            </tr>


                                            <tr>
                                                <td><input type="text" id="row_191" name="sale[17]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_192" name="sale[17]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_193" name="sale[17]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_194" name="sale[17]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_195" name="sale[17]['qty_type_id']" class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" id="row_201" name="sale[18]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_202" name="sale[18]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_203" name="sale[18]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_204" name="sale[18]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_205" name="sale[18]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_211" name="sale[19]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_212" name="sale[19]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_213" name="sale[19]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_214" name="sale[19]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_215" name="sale[19]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_221" name="sale[20]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_222" name="sale[20]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_223" name="sale[20]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_224" name="sale[20]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_225" name="sale[20]['qty_type_id']" class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" id="row_231" name="sale[21]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_232" name="sale[21]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_233" name="sale[21]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_234" name="sale[21]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_235" name="sale[21]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_241" name="sale[22]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_242" name="sale[22]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_243" name="sale[22]['quantity_entered']" class="form-control"></td>

                                                <td><input type="text" id="row_244" name="sale[22]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_245" name="sale[22]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_251" name="sale[23]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_252" name="sale[23]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_253" name="sale[23]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_254" name="sale[23]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_255" name="sale[23]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_261" name="sale[24]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_262" name="sale[24]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_263" name="sale[24]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_264" name="sale[24]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_265" name="sale[24]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_271" name="sale[25]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_272" name="sale[25]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_273" name="sale[25]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_274" name="sale[25]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_275" name="sale[25]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_281" name="sale[26]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_282" name="sale[26]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_283" name="sale[26]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_284" name="sale[26]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_285" name="sale[26]['qty_type_id']" class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" id="row_291" name="sale[27]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_292" name="sale[27]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_293" name="sale[27]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_294" name="sale[27]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_295" name="sale[27]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_301" name="sale[28]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_302" name="sale[28]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_303" name="sale[28]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_304" name="sale[28]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_305" name="sale[28]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_311" name="sale[29]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_312" name="sale[29]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_313" name="sale[29]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_314" name="sale[29]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_315" name="sale[29]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_321" name="sale[30]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_322" name="sale[30]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_323" name="sale[30]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_324" name="sale[30]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_325" name="sale[30]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_331" name="sale[31]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_332" name="sale[31]['prod_name']" class="form-control"></td>
                                                 <td><input type="text" id="row_333" name="sale[31]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_334" name="sale[31]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_335" name="sale[31]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_341" name="sale[32]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_342" name="sale[32]['prod_name']" class="form-control"></td>
                                                 <td><input type="text" id="row_343" name="sale[32]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_344" name="sale[32]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_345" name="sale[32]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_351" name="sale[33]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_352" name="sale[33]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_353" name="sale[33]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_354" name="sale[33]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_355" name="sale[33]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_361" name="sale[34]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_362" name="sale[34]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_363" name="sale[34]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_364" name="sale[34]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_365" name="sale[34]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_371" name="sale[35]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_372" name="sale[35]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_373" name="sale[35]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_374" name="sale[35]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_375" name="sale[35]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_381" name="sale[36]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_382" name="sale[36]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_383" name="sale[36]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_384" name="sale[36]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_385" name="sale[36]['qty_type_id']" class="form-control"></td>
                                            </tr>


                                            <tr>
                                                <td><input type="text" id="row_391" name="sale[37]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_392" name="sale[37]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_393" name="sale[37]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_394" name="sale[37]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_395" name="sale[37]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_401" name="sale[38]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_402" name="sale[38]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_403" name="sale[38]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_404" name="sale[38]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_405" name="sale[38]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_411" name="sale[39]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_412" name="sale[39]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_413" name="sale[39]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_414" name="sale[39]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_415" name="sale[39]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_421" name="sale[40]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_422" name="sale[40]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_423" name="sale[40]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_424" name="sale[40]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_425" name="sale[40]['qty_type_id']" class="form-control"></td>
                                            </tr>


                                            <tr>
                                                <td><input type="text" id="row_431" name="sale[41]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_432" name="sale[41]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_433" name="sale[41]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_434" name="sale[41]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_435" name="sale[41]['qty_type_id']" class="form-control"></td>
                                            </tr>


                                            <tr>
                                                <td><input type="text" id="row_441" name="sale[42]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_442" name="sale[42]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_443" name="sale[42]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_444" name="sale[42]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_445" name="sale[42]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_451" name="sale[43]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_452" name="sale[43]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_453" name="sale[43]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_454" name="sale[43]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_455" name="sale[43]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_461" name="sale[44]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_462" name="sale[44]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_463" name="sale[44]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_464" name="sale[44]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_465" name="sale[44]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_471" name="sale[45]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_472" name="sale[45]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_473" name="sale[45]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_474" name="sale[45]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_475" name="sale[45]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_481" name="sale[46]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_482" name="sale[46]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_483" name="sale[46]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_484" name="sale[46]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_485" name="sale[46]['qty_type_id']" class="form-control"></td>
                                            </tr>


                                            <tr>
                                                <td><input type="text" id="row_491" name="sale[47]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_492" name="sale[47]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_493" name="sale[47]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_494" name="sale[47]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_495" name="sale[47]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_501" name="sale[48]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_502" name="sale[48]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_503" name="sale[48]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_504" name="sale[48]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_505" name="sale[48]['qty_type_id']" class="form-control"></td>
                                            </tr>

                                            <tr>
                                                <td><input type="text" id="row_511" name="sale[49]['prod_id']" class="form-control"></td>
                                                <td><input type="text" id="row_512" name="sale[49]['prod_name']" class="form-control"></td>
                                                <td><input type="text" id="row_513" name="sale[49]['quantity_entered']" class="form-control"></td>
                                                <td><input type="text" id="row_514" name="sale[49]['qty_type']" class="form-control"></td>
                                                <td><input type="text" id="row_515" name="sale[49]['qty_type_id']" class="form-control"></td>
                                            </tr>


              </table>


          </div>

        </div>
    </div>

    <div class="row">
         <div class="col-md-6">
            <table class="stablewidth">
                <tbody>

                  <tr hidden>
                    <th>hiden total product amount</th>
                    <td>
                      <input type="text" name="" id="total_prod_amount" value="0" readonly>
                    </td>
                  </tr>

                  <tr>
                    <th>Number of Crates</th>
                    <td>
                      <input type="number" name="crate_count" id="crate_count" value="0" class="form-control" >
                    </td>
                  </tr>

                  <tr>
                    <th>Rate per Crate</th>
                    <td>
                      <input type="number" name="crate_rate" onblur="crateAmt()" id="crate_rate" value="0" class="form-control" >
                    </td>
                  </tr>


                  <tr>
                    <th>Amount for  Crates</th>
                    <td>
                      <input type="text" name="crate_amount" id="crate_amount" onblur="crateAdd()" value="0" class="form-control">
                    </td>
                  </tr>

                  <script type="text/javascript">
                    function crateAmt(){
                      var crate_count = parseFloat($("#crate_count").val());
                      var crate_rate = parseFloat($("#crate_rate").val());
                      var res = parseFloat(crate_count*crate_rate);
                      $("#crate_amount").val(res);
                    }
                  </script>

                    <tr>
                    <th>Total Amount</th>

                        <td>
                           <input type="text" id="stotalreceivable" onchange="expensescom()" class="salestextt2"  name="transaction_id" placeholder="Total Amount" readonly>
                        </td>

                    </tr>
                     <tr>
                    <th>Commission In Percentage</th>
                         <td>
                        <input type="text" class="salestextt2" value="0" id="comm_percent" onchange="commision()" name="comm_percent" placeholder="Enter Percentage Of Commission..">
                    </td>
                    </tr>

                    <tr>
                    <th>Commission In Rupees</th>
                          <td>
                       <input type="text" class="salestextt2" value="0" id="comm_amount" name="comm_amount"  placeholder="Commission In Rupees.." readonly>
                    </td>

                    </tr>
                    <tr>
                      <th></th>
                      <td>
                        <label>
                          <input type="radio" name="add_comm"  onclick="addcomm()" checked>
                          <b>Receive Commision</b>

                        </label>
                        <label>
                          <input type="radio" name="add_comm" onclick="subcomm()">
                          <b>Give Commission</b>

                        </label>

                      </td>
                    </tr>
                    <tr>
                      <th></th>
                      <td hidden>
                        <input type="text" name="comm_type" id="comm_type" value="RECEIVE" readonly>
                      </td>
                    </tr>

                    <script type="text/javascript">

                      function addcomm() {
                          var commision_amount = $("#comm_amount").val();
                          if(commision_amount < 0){
                            commision_amount = commision_amount*-1;
                            $("#comm_amount").val(commision_amount);
                            $("#comm_type").val("RECEIVE");

                          }
                          paycalc();
                      }

                      function subcomm(){
                        var commision_amount = $("#comm_amount").val();
                        if(commision_amount > 0){
                          commision_amount = commision_amount*-1;
                          $("#comm_amount").val(commision_amount);
                          $("#comm_type").val("PAID");

                        }
                        paycalc();
                      }

                      function paycalc(){
                        var prodAmount = parseFloat($("#stotalreceivable").val());
                        var com_amount = parseFloat($("#comm_amount").val());
                        var balpaid = parseFloat($("#balpaid").val());


                        var total = parseFloat(prodAmount + com_amount );

                        $("#storedcal").val(total);

                      }

                    </script>

                     <tr>
                        <th>Balance Receivable</th>
                         <td>
                        <input type="text" class="salestext2" id="balrecvable"
                        value="" name="balrec" readonly style="margin-left: -8px;width: 100%;" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th>Balance Received</th>
                         <td>
                        <input type="text" id="balpaid"  onblur="expensescom()" value="0" class="salestext2" name="balrece" style="margin-left: -8px; width: 100%;">
                        </td>
                    </tr>
                    <tr hidden>
                        <th>Balance After Received</th>
                         <td>
                        <input type="text" id="afterbalpaid1" value="0" class="salestext2" name="afterpaid" style="margin-left: -8px; width: 100%;">
                        </td>
                    </tr>






                </tbody>
             </table>
         </div>

        <div class="col-md-6">
            <h6 style="    margin-top: -30px;"><u>Select Expenses</u></h6>
         <div class="row">
          <div class="col-md-4">

               <table>
                <tbody>
                <tr>
    <th>Expenses<span class="requiredfield">*</span></th>
     <td>
     <select id="expenses" class="expen" name="expenseslist1" >
     <?php
                                    //select expense from expense table
                                    $selectExpenseQ = "select * from hk_expenses_type";
                                    $selectExpenseExe = mysqli_query($conn,$selectExpenseQ);
                                    while($selectExpenseRow = mysqli_fetch_array($selectExpenseExe)){
                                    ?>

                                    <option value="<?php echo $selectExpenseRow ["id"] ; ?>"><?php echo $selectExpenseRow ["expenses_type"] ; ?></option>


                                    <?php
                                    }
                                    ?>
         </select>
                    </td>
                </tr>
                    </tbody>
            </table>
      </div>
                <div class="col-md-2" >
               <table>
                <tbody>
                    <tr>
                    <td>
                        <input type="text" id="expensevalue1" class="stext5" name="expensevalue1"
                         placeholder="Amount.." value="0" required>

                    </td>
                </tr>
                 </tbody>
            </table>
      </div>
            </div>
             <div class="row" style="margin-left: -36px; margin-top:10px;">

           <button class="buttonsave btn btn-primary" type="button" onclick="addHtmlTableRow1();" >Add</button>
                <button class="buttonsave1 btn btn-warning" type="button" onclick="editHtmlTbleSelectedRow1();"  >Edit</button>
                <button class="buttonsave2 btn btn-danger" type="button" onclick="removeSelectedRow1();" >Remove</button>

     </div>

      <h6 style="margin-top:10px;"><u>Selected Expenses</u></h6>
            <table class="table table-bordered table-sm" id="salestable" width="100%" cellspacing="0">
              <thead>
                <tr style="font-size: 14px;">
                     <th style="display:none;">Expense ID</th>
                    <th>Expense </th>
                    <th>Amount</th>

                 </tr>
              </thead>
              <tbody>
              </tbody>
            </table>

            <button  onclick="expensetable()" type="button" class="buttonsave3 btn btn-success">Save</button>

            <table class="table" hidden>
                    <tbody>
                        <tr>
                            <td><input type="text" id="expense_21" name="expenses[0][id]" readonly></td>
                            <td><input type="text" id="expense_22" name="expenses[0][expense]" readonly></td>
                        </tr>
                        <tr>
                            <td><input type="text" id="expense_31" name="expenses[1][id]" readonly></td>
                            <td><input type="text" id="expense_32" name="expenses[1][expense]" readonly></td>
                        </tr>
                        <tr>
                            <td><input type="text" id="expense_41" name="expenses[2][id]" readonly></td>
                            <td><input type="text" id="expense_42" name="expenses[2][expense]" readonly></td>
                        </tr>
                        <tr>
                            <td><input type="text" id="expense_51" name="expenses[3][id]" readonly></td>
                            <td><input type="text" id="expense_52" name="expenses[3][expense]" readonly></td>
                        </tr>
                        <tr>
                            <td><input type="text" id="expense_61" name="expenses[4][id]" readonly></td>
                            <td><input type="text" id="expense_62" name="expenses[4][expense]" readonly></td>
                        </tr>
                        <tr>
                            <td><input type="text" id="expense_71" name="expenses[5][id]" readonly></td>
                            <td><input type="text" id="expense_72" name="expenses[5][expense]" readonly></td>
                        </tr>
                        <tr>
                            <td><input type="text" id="expense_81" name="expenses[6][id]" readonly></td>
                            <td><input type="text" id="expense_82" name="expenses[6][expense]" readonly></td>
                        </tr>
                        <tr>
                            <td><input type="text" id="expense_91" name="expenses[7][id]" readonly></td>
                            <td><input type="text" id="expense_92" name="expenses[7][expense]" readonly></td>
                        </tr>
                         <tr>
                            <td><input type="text" id="expense_101" name="expenses[8][id]" readonly></td>
                            <td><input type="text" id="expense_102" name="expenses[8][expense]" readonly></td>
                        </tr>
                        <tr>
                            <td><input type="text" id="expense_111" name="expenses[9][id]" readonly></td>
                            <td><input type="text" id="expense_112" name="expenses[9][expense]" readonly></td>
                        </tr>

                    </tbody>
                </table>

            </div>

</div>


<hr>
        <div class="row">

            <div class="col-md-6">



                     <h5 style="margin-top: -10px;"><u>Payment Details</u></h5>
                     <table>
                            <tbody>

                             <tr>
                    <th>Total Receivable Amount<span class="requiredfield">*</span></th>

                        <td>
                           <input type="text" id="storedcal"
                           class="salestextt2"  name="rec_amount"
                           placeholder="Total Receivable Amount" readonly>
                        </td>

                    </tr>



                            <tr>
                        <th>Total expense Amount <span class="requiredfield">*</span></th>
                        <td>
                            <input type="text" class="salestext2" value="0" onblur="expensecall()" id="totalExpense" name="expense_amt" placeholder="Expense Amount" readonly>
                        </td>
                    </tr>

                                  <tr>
                    <th>Net Amount<span class="requiredfield">*</span></th>

                        <td>
                           <input type="text" id="snet_amt"
                           class="salestextt2"  value="0" name="transaction_id"
                           placeholder="Total Recivable Amount" style="margin-left: 9px;" readonly>
                        </td>

                    </tr>

                    <tr>
                      <th>Send Message</th>
                      <td style="padding-left:90px">
                        <label>
                          <input type="radio" name="send_msg" value="1" checked>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="send_msg" value="0">
                          No
                        </label>
                      </td>

                    </tr>

                    <tr>



                            </tbody>
                     </table>
                 </div>






              <div class="col-md-6" >
                  <div id="scash1" style="margin-left: 15px;" hidden>
    <div class="row" >

            <h5><u>Payment Transaction Methods</u></h5>

            <label class="radio-inline" style="margin-left:-270px; margin-top:30px;">
      <input type="radio" name="transMethod" id="scashm" value="1"  >CASH
    </label>
    <label class="radio-inline" style="margin-top:30px;">
      <input type="radio" name="transMethod" id="schequem" value="2">CHEQUE
    </label>
    </div>


                <table style="margin-left: -15px;">
                    <tbody>
                    <tr>
                    <th>Total Receivable Amount<span class="requiredfield">*</span></th>

                        <td>
                           <input type="text" id="stotalrecei"
                           class="salestextt2"  name="stotalreceivable"
                           placeholder="Total Receivable Amount" readonly>
                        </td>

                    </tr>

                    <tr>
                        <th>Total Amount Received <span class="requiredfield">*</span></th>
                        <td> <input type="text" class="salestext2" id="totalPaid" onblur="duecalc()" name="totalPaid" placeholder="Amount Received" value="0" ></td>

                    </tr>
                    <tr>
                        <th>Balance Amount<span class="requiredfield">*</span></th>
                        <td><input type="text" class="salestext2" id="duepay" value="0" placeholder="Balance Amount" onblur="duecalc()" name="duepay" readonly></td>

                    </tr>

                    <tr>
                        <th>Final Balance<span class="requiredfield">*</span></th>
                        <td><input type="text" class="salestext2" id="finalbal" value="0" placeholder="Final Balance" name="" readonly></td>

                    </tr>


                     <tr>
                    <th>Enter Cheque Number</th>
                    <td><input type="text" class="salestext2" name="chequeNumber" id="schequenumber" placeholder="Enter Cheque Number" value="" style= "margin-left: 9px;
    width: 100%;"></td>
                </tr>

                        <tr>
                        <th>Transaction ID </th>
                        <td>
                            <input type="text" class="salestext2" id="transaction_id2"
                            name="transaction_id2" placeholder="Transaction ID" >
                        </td>
                    </tr>
                        </tbody>
                </table>

            </div>



             </div>
    </div>



<div class="row">

              <script>

                  $(function(){
                    $("#scash,#scredit").change(function(){
                        if($("#scash").is(":checked")){
                                   $("#scash1").removeAttr('hidden');
                                   $("#scash1").show();


                                    $("#stotalreceivable").prop('required',true);
                                    $("#stotalreceived").prop('required',true);
                                    $("#sbalanceamt").prop('required',true);



                                    $("#scash").css({"background-color":"black","color":"white"});
                                    $("#scredit").css({"background-color":"dimgray"});
                             }
                        else if($("#scredit").is(":checked")){
                            $("#scash1").hide();
                            $("#scredit").css({"background-color":"black","color":"white"});
                            $("#scash").css({"background-color":"dimgray"});

                             $("#stotalreceivable").prop('required',false);
                            $("#stotalreceived").prop('required',false);
                            $("#sbalanceamt").prop('required',false);
                        }
                    });
                });

                $(function(){
                    $("#scashm,#schequem").change(function(){
                    $("#schequenumber").val("").attr("readonly",true);
                    if($("#schequem").is(":checked")){
                        $("#schequenumber").removeAttr("readonly");
                        $("#schequenumber").prop('required',true);
                        $("#schequenumber").focus();
                     }else if($("#scashm").is(":checked")){
                         $("#schequenumber").attr("readonly",true);
                         $("#schequenumber").prop('required',false);

                     }
    });
});

            </script>



      </div>

<div class="row" style="margin-left: 378px;margin-top: 20px;">

       <button class="buttonsubmit" type="button" onclick="confirmModel()" data-toggle="modal" data-target="#confirmModal"><a>Submit</a></button>
     <a  href="sales_entry_list.php" style="text-decoration:none;"  class="buttonreset"><span>Cancel</span></a>
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
    <!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">�</span>
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
       <script src="js/salestable.js"></script>
    <!-- Custom scripts for this page-->
   <script src="js/sb-admin-datatables.min.js"></script>
   <script src="js/supplierdetails.js"></script>
   <script type="text/javascript" src="script/sales_data.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   <!-- ajax file for stock  -->
   <!-- <script type="text/javascript" src="script/fetch_Pid_Qtyid.js"></script> -->
   <!-- dropdown search js -->
   <script src="js/jquery-3.2.1.min.js"></script>
   <script src="js/select2.min.js"></script>

       <script>

            var rIndex,
                table = document.getElementById("dataTable");

            // check the empty input
            function checkEmptyInput()
            {
                var isEmpty = false,
                    product_type =  $("#product_type option:selected").html(),
                    sale_quantity= document.getElementById("quantity").value,
//                    quantitytype  = $("#quantitytype option:selected").html(),
                  //  quantitytype = document.getElementById("quantitytype").value,
                    unit_price  = document.getElementById("unit_price").value,
                    product_amount  = document.getElementById("total").value,
                    product_id =$("#product_type option:selected").val(),
                    quantity_id =$("#quantitytype option:selected").val();

            }
 // add Row

           function reseting(){
           $("#product_type").val(0);
            $("#quantity").val(0);
            // $("#quantitytype").val(0);
            $("#unit_price").val(0);
            $("#total").val(0);
              $("#avail").val(0);

        }
            function addHtmlTableRow()
            {
                if(!checkEmptyInput()){
                var newRow = table.insertRow(table.length),
                    cell1 = newRow.insertCell(0),
                    cell2 = newRow.insertCell(1),
                    cell3 = newRow.insertCell(2),
                    cell4 = newRow.insertCell(3),
                    cell5 = newRow.insertCell(4),
                    cell6 = newRow.insertCell(5),

                   product_type =  $("#product_type option:selected").html(),
                   sale_quantity= document.getElementById("quantity").value,
              //      quantitytype =  $("#quantitytype option:selected").html(),
                   unit_price  = document.getElementById("unit_price").value,
                   product_amount  = document.getElementById("total").value,
                   product_id =$("#product_type option:selected").val(),
                   quantity_id =$("#quantitytype option:selected").val();

                        cell1.innerHTML = product_type;
                        cell2.innerHTML =  sale_quantity;

                        cell3.innerHTML =  unit_price;
                        cell4.innerHTML =  product_amount;
                        cell5.innerHTML =  product_id;
                        cell6.innerHTML =  quantity_id;

                selectedRowToInput();
                    reseting();
                    scolum();
            }
            }
             // selectedRowToInput();
            // display selected row data into input text
            function selectedRowToInput()
            {

                for(var i = 1; i < table.rows.length; i++)
                {
                    table.rows[i].onclick = function()
                    {
                      // get the seected row index
                        debugger;
                      rIndex = this.rowIndex;
                         $('#product_type').val(this.cells[4].innerHTML);
                         $('#quantity').val(this.cells[1].innerHTML);
                        // $('#quantitytype').val(this.cells[6].innerHTML);
                         $('#unit_price').val(this.cells[2].innerHTML);
                         $('#total').val(this.cells[3].innerHTML);

                    };
                }
            }
            selectedRowToInput();

            function editHtmlTbleSelectedRow()
            {
               var
                product_type =  $("#product_type option:selected").html(),
                   sale_quantity= document.getElementById("quantity").value,

                   unit_price  = document.getElementById("unit_price").value,
                   product_amount  = document.getElementById("total").value,
                   product_id = document.getElementById("product_type").value;


               if(!checkEmptyInput()){
                   table.rows[rIndex].cells[0].innerHTML = product_type;
                table.rows[rIndex].cells[1].innerHTML = sale_quantity;

                table.rows[rIndex].cells[2].innerHTML =  unit_price ;
                    table.rows[rIndex].cells[3].innerHTML =  product_amount;
                    table.rows[rIndex].cells[4].innerHTML =  product_id;


              }
            }

            function removeSelectedRow()
            {
                table.deleteRow(rIndex);
             $('#product_type option:selected').val(this.cells[0]? this.cells[0].innerHTML:'');
                         $('#quantity').val(this.cells[1].innerHTML);
                         $('#quantitytype').val(this.cells[2].innerHTML);
                         $('#unit_price').val(this.cells[3].innerHTML);
                         $('#total').val(this.cells[4].innerHTML);

            }

        </script>

   <script>
$( "select[name='cust_name']" ).change(function () {
    var stateID = $(this).val();
    if(stateID) {
        $.ajax({
            url: "sales_entry_ajax_php/ajax_sales_1.php",
            dataType: 'Json',
            data: {'id':stateID},
            success: function(data) {
              console.log(data);
                $('select[name="order"]').empty();
                $.each(data, function(key, value) {
                    $('select[name="order"]').append('<option value="'+ key +'">'+ value +'</option>');
                });
            }
        });


    }else{
        $('select[name="order"]').empty();
    }
});


// recursiveCall();
/*var myVar = window.setInterval(function(){
  recursiveCall();
}, 5000);*/
</script>


      <script>
         function salesExpense(){
            $("#expensevalue1").prop('required',false);
        }

      </script>

         <script>

                  function tableone(){
                    var sum=0;
                      for(row = 2; row<51;row++){
                           var xvalue =[];
                            xvalue[0] = $("#dataTable > thead > tr:nth-child(" + row+ ") > td:nth-child(2)").text();

                            xvalue[1] = $("#dataTable > thead > tr:nth-child(" + row+ ") > td:nth-child(3)").text();
                            xvalue[2] = $("#dataTable > thead > tr:nth-child(" + row+ ") > td:nth-child(4)").text();
                            xvalue[3] = $("#dataTable > thead > tr:nth-child(" + row+ ") > td:nth-child(5)").text();


                            var value = $("#dataTable > thead > tr:nth-child(" + row+ ") > td:nth-child(4)").text();

                                $('#row_'+ row +1 ).val(xvalue[0]);
                                $('#row_'+ row +2 ).val(xvalue[1]);
                                $('#row_'+ row +3 ).val(xvalue[2]);
                                $('#row_'+ row +4 ).val(xvalue[3]);



                                if (!isNaN(value) && value.length !=0) {
                                    sum+=parseFloat(value);
                                }
                                $("#total_prod_amount").val(sum);

                      }
                  }
                  function getSum(total,num) {
                      return total+num;
                  }


    //expense module

     function expensetable(){
          //expense module
         var expenseSum = 0;
                          for(exrow = 2; exrow<13;exrow++){

                           var expense =[];
                          expense[0] = $("#salestable > thead > tr:nth-child("+ exrow +") > td:nth-child(1)").text();
                          expense[1] = $("#salestable > thead > tr:nth-child("+ exrow +") > td:nth-child(3)").text();

                              var toatalExpense = $("#salestable > thead > tr:nth-child("+ exrow +") > td:nth-child(3)").text();


                          $("#expense_"+ exrow +1).val(expense[0]);
                          $("#expense_"+ exrow +2).val(expense[1]);


                          if(!isNaN(toatalExpense) && toatalExpense.length !=0){
                              expenseSum += parseFloat(toatalExpense);
                          }
                          $("#totalExpense").val(expenseSum);


                          }
         salesExpense();
     }

</script>

<script type="text/javascript">
    function finalQuantity(){

              var unitprice = $('#unit_price').val();
              var loaded = $('#quantity').val();
              var total = (parseFloat(unitprice)*parseFloat(loaded));
              $('#total').val(total);
          }

           function  commision(){
          var amount = $("#stotalreceivable").val();
//          var advance = $("#advance").val();
          var commisionPerent = $("#comm_percent").val();
          var commision = parseFloat(amount*(commisionPerent/100));
          $("#comm_amount").val(commision);
          }

          function expensescom(){
              var amountCal = $("#stotalreceivable").val();
              // var labour = $("#labour").val();
              // var trans = $("#trans").val();
              // var post = $("#post").val();
              // var miscellaneous = $("#miscellaneous").val();
              var com = $("#comm_amount").val();
              var prebal= $("#balrecvable").val();
              var prebalpaid = $("#balpaid").val();
              var afterbalpaid= parseFloat(prebal)-parseFloat(prebalpaid);

              // var totalexpense = (parseFloat(labour)+parseFloat(trans)+parseFloat(post)+parseFloat(miscellaneous));
              var pay = (parseFloat(amountCal)-parseFloat(com));
//              var pays=parseFloat(totexp)+pay;
                $("#storedcal").val(pay);
                $("#afterbalpaid1").val(afterbalpaid);

          }
    function expensecall(){
        var netamt= $("#storedcal").val();
        var totexp = $("#totalExpense").val();
        var netamnt=parseFloat(netamt)+parseFloat(totexp);
        $("#snet_amt").val(netamnt);
         $("#stotalrecei").val(netamnt);
    }

           function duecalc(){
              var totalpayable = $("#stotalrecei").val();
              var totalpaid = $("#totalPaid").val();
              var totbal= $("#afterbalpaid1").val();
              var balfromfinal= $("#duepay").val();
              var totbalfinal=parseFloat(balfromfinal)+parseFloat(totbal);
              var due = parseFloat(totalpayable)-parseFloat(totalpaid);
              $("#duepay").val(due);
              $("#finalbal").val(totbalfinal);

          }
</script>
        <script>
          $('#name').on('change',function(){
              var selection = $(this).val();
              switch(selection){
                  case "2":
                      $('#Cheque').show();
                      $('#Rtgs').hide();
                      break;
                  case "3":
                      $('#Rtgs').show();
                      $('#Cheque').hide();
                      break;
                  default:
                      $('#Cheque').hide();
                      $('#Rtgs').hide();
                      break;

              }
          })



      </script>


      <!--      search dropdown-->
      <script src="js/jquery-3.2.1.min.js"></script>
      <script src="js/select2.min.js"></script>


<!--
      <script>
        $(document).ready(function(){

            // Initialize select2
            $("#product_type").select2();

            // Read selected option
            $('#but_read').click(function(){
                var username = $('#product_type option:selected').text();
                var userid = $('#product_type').val();
            });
        });
        </script>
-->


<script>
    function setadvance(){
        if($("#advance").val()=="")
        {
            $("#advance").val("0");
        }

    }
</script>

        <script>
        function scolum(){


        for(pro = 2;pro<13; pro++){
                $("#dataTable > thead > tr:nth-child("+pro+") > td:nth-child(6)").css('display','none');
             $("#dataTable > thead > tr:nth-child("+pro+") > td:nth-child(7)").css('display','none');
        }

        }

    </script>
    <!-- for search -->
    <script>
        $(document).ready(function(){

            // Initialize select2
            $("#cust_id").select2();

            // Read selected option
            $('#but_read').click(function(){
                var username = $('#cust_id option:selected').text();
                var userid = $('#cust_id').val();

//                $('#result').html("id : " + userid + ", name : " + username);
            });
        });
    </script>


    </div>


<!-- script to add crate amount to product amount  -->

<script type="text/javascript">
    function crateAdd(){
      var total_prod_amount = parseFloat($("#total_prod_amount").val());
      var crate_amount = parseFloat($("#crate_amount").val());
      var sum = parseFloat(total_prod_amount+crate_amount);
      $("#stotalreceivable").val(sum);
    }
</script>


<!-- script to add crate amount to product amount  -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <!-- <a class="btn btn-primary" href="login.html">Logout</a> -->
        <form method="post" action="logout_handler.php">
            <button type="submit" name="logout" class="btn btn-primary" style="margin-bottom:-14px;">Logout</button>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- confirm model -->

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Please confirm all the entries</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="container">
          <div class="row">
            <div class="col-md-4">
              <label for="md-name">
                Name: <b id="md-name"></b>
              </label>
            </div>
            <div class="col-md-4">
              <label for="md-date">
                Date: <b id="md-date"></b>
              </label>
            </div>
            <div class="col-md-4">
              <label for="md-transtype">
                Transaction Type: <b id="md-transtype"></b>
              </label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <table  class="table table-bordered table-hover table-sm">
                <thead>
                  <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody id="confir_table">

                </tbody>
              </table>
            </div>
          </div>

          <div class="row">

            <div class="col-md-12">

              <table class="table">
                <thead>
                  <tr>
                    <th>Total Expenses</th>
                    <th>Commission Amount</th>
                    <th>Number of Crates Given</th>
                    <th>Crate Bill Amount</th>
                    <th>Net Amount</th>
                    <th>Amount received</th>
                    <th>Balance Amount</th>
                    <th>Final  Balance Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><b id="md_expenses"></b></td>
                    <td><b id="md_comm_amount"></b></td>
                    <td><b id="md_num_crates"></b></td>
                    <td><b id="md_crate_amt"></b></td>
                    <td><b id="md_net_amount"></b></td>
                    <td><b id="md_received_amount"></b></td>
                    <td><b id="md_balance"></b></td>
                    <td><b id="md_final_bal"></b></td>
                  </tr>


                </tbody>

              </table>

            </div>


          </div>

        </div>

      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <!-- <a class="btn btn-primary" href="login.html">Logout</a> -->
        <button name="submit" onclick="sales_form()" class="btn btn-primary">Submit</button>

      </div>
    </div>
  </div>
</div>


<!-- confirm model -->


<!-- confirm Model script -->

<script type="text/javascript">

function sformValidate(){
  var cust_id = $("#cust_id").val();
  var date = $("#ondate").val();
  if(isNaN(cust_id) || date == ""){
    alert("Please Provide Proper inputs");
    return false;
  }
  else {
    return true;
  }
}

  function sales_form(){

    var res = sformValidate();
    if(res == true){
        document.getElementById("sales_form").submit();
    }
  }


  function confirmModel(){
    var md_name = $("#cust_id option:selected").html();
    var md_date = $("#ondate").val();
    var md_transtype = $("input[name=transType]:checked").val();
    if(md_transtype == "1"){
      md_transtype_name = "CASH";
    }else{
      md_transtype_name = "CREDIT";
    }

    var md_comm_amount = $("#comm_amount").val(); //commission amount
    var md_expenses = $("#totalExpense").val(); // total expense
    var md_net_amount = $("#snet_amt").val(); // Net amount

    var md_num_crates = $("#crate_count").val();
    var md_crate_amt = $("#crate_amount").val();



var transType = $("input[name='transType']:checked").val();

 if(transType == "1"){
   // cash sales
   var md_received_amount = $("#totalPaid").val(); //amount received
   var md_balance = $("#duepay").val(); // balance amt
   var md_final_bal = $("#finalbal").val(); //final balance



 }else{
   // credit sales
   var md_received_amount = "0"; //amount received will be zero
   var md_balance = parseFloat(md_net_amount); // net amount will be balance

   // fetch previous balance
   var md_prev_bal = $("#balrecvable").val();

   // and balance recovered
   var md_bal_received = $("#balpaid").val();

   // subtract both and add it to md_balance
    var md_final_bal = parseFloat(md_prev_bal-md_bal_received); //final balance
    var md_final_bal = parseFloat(md_final_bal+md_balance);
console.log(md_final_bal);

 }



    $("#md-name").text(md_name);
    $("#md-date").text(md_date);
    $("#md-transtype").text(md_transtype_name);
$("#confir_table").html("");
copytab();
$("#md_expenses").text(md_expenses);
$("#md_net_amount").text(md_net_amount);
$("#md_received_amount").text(md_received_amount);
$("#md_balance").text(md_balance);
$("#md_final_bal").text(md_final_bal);
$("#md_comm_amount").text(md_comm_amount);
$("#md_num_crates").text(md_num_crates);
$("#md_crate_amt").text(md_crate_amt);


  }

    function copytab(){
      var prod_name = [];
      var quantity = [];
      var unitprice = [];
      var amount = [];
      var len = $("#dataTable > thead > tr").length;

      var count = 2;
      for(var i =0;i<len-1;i++){
        prod = $("#dataTable > thead > tr:nth-child("+count+") > td:nth-child(1)").text();
        prod_name.push(prod);
        quant = $("#dataTable > thead > tr:nth-child("+count+") > td:nth-child(2)").text();
        quantity.push(quant);
        unit = $("#dataTable > thead > tr:nth-child("+count+") > td:nth-child(3)").text();
        unitprice.push(unit);
        amt = $("#dataTable > thead > tr:nth-child("+count+") > td:nth-child(4)").text();
        amount.push(amt);
        count++;
      }

      for (var i = 0; i < len-1; i++) {

        $("#confir_table").append(`

          <tr>
            <td>`+prod_name[i]+`</td>
            <td>`+quantity[i]+`</td>
            <td>`+unitprice[i]+`</td>
            <td>`+amount[i]+`</td>
          </tr>

          `);
      }

      console.log(prod_name);
      console.log(quantity);
      console.log(unitprice);
      console.log(amount);

    }
</script>
<script type="text/javascript">
          function enterprod(){
            // product_id of input box
            var prod_id = $("#product_id").val();
            // id of select tag
            $("#product_type").val(prod_id);
          }

        </script>

<!-- confirm model script -->

</body>

</html>

  <?php }   ?>
