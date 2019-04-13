<?php
session_start();
// require("logout.php");

if($_SESSION['username']==""){
    header("Location: loginn.php");
}
else{
?>
<?php
require('../dbconnect.php');
    $purchaseId = $_POST["edit"];
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
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="../vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="../css/sb-admin1.css" rel="stylesheet">
    <link href="../css/purchaseentry.css" rel="stylesheet">
    <!--    search dropdown-->
    <link href="../css/select2.min.css" rel="stylesheet">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="../script/getData.js"></script>
    <script src="../script/purchseQuantity.js"></script>
    <script>

        function columsi(){


        for(ex = 2;ex<8; ex++){
//
    $("#table > thead > tr:nth-child("+ex+") > td:nth-child(1)").css('display','none');

        }

        }





    </script>

</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
   <!-- Navigation-->
   <?php

    require('header.php');

    $purcaseDetailsQ = "SELECT * FROM `hk_purchases` WHERE id = '$purchaseId'";
    $purchaseExe = mysqli_query($conn,$purcaseDetailsQ);
    while($purchaseRow = mysqli_fetch_array($purchaseExe)){
        $billNumber = $purchaseRow["bill_number"];
        $billDate = $purchaseRow["bill_date"];
        $personId = $purchaseRow["person_id"];
        $vehicalNumber = $purchaseRow["vehicle_number"];
        $weighbillNumber = $purchaseRow["weighbill_slip_number"];
        $location = $purchaseRow["location"];
        $emptyWeight = $purchaseRow["empty_weight"];
        $loadedWeight = $purchaseRow["loaded_weight"];
        $netweight = $purchaseRow["net_weight"];
        $purchaseTranstype = $purchaseRow["purchase_transaction_type_id"];

        $advancePayable = $purchaseRow["advance_receivable"];
        $advanceReceived = $purchaseRow["advance_received"];
        $balanceRecevable = $purchaseRow["balance_receivable"];
        $balanceReceived = $purchaseRow["balance_received"];


        $amountPayable = $purchaseRow["amount_payable"];
        $amountpaid = $purchaseRow["amount_paid"];

        $paidTo = $purchaseRow["paid_to"];
        $chequeNumber = $purchaseRow["cheque_number"];
        $transtableId = $purchaseRow["transaction_table_id"];

    }

    ?>
  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->
       <div class="row">
                <h5 style="8px 2px -20px 15px; margin-left:24px;"><u>Purchase Entry</u></h5>
                <pre style="float:right;margin-top:-30px;">                                                                                   (Note:Fields with <i class="fa fa-asterisk" style="font-size:10px;color:red;"></i> make are compulsory)</pre>
            </div>
        <form class="cust_line" method="post" action="purchase_entry_edit_handler1.php">
<div class="row" style="margin-top:-12px; margin-left: 1px;">
            <h5><u>Purchase Transaction Type :</u></h5>


 <label class="radio-inline" style="margin-left: 20px; margin-top: -10px;">
      <input type="radio" name="transType" id="cash" value="1"<?= $purchaseTranstype == '1' ? 'checked="checked"':'' ?> required>CASH
    </label>
    <label class="radio-inline" style="margin-left: 18px; margin-top: -10px;">
      <input type="radio" name="transType" id="credit" value="2" <?= $purchaseTranstype == '2' ? 'checked="checked"':'' ?>>CREDIT
    </label>


    <label for="date" class="adddate1">Purchase Date<span class="requiredfield">*</span></label>
     <input type="date" id="ondate" class="adddate" value="<?php echo $billDate; ?>" name="ondate" max="<?php echo date('Y-m-d'); ?>">
            </div>
<div class="row" style="margin-top: 15px;">
        <div class="col-md-4" >
            <table>
                <tbody>
                <tr>
                    <th>Supplier Name<span class="requiredfield">*</span></th>
                    <td>
                        <select id="employee" class="ptext" name="supplier_id" >
                            <option value="" selected="selected">Select Supplier Name</option>
                <?php
                        $sql = "SELECT id,first_name,last_name FROM hk_persons WHERE person_type_id = 1";
                        $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
                        while( $rows = mysqli_fetch_assoc($resultset) ) {
                    ?>
                <option value="<?php echo $rows["id"]; ?>"<?=$rows["id"] == $personId ? 'selected="selected"':'' ?>><?php echo $rows["first_name"]." ".$rows["last_name"]; ?></option>
                <?php } ?>
                  </select>
                     </td>
                </tr>
                    <tr>
                        <th>Bill Number<span class="requiredfield">*</span></th>
                        <td>

                          <input type="text" id="address1" class="ptext" name="billNumber" value="<?php echo $billNumber; ?>" placeholder="   Enter bill number" required readonly></td>
                    </tr>
                     <tr>
                        <th>
                        Vehicle Number
                        </th>
                        <td>
                        <input type="text" id="phone1" class="ptext" name="vehicleNumber"  value="<?php echo $vehicalNumber ;?>"
                        placeholder="Enter vehicle number" >
                        </td>
                    </tr>
                </tbody>
            </table>
    </div>

        <div class="col-md-4">
            <table>
                <tbody>

                   <tr>
                    <th>Empty weight</th>
                    <td>

                    <input type="number" id="accountno" class="ptext1" name="emptyWeight" onchange="netweight1()" value="<?php echo $emptyWeight; ?>" placeholder="Enter Empty weight"><span> Kg</span>

                    </td>
                </tr>
                     <tr>
                    <th>Loaded weight</th>
                    <td>
                        <input type="number" id="emptyw" class="ptext1" name="loadedWeight" onchange="netweight1()" value="<?php echo $loadedWeight; ?>" placeholder="Enter Loaded weight"><span> Kg</span>
<!--                        <label style="padding:0px;margin-bottom:0px">TONNE</label>-->

                    </td>
                </tr>
                      <tr>
                        <th>Net Weight</th>
                        <td>
                            <input type="number" id="netweight" class="ptext1" name="netWeight"  style="margin-bottom:4px;padding-left:12px;" value="<?php echo $netweight; ?>" placeholder="Enter Net weight" readonly><span> Kg</span>
                        </td>

                    </tr>
                </tbody>
            </table>

    </div>
     <div class="col-md-4">
            <table>
                <tbody>
                    <tr>
                        <th>Weigh Bill Number</th>
                        <td>
                            <input type="text" id="phone" class="ptext2" name="weighBillNo" style="margin-bottom:4px;padding-left:1px;"  value="<?php echo $weighbillNumber; ?>" placeholder="   Enter weigh bill number" >

                        </td>
                    </tr>




                    <tr>
                        <th>
                        Location
                        </th>
                        <td>
                        <input type="text" id="location" class="ptext2" name="location" placeholder=" Enter Location" value="<?php echo $location; ?>" style="padding-left:9px;" >
                        </td>
                    </tr>

                    <tr style="display:none">
                        <th>Transaction table ID</th>
                        <td>
                        <input type="text" class="ptext2" value="<?php echo $transtableId; ?>" name="transtableid" readonly>
                        </td>
                    </tr>
                    <tr style="display:none">
                        <th>Purchase Table  ID</th>
                        <td>
                        <input type="text" class="ptext2" value="<?php echo $purchaseId; ?>" name="purchaseID" readonly>
                        </td>

                    </tr>

                </tbody>
            </table>

    </div>
            </div>
<!--            style="margin-top:-27px;"-->
            <hr>
            <h6><u>Select Products</u></h6>
<div class="row">

        <div class="col-md-4" >
            <table>
                <tbody>
                <tr>
                    <th style="width:42%;">Product name</th>
                    <td>
                        <select id="address"  class="ptextp" name="product_id">
                            <option value="" selected="selected">Select Product Name</option>
             <?php
             $productQuery = "select id, name,type,quantity_type from `hk_products`";
             $productExe = mysqli_query($conn,$productQuery);
             while($productRow = mysqli_fetch_array($productExe)){
             ?>
                    <option value="<?php echo $productRow["id"]; ?>"><?php echo $productRow["name"]." ".strtoupper($productRow["type"])." ".strtoupper($productRow["quantity_type"]); ?></option>

             <?php
             }
             ?>

         </select>

                    </td>
                </tr>
 <tr>
                    <th>Quantity<span class="requiredfield">*</span></th>
                    <td>
                        <input type="number" id="shrink1" class="ptext21" name="Qtys" onchange="finalQuantity()"  placeholder="Enter shrink.." value="0" required>
<!--                        <label style="padding:0px;margin-bottom:0px">Kg</label>-->


                    </td>
                </tr>
                     <tr>
                    <th>Shrink Weight</th>
                    <td>
<!--                        <input type="number" id="shrink" class="ptext31" name="shrinkWeight" onchange="finalQuantity()"  placeholder="Enter shrink.." value="0" required>-->

                        <input type="number" id="shrink" class="ptext31" name="shrinkWeight" onchange="finalQuantity()"  placeholder="Enter shrink" value="0" required >


                    </td>
                </tr>
                 </tbody>
            </table>
    </div>

        <div class="col-md-4">
            <table>
                <tbody>


                     <tr>
                        <th>
                            Final Quantity<span class="requiredfield">*</span>
                        </th>
                        <td>
                            <input type="number" id="final" class="ptext15" name="finalQunatity"  style="margin-bottom:1px;padding-left: 2px;" placeholder="   Enter final quantity" readonly required>
                        </td>
                    </tr>
                 <tr>
                    <th>Unit Price<span class="requiredfield">*</span></th>
                    <td>
                    <input type="number" id="city" class="ptext22" name="unitPrice" onchange="finalQuantity()" placeholder="Enter unit price"required>  <span>INR</span>
<!--                        <label style="padding:0px;margin-bottom:0px">INR</label>-->
                    </td>
                </tr>

                    <tr>
                        <th>Amount<span class="requiredfield">*</span></th>
                        <td>
                            <input type="number" id="total" class="ptext15" name="totalAmount" placeholder="Enter total amount" onblur="setadvance()" style="padding-left: 12px"  required readonly>
                        </td>
                    </tr>


                </tbody>
            </table>



     <div class="row pursave">
           <button class="buttonsave btn btn-primary" onclick="addHtmlTableRow()" type="button">Add</button>
                <button class="buttonsave1 btn btn-warning" onclick="editHtmlTbleSelectedRow();" type="button">Edit</button>
                <button type="button" class="buttonsave2 btn btn-danger" onclick="removeSelectedRow()">Remove</button>

<!--
         <button class="buttonsubmit" type="submit"><a >Submit</a></button>
   <a href="customer_list.php" style=" text-decoration: none;" class="buttonreset">  <span >Cancel</span></a>
-->

     </div>

     </div>
    <div class="vl"></div>
     <div class="col-md-4">
            <table>
                <tbody>


                      <tr>
                        <th>
                            Advance Bal. Receivable<span class="requiredfield">*</span>
                        </th>
                        <td>
                            <input type="number" id="advance" class="ptext33" name="advance" value="<?php echo $advancePayable; ?>" placeholder="Advance" required readonly>
                        </td>
                    </tr>
                 <tr>
                        <th>
                        Advance Bal. Received<span class="requiredfield">*</span>
                        </th>
                        <td>
                        <input type="number" id="advancepaid" class="ptext33" name="advancepaid" value="<?php echo $advanceReceived; ?>">
                        </td>
                    </tr>
                    <tr style="display:none">
                        <th>Previous advance balance received</th>
                        <td>
                        <input type="text" class="ptext33" value="<?php echo $advanceReceived; ?>" name="preadvancepaid">
                        </td>
                    </tr>
                    <tr>
                        <th>Previous Bal. Receivable<span class="requiredfield">*</span></th>
                        <td>
                        <input type="number" id="balpayble" class="ptext33" value="<?php echo $balanceRecevable ; ?>" name="balpayble" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th>Previous Bal. Received<span class="requiredfield">*</span></th>
                        <td>
                        <input type="number" id="balpaid" class="ptext33" value = "<?php echo $balanceReceived; ?>" name="balpaid">
                        </td>
                    </tr>
                    <tr style="display:none">
                        <th>Previous Balanace Received</th>
                        <td>
                            <input type="text" value="<?php echo $balanceReceived; ?>" name="prevbalpaid">
                        </td>
                    </tr>

                </tbody>
            </table>

    </div>
            </div>

            <hr>
           <h6><u>Selected Products</u></h6>
             <div class="card-body" style="margin-top:-7px;">
          <div class="table-responsive">
            <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr style="font-size: 14px;">
                   <th>Product Name </th>
                    <th>Quantity</th>
                    <th>Shrink</th>
                    <th>Final Quantity</th>

                     <th>Unit Price</th>
                    <th>Amount</th>
                 </tr>

                                      <?php
                    $selectProdutDetailsQ = "SELECT HKPP.*,HKP.name,HKP.type,HKP.quantity_type FROM `hk_purchased_products` as HKPP
                    left JOIN hk_products as HKP on HKP.id = HKPP.product_id
                    WHERE purchase_id = $purchaseId";
                    $productExe = mysqli_query($conn,$selectProdutDetailsQ);
                    $totalProductAmount = 0;
                    $productdetails = array();
                    $count =0;
                    while($productDetailsRow = mysqli_fetch_array($productExe)){
                    ?>
                <tr>
                    <td><?php echo $productDetailsRow["name"]." ".$productDetailsRow["type"]." ".$productDetailsRow["quantity_type"]; ?></td>
                    <td><?php echo $productDetailsRow["quantity"]; ?></td>
                    <td><?php echo $productDetailsRow["shrink"]; ?></td>
                    <td><?php echo $productDetailsRow["final_quantity"]; ?></td>
                    <!-- <td><?php echo $productDetailsRow["quantity_type"]; ?></td> -->
                    <td><?php echo $productDetailsRow["rate"]; ?></td>
                    <td><?php echo $productDetailsRow["amount"]; ?></td>
                    <!-- <td style="display:none;"><?php echo $productDetailsRow["quantity_type"]; ?></td> -->
                    <td style="display:none;"><?php echo $productDetailsRow["product_id"]; ?></td>


                </tr>
                <?php
                    $totalProductAmount = $totalProductAmount+$productDetailsRow["amount"];

                        $productdetails[$count]['productid'] = $productDetailsRow["product_id"];
                        $productdetails[$count]['quantity'] = $productDetailsRow["quantity"];
                        $productdetails[$count]['shrink'] = $productDetailsRow["shrink"];
                $productdetails[$count]['final_quantity'] = $productDetailsRow["final_quantity"];
                        $productdetails[$count]['quantity_type'] = $productDetailsRow["quantity_type"];
                        $productdetails[$count]['rate'] = $productDetailsRow["rate"];
                        $productdetails[$count]['amount'] = $productDetailsRow["amount"];

                         error_reporting(E_ALL ^ E_NOTICE);

                        $count++;
                    } ?>


              </thead>

              <tbody>



              </tbody>
            </table>
          </div>

        </div>


            <div class="row">
            <div class="col-md-12">

                <table class="table" id="inputtable" style="display:none;" >

                    <tr>
                        <td><input type="text" id="row_21" value="<?php echo $productdetails[0]['productid']; ?>" name="purchase[0]['id']" class="form-control"></td>
                        <td><input type="text" id="row_22" value="<?php echo $productdetails[0]['quantity']; ?>" name="purchase[0]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_23" value="<?php echo $productdetails[0]['shrink']; ?>" name="purchase[0]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_24" value="<?php echo $productdetails[0]['final_quantity']; ?>" name="purchase[0]['finalqunatity']" class="form-control"></td>
                        <td><input type="text" id="row_25" value="<?php echo $productdetails[0]['quantity_type_id']; ?>" name="purchase[0]['qauntitytype']" class="form-control"></td>
                        <td><input type="text" id="row_26" value="<?php echo $productdetails[0]['rate']; ?>" name="purchase[0]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_27" value="<?php echo $productdetails[0]['amount']; ?>" name="purchase[0]['amount']" class="form-control"></td>
                    </tr>
                  <tr>
                        <td><input type="text" id="row_31" value="<?php echo $productdetails[1]['productid']; ?>" name="purchase[1]['id']" class="form-control"></td>
                        <td><input type="text" id="row_32" value="<?php echo $productdetails[1]['quantity']; ?>" name="purchase[1]['quantity']"  class="form-control"></td>
                        <td><input type="text" id="row_33"  value="<?php echo $productdetails[1]['shrink']; ?>" name="purchase[1]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_34" value="<?php echo $productdetails[1]['final_quantity']; ?>" name="purchase[1]['finalqunatity']" class="form-control"></td>
                        <td><input type="text" id="row_35" value="<?php echo $productdetails[1]['quantity_type_id']; ?>" name="purchase[1]['qauntitytype']"  class="form-control"></td>
                        <td><input type="text" id="row_36"  value="<?php echo $productdetails[1]['rate']; ?>" name="purchase[1]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_37" value="<?php echo $productdetails[1]['amount']; ?>" name="purchase[1]['amount']"  class="form-control"></td>

                    </tr>
                  <tr>
                        <td><input type="text" id="row_41" value="<?php echo $productdetails[2]['productid']; ?>" name="purchase[2]['id']" class="form-control"></td>
                        <td><input type="text" id="row_42" value="<?php echo $productdetails[2]['quantity']; ?>" name="purchase[2]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_43"  value="<?php echo $productdetails[2]['shrink']; ?>"  name="purchase[2]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_44" value="<?php echo $productdetails[2]['final_quantity']; ?>" name="purchase[2]['finalqunatity']"  class="form-control"></td>
                        <td><input type="text" id="row_45" value="<?php echo $productdetails[2]['quantity_type_id']; ?>" name="purchase[2]['qauntitytype']"  class="form-control"></td>
                        <td><input type="text" id="row_46"  value="<?php echo $productdetails[2]['rate']; ?>" name="purchase[2]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_47" value="<?php echo $productdetails[2]['amount']; ?>" name="purchase[2]['amount']"  class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_51" value="<?php echo $productdetails[3]['productid']; ?>" name="purchase[3]['id']" class="form-control"></td>
                        <td><input type="text" id="row_52" value="<?php echo $productdetails[3]['quantity']; ?>" name="purchase[3]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_53" value="<?php echo $productdetails[3]['shrink']; ?>"  name="purchase[3]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_54" value="<?php echo $productdetails[3]['final_quantity']; ?>" name="purchase[3]['finalqunatity']"  class="form-control"></td>
                        <td><input type="text" id="row_55" value="<?php echo $productdetails[3]['quantity_type_id']; ?>" name="purchase[3]['qauntitytype']"  class="form-control"></td>
                        <td><input type="text" id="row_56"  value="<?php echo $productdetails[3]['rate']; ?>" name="purchase[3]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_57" value="<?php echo $productdetails[3]['amount']; ?>" name="purchase[3]['amount']"  class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_61" value="<?php echo $productdetails[4]['productid']; ?>" name="purchase[4]['id']" class="form-control"></td>
                        <td><input type="text" id="row_62" value="<?php echo $productdetails[4]['quantity']; ?>" name="purchase[4]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_63" value="<?php echo $productdetails[4]['shrink']; ?>"  name="purchase[4]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_64" value="<?php echo $productdetails[4]['final_quantity']; ?>" name="purchase[4]['finalqunatity']"  class="form-control"></td>
                        <td><input type="text" id="row_65" value="<?php echo $productdetails[4]['quantity_type_id']; ?>" name="purchase[4]['qauntitytype']"  class="form-control"></td>
                        <td><input type="text" id="row_66" value="<?php echo $productdetails[4]['rate']; ?>" name="purchase[4]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_67" value="<?php echo $productdetails[4]['amount']; ?>" name="purchase[4]['amount']"  class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_71" value="<?php echo $productdetails[5]['productid']; ?>" name="purchase[5]['id']" class="form-control"></td>
                        <td><input type="text" id="row_72" value="<?php echo $productdetails[5]['quantity']; ?>" name="purchase[5]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_73" value="<?php echo $productdetails[5]['shrink']; ?>"  name="purchase[5]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_74" value="<?php echo $productdetails[5]['final_quantity']; ?>" name="purchase[5]['finalqunatity']"  class="form-control"></td>
                        <td><input type="text" id="row_75" value="<?php echo $productdetails[5]['quantity_type_id']; ?>" name="purchase[5]['qauntitytype']"  class="form-control"></td>
                        <td><input type="text" id="row_76" value="<?php echo $productdetails[5]['rate']; ?>" name="purchase[5]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_77" value="<?php echo $productdetails[5]['amount']; ?>" name="purchase[5]['amount']"  class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_81" value="<?php echo $productdetails[6]['productid']; ?>" name="purchase[6]['id']" class="form-control"></td>
                        <td><input type="text" id="row_82" value="<?php echo $productdetails[6]['quantity']; ?>" name="purchase[6]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_83" value="<?php echo $productdetails[6]['shrink']; ?>"  name="purchase[6]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_84" value="<?php echo $productdetails[6]['final_quantity']; ?>" name="purchase[6]['finalqunatity']"  class="form-control"></td>
                        <td><input type="text" id="row_85" value="<?php echo $productdetails[6]['quantity_type_id']; ?>" name="purchase[6]['qauntitytype']"  class="form-control"></td>
                        <td><input type="text" id="row_86" value="<?php echo $productdetails[6]['rate']; ?>" name="purchase[6]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_87" value="<?php echo $productdetails[6]['amount']; ?>" name="purchase[6]['amount']"  class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_91" value="<?php echo $productdetails[7]['productid']; ?>" name="purchase[7]['id']" class="form-control"></td>
                        <td><input type="text" id="row_92" value="<?php echo $productdetails[7]['quantity']; ?>" name="purchase[7]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_93" value="<?php echo $productdetails[7]['shrink']; ?>"  name="purchase[7]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_94" value="<?php echo $productdetails[7]['final_quantity']; ?>" name="purchase[7]['finalqunatity']"  class="form-control"></td>
                        <td><input type="text" id="row_95" value="<?php echo $productdetails[7]['quantity_type_id']; ?>" name="purchase[7]['qauntitytype']"  class="form-control"></td>
                        <td><input type="text" id="row_96" value="<?php echo $productdetails[7]['rate']; ?>" name="purchase[7]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_97" value="<?php echo $productdetails[7]['amount']; ?>" name="purchase[7]['amount']"  class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_101" value="<?php echo $productdetails[8]['productid']; ?>" name="purchase[8]['id']" class="form-control"></td>
                        <td><input type="text" id="row_102" value="<?php echo $productdetails[8]['quantity']; ?>" name="purchase[8]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_103" value="<?php echo $productdetails[8]['shrink']; ?>"  name="purchase[8]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_104" value="<?php echo $productdetails[8]['final_quantity']; ?>" name="purchase[8]['finalqunatity']"  class="form-control"></td>
                        <td><input type="text" id="row_105" value="<?php echo $productdetails[8]['quantity_type_id']; ?>" name="purchase[8]['qauntitytype']"  class="form-control"></td>
                        <td><input type="text" id="row_106" value="<?php echo $productdetails[8]['rate']; ?>" name="purchase[8]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_107" value="<?php echo $productdetails[8]['amount']; ?>" name="purchase[8]['amount']"  class="form-control"></td>
                    </tr>

                <tr>
                        <td><input type="text" id="row_111" value="<?php echo $productdetails[9]['productid']; ?>" name="purchase[9]['id']" class="form-control"></td>
                        <td><input type="text" id="row_112" value="<?php echo $productdetails[9]['quantity']; ?>" name="purchase[9]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_113"  value="<?php echo $productdetails[9]['shrink']; ?>"  name="purchase[9]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_114" value="<?php echo $productdetails[9]['final_quantity']; ?>" name="purchase[9]['finalqunatity']"  class="form-control"></td>
                        <td><input type="text" id="row_115" value="<?php echo $productdetails[9]['quantity_type_id']; ?>" name="purchase[9]['qauntitytype']"  class="form-control"></td>
                        <td><input type="text" id="row_116" value="<?php echo $productdetails[9]['rate']; ?>" name="purchase[9]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_117" value="<?php echo $productdetails[9]['amount']; ?>" name="purchase[9]['amount']"  class="form-control"></td>
                    </tr>

              </table>

                </div>

            </div>



    <div class="row">
        <div class="col-md-1" style="margin-top: -19px; margin-left: 4px;">
         <button  onclick="tableone()" type="button" class="buttons btn btn-success">Save</button>
        </div>




    <div class="col-md-5" style="margin-top: 17px; margin-left: -95px;">
            <table>
                <?php
                $selectCommissionQ = "SELECT * FROM `hk_purchase_commission` WHERE purchase_id = $purchaseId";
                $commissionExe = mysqli_query($conn,$selectCommissionQ);
                while($comisonRow = mysqli_fetch_array($commissionExe)){
                    $commissionp = $comisonRow["commission_percentage"];
                    $commissionamount = $comisonRow["commission_amount"];
                }
                ?>

                <tbody>
 <tr>
                                    <th>Total product Amount <span class="requiredfield">*</span></th>
                                    <td><input type="number"  step="0.01" class="purcomm" id="totalPay11" value="<?php echo $totalProductAmount; ?>" placeholder="Product Amount" name="totalPay11" required readonly></td>
                                </tr>
                <tr>
                        <th>
                            Commision in Percentage<span class="requiredfield">*</span>
                        </th>
                        <td>

                        <input type="number" value="<?php echo $commissionp; ?>" class="purcomm" id="comm_percent" onblur="commision()" name="comm_percent" placeholder="Enter percentage"> <span>%</span>
                        </td>
                    </tr>

                    <tr>
                        <th>
                            Commision in Rupees<span class="requiredfield">*</span>
                        </th>
                        <td>
                            <input type="number" value="<?php echo $commissionamount; ?>" class="purcomm1" id="comm_amount" name="comm_amount" placeholder=" commision in rupees"  style="padding-left: 9px;" readonly>
                        <span>INR</span>
                        </td>
                    </tr>




                </tbody>
            </table>
        </div>

            </div>
<!--            <hr>-->


            <div class="row" style="margin-left: 563px; margin-top: -116px;">
          <div class="col-md-8">
              <h6><u>Select Expenses</u></h6>
               <table >
                <tbody>
                    <tr>
                    <th>Expenses</th>
                    <td>
                        <select id="expense"   class="expen" name="expenseslist" style="padding-left: 10px;">
                            <option value="" selected="selected">Select Expense</option>
             <?php
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
                <div class="col-md-4">
               <table>
                <tbody>
                    <tr>
                    <td>
                        <input type="number" id="expensevalue" class="ptext42" name="expensevalue" style="margin-left:14px;margin-top: 27px;" placeholder="Amount" value="0" required>

                    </td>
                </tr>
                 </tbody>
            </table>
      </div>
            </div>
             <div class="row pursave1">

           <button class="buttonsav btn btn-primary" type="button" onclick="addHtmlTableRow1()">Add</button>
                <button class="buttonsav1 btn btn-warning" type="button" onclick="editHtmlTbleSelectedRow1();">Edit</button>
                <button class="buttonsav2 btn btn-danger" type="button" onclick="removeSelectedRow1()">Remove</button>

     </div>

    <div class="row" style="margin-top:27px;margin-left: 577px;">
         <h6><u>Selected Expenses</u></h6>
        <div class="col-md-11">
            <table class="table table-bordered table-sm" id="table" width="100%" cellspacing="0">
              <thead>
                <tr style="font-size: 14px;">
                     <th style="display:none;">Expense ID</th>
                   <th>Expense </th>
                    <th>Amount</th>

                 </tr>
                  <?php
                  $expenseQ = "SELECT HKPE.*,HKET.expenses_type FROM `hk_purchase_expenses` AS HKPE left JOIN hk_expenses_type AS HKET ON HKET.id = HKPE.expense_type_id WHERE HKPE.purchase_id =$purchaseId && HKPE.expenses_active=1";
                    $expenseQuery = mysqli_query($conn,$expenseQ);
                    $totalExpense = 0;
                    $expensecount = 0;
    $expensedetails = array();
                    while($expenseRow = mysqli_fetch_array($expenseQuery)){
                  ?>
                  <tr>
                    <td style="display:none"><?php echo $expenseRow["expense_type_id"]; ?></td>
                    <td><?php echo $expenseRow["expenses_type"]; ?></td>
                    <td><?php echo $expenseRow["amount"]; ?></td>
                  </tr>


                  <?php
                    $totalExpense = $totalExpense +$expenseRow["amount"];


                $expensedetails[$expensecount]['expenses_type']=$expenseRow["expense_type_id"];
                $expensedetails[$expensecount]['amount'] = $expenseRow["amount"];

                        $expensecount++;
                    }
                  ?>
              </thead>
              <tbody>
              </tbody>
            </table>

<!--
            <div class="col-md-1">
         <button  onclick="tableone()" class="buttons btn btn-primary">Save</button>
        </div>
-->
            <button class="buttons1 btn btn-success"
                type="button" onclick="expensetable()">Save
            </button>
<!--
            <table id="resultTotals" width="360">
<tr>
    <td scope="col" width="120">Totals</td>
    <td scope="col" width="120"><div id="priceTotals"></div></td>
</tr>
            </table>
-->
            </div>


            </div>

            <hr>
            <div class="row">

         <div class="col-md-6" style="margin-top:6px;">
                 <h5><u>Payment Details</u></h5>
                     <table>
                            <tbody>
                                <tr>
                                    <th>Total Payable Amount <span class="requiredfield">*</span></th>
                                    <td><input type="text"  step="0.01" class="ptext ptotalpa" id="totalPay" placeholder="Payable Amount" onchange="duecalc()" onkeypress='validate(event)' name="totalPay" value="<?php echo $totalProductAmount-$commissionamount; ?>" required ></td>
                                </tr>
                                <tr>
                                    <th>Total expense Amount <span class="requiredfield">*</span></th>
                                    <td>
                                    <input type="number" step="0.01" class="ptext ptotalpa" id="totalExpense" name="totalExpense" placeholder="Expense Amount" value="<?php echo $totalExpense; ?>" onblur="netamount()" required readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Net Amount <span class="requiredfield">*</span></th>
                                    <td>
                                    <input type="number"  step="0.01" class="ptext ptotalpa" id="netAmount" placeholder="Net Amount" value="<?php echo $amountPayable; ?>" name="netAmount" required readonly>
                                    </td>
                                </tr>
                            </tbody>
                     </table>
                 </div>
      </div>

            <div class="row">

            <div class="col-md-6" style="display:none;">
                <table class="table" >
                    <tbody>
                        <tr>
                            <input type="text" id="expense_21" value="<?php echo $expensedetails[0]['expenses_type']; ?>" name="expenses[0][id]" readonly>
                            <input type="text" id="expense_22" value="<?php echo $expensedetails[0]['amount']; ?>" name="expenses[0][expense]" readonly>
                        </tr>
                        <tr>
                            <input type="text" id="expense_31" value="<?php echo $expensedetails[1]['expenses_type']; ?>" name="expenses[1][id]" readonly>
                            <input type="text" id="expense_32" value="<?php echo $expensedetails[1]['amount']; ?>" name="expenses[1][expense]" readonly>
                        </tr>
                        <tr>
                            <input type="text" id="expense_41" value="<?php echo $expensedetails[2]['expenses_type']; ?>" name="expenses[2][id]" readonly>
                            <input type="text" id="expense_42" value="<?php echo $expensedetails[2]['amount']; ?>" name="expenses[2][expense]" readonly>
                        </tr>
                        <tr>
                            <input type="text" value="<?php echo $expensedetails[3]['expenses_type']; ?>" id="expense_51" name="expenses[3][id]" readonly>
                            <input type="text" id="expense_52" value="<?php echo $expensedetails[3]['amount']; ?>" name="expenses[3][expense]" readonly>
                        </tr>
                        <tr>
                            <input type="text" id="expense_61" value="<?php echo $expensedetails[4]['expenses_type']; ?>" name="expenses[4][id]" readonly>
                            <input type="text" id="expense_62" value="<?php echo $expensedetails[4]['amount']; ?>" name="expenses[4][expense]" readonly>
                        </tr>
                        <tr>
                            <input type="text" id="expense_71" value="<?php echo $expensedetails[5]['expenses_type']; ?>" name="expenses[5][id]" readonly>
                            <input type="text" id="expense_72" value="<?php echo $expensedetails[5]['amount']; ?>" name="expenses[5][expense]" readonly>
                        </tr>
                        <tr>
                            <input type="text" id="expense_81" value="<?php echo $expensedetails[6]['expenses_type']; ?>" name="expenses[6][id]" readonly>
                            <input type="text" id="expense_82" value="<?php echo $expensedetails[6]['amount']; ?>" name="expenses[6][expense]" readonly>
                        </tr>
                        <tr>
                            <input type="text" id="expense_91" value="<?php echo $expensedetails[7]['expenses_type']; ?>" name="expenses[7][id]" readonly>
                            <input type="text" id="expense_92" value="<?php echo $expensedetails[7]['amount']; ?>" name="expenses[7][expense]" readonly>
                        </tr>
                         <tr>
                            <input type="text" id="expense_101" value="<?php echo $expensedetails[8]['expenses_type']; ?>" name="expenses[8][id]" readonly>
                            <input type="text" id="expense_102" value="<?php echo $expensedetails[8]['amount']; ?>" name="expenses[8][expense]" readonly>
                        </tr>
                        <tr>
                            <input type="text" id="expense_111" value="<?php echo $expensedetails[9]['expenses_type']; ?>" name="expenses[9][id]" readonly>
                            <input type="text" id="expense_112" value="<?php echo $expensedetails[9]['amount']; ?>" name="expenses[9][expense]" readonly>
                        </tr>

                    </tbody>
                </table>




                </div>


            </div>


        <div class="row">
              <div class="col-md-12" >
                  <div id="cash1" style="margin-left: 54%; margin-top: -125px;" hidden>
    <div class="row" >

            <h5><u>Payment Transaction Methods</u></h5>

            <label class="radio-inline" style="margin-left:-270px; margin-top:30px;">
      <input type="radio" name="transMethod" id="cashm" value="1" >CASH
    </label>
    <label class="radio-inline" style="margin-top:30px;">
      <input type="radio" name="transMethod" id="chequem" value="2">CHEQUE
    </label>
    </div>
    <div class="row">

                <table>
                    <tr>
                        <th>Total Payable Amount <span class="requiredfield">*</span></th>
                        <td>
                            <input type="text"  step="0.01" class="ptext ptotalpa" id="totalPay1" onchange="duecalc()" onkeypress='validate(event)' value="<?php echo $amountPayable; ?>" name="totalPay" placeholder="payable amount" required >
                        </td>
                    </tr>
                    <tr>
                        <th>Total Amount Paid <span class="requiredfield">*</span></th>
                        <td> <input type="number" step="0.01" class="ptext ptotalam"id="totalPaid" onblur="duecalc()" name="totalPaid" placeholder="amount paid" value="<?php echo $amountpaid; ?>" required></td>

                    </tr>
                    <tr>
                        <th>Due Amount<span class="requiredfield">*</span></th>
                        <td><input type="number"  step="0.01" class="ptext ptotaldue" id="duepay" value="<?php echo $amountPayable-$amountpaid; ?>" placeholder="due amount"  name="duepay" required readonly></td>

                    </tr>
                    <tr style="display:none">
                        <th>PrevDue Amount</th>
                        <td>
                        <input type="text" class="ptext ptotaldue" value="<?php echo $amountPayable-$amountpaid; ?>" name="prevDue" readonly>
                        </td>
                    </tr>


                    <tr>
                        <th>Paid To</th>
                        <td><input type="text" name="paidTo" value="<?php echo $paidTo; ?>" class="ptext ptotaldue" placeholder="Receiver name" ></td>

                    </tr>

                     <tr>
                    <th>Enter Cheque Number</th>
                    <td><input type="text" class="ptext" name="chequeNumber" id="chequenumber" placeholder="Enter cheque number" value="<?php echo $chequeNumber; ?>" style="margin-left: 90px;width: 81%;padding-left:13px;"></td>
                </tr>
                </table>

            </div>
    </div>
            </div>
      </div>

   <div class="row">
            <script>



                $(function(){


                    $("#cash,#credit").click(function(){
                        if($("#cash").is(":checked")){
                                   $("#cash1").removeAttr('hidden');
                                   $("#cash1").show();


                                    $("#totalPay1").prop('required',true);
                                    $("#totalPaid").prop('required',true);
                                    $("#duepay").prop('required',true);
                                    $("#cashm").prop('required',true);


                                    $("#cash").css({"background-color":"black","color":"white"});
                                    $("#credit").css({"background-color":"dimgray"});
                             }
                        else if($("#credit").is(":checked")){
                            $("#cash1").hide();
                            $("#credit").css({"background-color":"black","color":"white"});
                            $("#cash").css({"background-color":"dimgray"});

                             $("#totalPay1").prop('required',false);
                            $("#totalPaid").prop('required',false);
                            $("#duepay").prop('required',false);
                            $("#cashm").prop('required',false);
                        }
                    });
                });

                $(function(){
                    $("#cashm,#chequem").change(function(){
                    $("#chequenumber").val("").attr("readonly",true);
                    if($("#chequem").is(":checked")){
                        $("#chequenumber").removeAttr("readonly");
                        $("#chequenumber").prop('required',true);
                        $("#chequenumber").focus();
                     }else if($("#cashm").is(":checked")){
                         $("#chequenumber").attr("readonly",true);
                         $("#chequenumber").prop('required',false);

                     }
    });
});



            </script>
<script>
    $(document).ready(function(){
var x =$("#cash:checked").val();
        console.log(x);
        if(x==1){

            $("#cash1").removeAttr('hidden');
                                   $("#cash1").show();


               $("#totalPay1").prop('required',true);
               $("#totalPaid").prop('required',true);
               $("#duepay").prop('required',true);
               $("#cashm").prop('required',true);
                $("#cash").css({"background-color":"black","color":"white"});
              $("#credit").css({"background-color":"dimgray"});

//            alert("ok");
        }
        else{

            $("#cash1").hide();
            $("#credit").css({"background-color":"black","color":"white"});
            $("#cash").css({"background-color":"dimgray"});

            $("#totalPay1").prop('required',false);
            $("#totalPaid").prop('required',false);
            $("#duepay").prop('required',false);
            $("#cashm").prop('required',false);

//            alert("sorry");
        }

});
</script>

                  </div>

            <div class="row pursubmit">

<!--           <button class="buttonreset"><a href="purchase_entry_list.php" style="color: white; text-decoration: none;">Cancel  </a></button>-->


           <button class="buttonsubmit" type="submit"><a >Submit</a></button>
   <a href="purchase_entry_list.php" style=" text-decoration: none;" class="buttonreset">  <span >Cancel</span></a>




     </div>

  </form>
      </div>

        <!-- end of customer deatils-->
  </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>MAHAT INNOVATIONS </small>
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

            <form method="post" action="logout_handler.php">
                <button type="submit" name="logout" class="btn btn-primary">Logout</button>
            </form>
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
<script src="../js/supplierdetails.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


      <script>
          function netweight1(){
             var x = $('#accountno').val();
             var y = $('#emptyw').val();
              var result = (parseFloat(x)-parseFloat(y))*-1;

              $('#netweight').val(result);
          }

          function finalQuantity(){

              var unitprice = $('#city').val();
              var loaded = $('#shrink1').val();
              var shrink = $('#shrink').val();
              var final = (parseFloat(loaded)-parseFloat(shrink));
                $('#final').val(final);
              var total = (parseFloat(unitprice)*final);
              $('#total').val(total);
          }

      </script>
      <script>

          function  commision(){
          var amount = $("#totalPay11").val();
//          var advance = $("#advance").val();
          var commisionPerent = $("#comm_percent").val();
          var commision = parseFloat(amount*(commisionPerent/100));
          var payableamount = parseFloat(amount - commision);
            $("#totalPay").val(payableamount);
          $("#comm_amount").val(commision);
          }

           function expenses(){
              var amountCal = $("#totalPay11").val();

              var com = $("#comm_amount").val();

var pay = parseFloat(amountCal)-parseFloat(com);
              $("#totalPay").val(pay);
           }

                    function duecalc(){
              var totalpayable = $("#netAmount").val();
              var totalpaid = $("#totalPaid").val();
              var due = parseFloat(totalpayable)-parseFloat(totalpaid);
              $("#duepay").val(due);

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
      <script src="../js/jquery-3.2.1.min.js"></script>
      <script src="../js/select2.min.js"></script>
    <script src="../js/table.js"></script>

       <script>
        $(document).ready(function(){

            // Initialize select2
            $("#employee").select2();

            // Read selected option
            $('#but_read').click(function(){
                var username = $('#employee option:selected').text();
                var userid = $('#employee').val();
            });
        });
        </script>

<script>
    function setadvance(){
        if($("#advance").val()=="")
        {
            $("#advance").val("0");
        }

    }
</script>

    <script>
            var rIndex,
                table = document.getElementById("dataTable");

            // check the empty input
            function checkEmptyInput()
            {
                var isEmpty = false,
                    product_id = $("#address option:selected").val(),
                    product_name = $("#address option:selected").html(),
                    Qtys = document.getElementById("shrink1").value,
                    shrinkWeight = document.getElementById("shrink").value,
                    finalQuantity= document.getElementById("final").value,
                    unitPrice = document.getElementById("city").value,
                    totalAmount = document.getElementById("total").value;
        }

        function clearing(){
           $("#shrink1").val(0);
            $("#shrink").val(0);
            $("#final").val(0);
            $("#shrink2").val(0);
            $("#city").val(0);
              $("#total").val(0);
              $("#address").val(0);
        }

            // add Row
            function addHtmlTableRow()
            {
                // get the table by id
                // create a new row and cells
                // get value from input text
                // set the values into row cell's
                if(!checkEmptyInput()){
                var newRow = table.insertRow(table.length),
                    cell1 = newRow.insertCell(0),
                    cell2 = newRow.insertCell(1),
                    cell3 = newRow.insertCell(2),
                    cell4 = newRow.insertCell(3),
                    // cell5 = newRow.insertCell(4),
                    cell6 = newRow.insertCell(4),
                    cell7 = newRow.insertCell(5),
                    // cell8 = newRow.insertCell(6),
                    cell9 = newRow.insertCell(6),
//                    cell10 = newRow.insertCell(9),

                    product_id = $("#address option:selected").val(),
                    product_name = $("#address option:selected").html(),
                    Qtys = document.getElementById("shrink1").value,
                    shrinkWeight = document.getElementById("shrink").value,
                    finalQuantity= document.getElementById("final").value,
                    // Qtys1 = document.getElementById("shrink2").value,
                    // Qtytype = $("#shrink2 option:selected").html(),
                    unitPrice = document.getElementById("city").value,

                    totalAmount = document.getElementById("total").value;


                        cell1.innerHTML =  product_name;
                        cell2.innerHTML =  Qtys ;
                        cell3.innerHTML =  shrinkWeight;
                        cell4.innerHTML =  finalQuantity;
                        // cell5.innerHTML = Qtytype ;
                        cell6.innerHTML = unitPrice;
                        cell7.innerHTML = totalAmount ;
                        // cell8.innerHTML = Qtys1 ;
                        cell9.innerHTML = product_id;
                selectedRowToInput();
                     colum();
                    clearing();

            }
            }

            // display selected row data into input text
            function selectedRowToInput()
            {

                for(var i = 1; i < table.rows.length; i++)
                {
                    table.rows[i].onclick = function()
                    {
                      // get the seected row index

                      rIndex = this.rowIndex;
                        $('#address').val(this.cells[6].innerHTML);
                        $('#shrink1').val(this.cells[1].innerHTML);
                        $('#shrink').val(this.cells[2].innerHTML);
                        $('#final').val(this.cells[3].innerHTML);
                        // $('#shrink2').val(this.cells[7].innerHTML);
                        $('#city').val(this.cells[4].innerHTML);
                        $('#total').val(this.cells[5].innerHTML);

                    };
                }
            }
            selectedRowToInput();


           function editHtmlTbleSelectedRow()
           {
               var
          product_id = document.getElementById("address").value,
          product_name = $("#address option:selected").html(),
            Qtys = document.getElementById("shrink1").value,
            shrinkWeight = document.getElementById("shrink").value,
            finalQuantity= document.getElementById("final").value,
            // Qtys1 = $("#shrink2 option:selected").html(),
            unitPrice = document.getElementById("city").value,
            // Qtys2 = $("#shrink2 option:selected").val(),
            totalAmount = document.getElementById("total").value;


              if(!checkEmptyInput()){
            table.rows[rIndex].cells[0].innerHTML = product_name;
            table.rows[rIndex].cells[1].innerHTML =  Qtys;
            table.rows[rIndex].cells[2].innerHTML = shrinkWeight;
            table.rows[rIndex].cells[3].innerHTML =  finalQuantity;
            // table.rows[rIndex].cells[4].innerHTML = Qtys1 ;
            table.rows[rIndex].cells[4].innerHTML =  unitPrice;
            table.rows[rIndex].cells[5].innerHTML = totalAmount;
            // table.rows[rIndex].cells[7].innerHTML = Qtys2;
            table.rows[rIndex].cells[7].innerHTML = product_id;

             }
           }

            function removeSelectedRow()
            {
                table.deleteRow(rIndex);
                 $('#address option:selected').val(this.cells[0]? this.cells[0].innerHTML:'');
                        $('#shrink1').val(this.cells[1].innerHTML);
                        $('#shrink').val(this.cells[2].innerHTML);
                        $('#final').val(this.cells[3].innerHTML);
                        // $('#shrink2').val(this.cells[4].innerHTML);
                        $('#city').val(this.cells[5].innerHTML);
                        $('#total').val(this.cells[6].innerHTML);
            }


        </script>

     <script>


                  function tableone(){
                      var sum = 0;
                      for(row = 2; row<13;row++){
                           var xvalue =[];


                               xvalue[0] = $("#dataTable > thead > tr:nth-child(" + row+ ") > td:nth-child(7)").text();
                            xvalue[1] = $("#dataTable > thead > tr:nth-child(" + row+ ") > td:nth-child(2)").text();

                          xvalue[2] = $("#dataTable > thead > tr:nth-child(" + row+ ") > td:nth-child(3)").text();

                          xvalue[3] = $("#dataTable > thead > tr:nth-child(" + row+ ") > td:nth-child(4)").text();
                           xvalue[4] = $("#dataTable > thead > tr:nth-child(" + row+ ") > td:nth-child(8)").text();
                           xvalue[5] = $("#dataTable > thead > tr:nth-child(" + row+ ") > td:nth-child(5)").text();
                           xvalue[6] = $("#dataTable > thead > tr:nth-child(" + row+ ") > td:nth-child(6)").text();

                          var value = $("#dataTable > thead > tr:nth-child(" + row+ ") > td:nth-child(6)").text();

                                $('#row_'+ row +1 ).val(xvalue[0]);
                                $('#row_'+ row +2 ).val(xvalue[1]);
                                $('#row_'+ row +3 ).val(xvalue[2]);
                                $('#row_'+ row +4 ).val(xvalue[3]);
                                $('#row_'+ row +5 ).val(xvalue[4]);
                                $('#row_'+ row +6 ).val(xvalue[5]);
                                $('#row_'+ row +7 ).val(xvalue[6]);









                          if(!isNaN(value) && value.length !=0){
                              sum += parseFloat(value);
                          }
//                          $("#totalPay").val(sum);
                          $("#totalPay11").val(sum);

                      }



                  }

      function getSum(total, num) {
              return total + num;
          }




      //expense module

     function expensetable(){
          //expense module
         var expenseSum = 0;
                          for(exrow = 2; exrow<13;exrow++){

                           var expense =[];
                          expense[0] = $("#table > thead > tr:nth-child("+ exrow +") > td:nth-child(1)").text();
                          expense[1] = $("#table > thead > tr:nth-child("+ exrow +") > td:nth-child(3)").text();

                              var toatalExpense = $("#table > thead > tr:nth-child("+ exrow +") > td:nth-child(3)").text();


                          $("#expense_"+ exrow +1).val(expense[0]);
                          $("#expense_"+ exrow +2).val(expense[1]);


                          if(!isNaN(toatalExpense) && toatalExpense.length !=0){
                              expenseSum += parseFloat(toatalExpense);
                          }
                          $("#totalExpense").val(expenseSum);


                          }

     }




</script>

    <script>
        function netamount(){
          var amount = $("#totalPay").val();
          var totalExpeses = $("#totalExpense").val();

        var advanceReceived = $("#advancepaid").val();
        var balanceReceived = $("#balpaid").val();
          var netAmount = parseFloat(amount)-parseFloat(totalExpeses)-parseFloat(advanceReceived)-parseFloat(balanceReceived);
         $("#netAmount").val(netAmount);
            console.log(netAmount);
         $("#totalPay1").val(netAmount)
      }

    </script>


    <script>
        function colum(){


        for(pro = 2;pro<13; pro++){
                $("#dataTable > thead > tr:nth-child("+pro+") > td:nth-child(8)").css('display','none');
             $("#dataTable > thead > tr:nth-child("+pro+") > td:nth-child(9)").css('display','none');
        }

        }

    </script>




</body>

</html>
<?php } ?>
