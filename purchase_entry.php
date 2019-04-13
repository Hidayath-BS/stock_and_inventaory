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
  <link href="css/sb-admin1.css" rel="stylesheet">
    <link href="css/purchaseentry.css" rel="stylesheet">
    <!--    search dropdown-->
    <link href="css/select2.min.css" rel="stylesheet">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="script/getData.js"></script>
    <script src="script/purchseQuantity.js"></script>
    <script>

        function columsi(){


        for(ex = 2;ex<8; ex++){
//
    $("#table > thead > tr:nth-child("+ex+") > td:nth-child(1)").css('display','none');


        }

        }





    </script>
    <style type="text/css">

      #dataTable > thead > tr:nth-child(n) > td:nth-child(7){
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
                <h5 style="8px 2px -20px 15px; margin-left:24px;"><u>Purchase Entry</u></h5>
                <pre style="float:right;margin-top:-30px;">                                                                                   (Note:Fields with <i class="fa fa-asterisk" style="font-size:10px;color:red;"></i> make are compulsory)</pre>
            </div>
        <form class="cust_line" id="purchase_form" method="post"  action="purchase_entry_module/purchase_entry_creation_handler1.php">
<div class="row" style="margin-top:-12px; margin-left: 1px;">
            <h5><u>Purchase Transaction Type :</u></h5>


 <label class="radio-inline" style="margin-left: 20px; margin-top: -10px;">
      <input type="radio" name="transType" id="cash" value="1" required>CASH
    </label>
    <label class="radio-inline" style="margin-left: 18px; margin-top: -10px;">
      <input type="radio" name="transType" id="credit" value="2" checked>CREDIT
    </label>


    <label for="date" class="adddate1">Purchase Date<span class="requiredfield">*</span></label>
     <input type="date" id="ondate" class="adddate" name="ondate" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>">
            </div>
<div class="row" style="margin-top: 15px;">
        <div class="col-md-4" >
            <table>
                <tbody>
                <tr>
                    <th>Supplier Name<span class="requiredfield">*</span></th>
                    <td>
                        <select id="employee" class="ptext" name="supplier_id" >
                            <option selected="selected">Select Supplier Name</option>
                <?php
                        $sql = "SELECT id,first_name,last_name FROM hk_persons WHERE person_type_id = 1";
                        $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
                        while( $rows = mysqli_fetch_assoc($resultset) ) {
                    ?>
                <option value="<?php echo $rows["id"]; ?>"><?php echo $rows["first_name"]." ".$rows["last_name"]; ?></option>
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
                            $calcBillQ = "select MAX(id) as billnum from `hk_purchases` WHERE bill_date BETWEEN '$fromDate' and '$toDate'";
                            $calcBillExe = mysqli_query($conn,$calcBillQ);
                            while($calcBillRow = mysqli_fetch_array($calcBillExe)){
                                $billval = $calcBillRow["billnum"];
                            }
                            $billval +=1;
//                            $timestamp = date('His');
//                            $billNumber = "AK/P$timestamp".sprintf("%04d",$billval);
                    $billNumber =$billval;
                            ?>
                          <input type="text" id="address1" class="ptext" name="billNumber" value="<?php echo $billNumber; ?>" placeholder="   Enter bill number" required readonly></td>
                    </tr>
                     <tr>
                        <th>
                        Vehicle Number
                        </th>
                        <td>
                        <input type="text" id="phone1" class="ptext" name="vehicleNumber" placeholder="Enter vehicle number" >
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

                    <input type="number" id="accountno" class="ptext1" name="emptyWeight" onchange="netweight1()" placeholder="Enter Empty weight"><span> Kg</span>

                    </td>
                </tr>
                     <tr>
                    <th>Loaded weight</th>
                    <td>
                        <input type="number" id="emptyw" class="ptext1" name="loadedWeight" onchange="netweight1()" placeholder="Enter Loaded weight"><span> Kg</span>
<!--                        <label style="padding:0px;margin-bottom:0px">TONNE</label>-->

                    </td>
                </tr>
                      <tr>
                        <th>Net Weight</th>
                        <td>
                            <input type="number" id="netweight" class="ptext1" name="netWeight"  style="margin-bottom:4px;padding-left:12px;" placeholder="Enter Net weight" readonly><span> Kg</span>
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
                            <input type="text" id="phone" class="ptext2" name="weighBillNo" style="margin-bottom:4px;padding-left:1px;" placeholder="   Enter weigh bill number" >

                        </td>
                    </tr>




                    <tr>
                        <th>
                        Location
                        </th>
                        <td>
                        <input type="text" id="location" class="ptext2" name="location" placeholder=" Enter Location" style="padding-left:9px;" >
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
                    <th>Product Id<span class="requiredfield">*</span></th>
                    <td><input type="number" id="product_id" class="ptext21" name="product_id">
                      <button class="btn btn-default" type="button" onclick="enterprod()">OK</button>
                    </td>
                  </tr>
                <tr>


                    <th style="width:42%;">Product name</th>
                    <td>
                        <select id="address"  class="ptextp" name="product_id" >
                            <option value="" selected="selected">Select Product Name</option>
             <?php
             $productQuery = "select * from `hk_products`";
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
                        <input type="number" id="shrink1" class="ptext21" name="Qtys"   placeholder="Enter shrink.." value="0" required>
<!--                        <label style="padding:0px;margin-bottom:0px">Kg</label>-->


                    </td>
                </tr>
                <tr>
                    <th>Petch %</th>
                    <td>
<!--                        <input type="number" id="" class="ptext31" name="petch%" 
onchange="finalQuantity()"  placeholder="Enter shrink.." value="0" required>-->

                        <input type="number" id="petch" class="ptext31" name="petch%" 
                        onkeydown="petchCalculation()"  placeholder="Enter petch" value="0" required >


                    </td>
                </tr>
                     <tr>
                    <th>Shrink Weight</th>
                    <td>
<!--                        <input type="number" id="shrink" class="ptext31" name="shrinkWeight" onchange="finalQuantity()"  placeholder="Enter shrink.." value="0" required>-->

                        <input type="number" id="shrink" class="ptext31" name="shrinkWeight" onkeydown="pfinalQuantity()" 
                         placeholder="Enter shrink" value="0" required >


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
                            <input type="number" id="final" class="ptext15" name="finalQunatity"  style="margin-bottom:1px;padding-left: 2px;" 
                            placeholder="   Enter final quantity"  required>
                        </td>
                    </tr>
                 <tr>
                    <th>Unit Price<span class="requiredfield">*</span></th>
                    <td>
                    <input type="number" id="city" class="ptext22" name="unitPrice" onkeydown="finalQuantity()" placeholder="Enter unit price"required>  <span>INR</span>
<!--                        <label style="padding:0px;margin-bottom:0px">INR</label>-->
                    </td>
                </tr>

                    <tr>
                        <th>Amount<span class="requiredfield">*</span></th>
                        <td>
                            <input type="number" id="total" class="ptext15" name="totalAmount" placeholder="Enter total amount" onchange="setadvance()" style="padding-left: 12px"  required readonly>
                        </td>
                    </tr>


                </tbody>
            </table>



     <div class="row pursave">
           <button class="buttonsave btn btn-primary" type="button" onclick="addHtmlTableRow()">Add</button>
                <button class="buttonsave1 btn btn-warning" type="button" onclick="editHtmlTbleSelectedRow();">Edit</button>
                <button class="buttonsave2 btn btn-danger" type="button" onclick="removeSelectedRow()">Remove</button>

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


                      <tr hidden>
                        <th>
                            Advance Bal. Receivable<span class="requiredfield">*</span>
                        </th>
                        <td>
                            <input type="number" id="advance" class="ptext33" name="advance" value="0" placeholder="Advance" required readonly>
                        </td>
                    </tr>
                 <tr hidden>
                        <th>
                        Advance Bal. Received<span class="requiredfield">*</span>
                        </th>
                        <td>
                        <input type="number" id="advancepaid" class="ptext33" name="advancepaid" value="0">
                        </td>
                    </tr>

                    <tr>
                        <th>Previous Bal. Receivable<span class="requiredfield">*</span></th>
                        <td>
                        <input type="number" id="balpayble" class="ptext33" name="balpayble" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th>Previous Bal. Received<span class="requiredfield">*</span></th>
                        <td>
                        <input type="number" id="balpaid" class="ptext33" value = "0" name="balpaid">
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
                   <!--  <th>Quantity Type</th> -->
                     <th>Unit Price</th>
                    <th>Amount</th>
                 </tr>
              </thead>


              <tbody>



              </tbody>
            </table>
          </div>

        </div>

<!--
        <div class="row" style="margin-right:1%; margin-bottom:5px;">

                <div class="col-md-12">
                    <button onclick="tableone()" class="btn btn-default" style="float: right;" >SAVE</button>
                </div>
            </div>
-->

            <div class="row">
            <div class="col-md-12">

                <table class="table" id="inputtable" style="display:none;" >

                    <tr>
                        <td><input type="text" id="row_21" name="purchase[0]['id']" class="form-control"></td>
                        <td><input type="text" id="row_22" name="purchase[0]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_23" name="purchase[0]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_24" name="purchase[0]['finalqunatity']" class="form-control"></td>
                        <!-- <td><input type="text" id="row_25" name="purchase[0]['qauntitytype']" class="form-control"></td> -->
                        <td><input type="text" id="row_26" name="purchase[0]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_27" name="purchase[0]['amount']" class="form-control"></td>
                    </tr>
                  <tr>
                        <td><input type="text" id="row_31" name="purchase[1]['id']" class="form-control"></td>
                        <td><input type="text" id="row_32" name="purchase[1]['quantity']"  class="form-control"></td>
                        <td><input type="text" id="row_33" name="purchase[1]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_34" name="purchase[1]['finalqunatity']" class="form-control"></td>
                        <!-- <td><input type="text" id="row_35" name="purchase[1]['qauntitytype']"  class="form-control"></td> -->
                        <td><input type="text" id="row_36" name="purchase[1]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_37" name="purchase[1]['amount']"  class="form-control"></td>

                    </tr>
                  <tr>
                        <td><input type="text" id="row_41" name="purchase[2]['id']" class="form-control"></td>
                        <td><input type="text" id="row_42" name="purchase[2]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_43"  name="purchase[2]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_44" name="purchase[2]['finalqunatity']"  class="form-control"></td>
                        <!-- <td><input type="text" id="row_45" name="purchase[2]['qauntitytype']"  class="form-control"></td> -->
                        <td><input type="text" id="row_46" name="purchase[2]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_47" name="purchase[2]['amount']"  class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_51" name="purchase[3]['id']" class="form-control"></td>
                        <td><input type="text" id="row_52" name="purchase[3]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_53"  name="purchase[3]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_54" name="purchase[3]['finalqunatity']"  class="form-control"></td>
                        <!-- <td><input type="text" id="row_55" name="purchase[3]['qauntitytype']"  class="form-control"></td> -->
                        <td><input type="text" id="row_56" name="purchase[3]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_57" name="purchase[3]['amount']"  class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_61" name="purchase[4]['id']" class="form-control"></td>
                        <td><input type="text" id="row_62" name="purchase[4]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_63"  name="purchase[4]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_64" name="purchase[4]['finalqunatity']"  class="form-control"></td>
                        <!-- <td><input type="text" id="row_65" name="purchase[4]['qauntitytype']"  class="form-control"></td> -->
                        <td><input type="text" id="row_66" name="purchase[4]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_67" name="purchase[4]['amount']"  class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_71" name="purchase[5]['id']" class="form-control"></td>
                        <td><input type="text" id="row_72" name="purchase[5]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_73"  name="purchase[5]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_74" name="purchase[5]['finalqunatity']"  class="form-control"></td>
                        <!-- <td><input type="text" id="row_75" name="purchase[5]['qauntitytype']"  class="form-control"></td> -->
                        <td><input type="text" id="row_76" name="purchase[5]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_77" name="purchase[5]['amount']"  class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_81" name="purchase[6]['id']" class="form-control"></td>
                        <td><input type="text" id="row_82" name="purchase[6]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_83"  name="purchase[6]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_84" name="purchase[6]['finalqunatity']"  class="form-control"></td>
                        <!-- <td><input type="text" id="row_85" name="purchase[6]['qauntitytype']"  class="form-control"></td> -->
                        <td><input type="text" id="row_86" name="purchase[6]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_87" name="purchase[6]['amount']"  class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_91" name="purchase[7]['id']" class="form-control"></td>
                        <td><input type="text" id="row_92" name="purchase[7]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_93"  name="purchase[7]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_94" name="purchase[7]['finalqunatity']"  class="form-control"></td>
                        <!-- <td><input type="text" id="row_95" name="purchase[7]['qauntitytype']"  class="form-control"></td> -->
                        <td><input type="text" id="row_96" name="purchase[7]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_97" name="purchase[7]['amount']"  class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_101" name="purchase[8]['id']" class="form-control"></td>
                        <td><input type="text" id="row_102" name="purchase[8]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_103"  name="purchase[8]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_104" name="purchase[8]['finalqunatity']"  class="form-control"></td>
                        <!-- <td><input type="text" id="row_105" name="purchase[8]['qauntitytype']"  class="form-control"></td> -->
                        <td><input type="text" id="row_106" name="purchase[8]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_107" name="purchase[8]['amount']"  class="form-control"></td>
                    </tr>

                <tr>
                        <td><input type="text" id="row_111" name="purchase[9]['id']" class="form-control"></td>
                        <td><input type="text" id="row_112" name="purchase[9]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_113"  name="purchase[9]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_114" name="purchase[9]['finalqunatity']"  class="form-control"></td>
                        <!-- <td><input type="text" id="row_115" name="purchase[9]['qauntitytype']"  class="form-control"></td> -->
                        <td><input type="text" id="row_116" name="purchase[9]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_117" name="purchase[9]['amount']"  class="form-control"></td>
                    </tr>

                  <tr>
                        <td><input type="text" id="row_121" name="purchase[10]['id']" class="form-control"></td>
                        <td><input type="text" id="row_122" name="purchase[10]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_123"  name="purchase[10]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_124" name="purchase[10]['finalqunatity']"  class="form-control"></td>
                        <!-- <td><input type="text" id="row_115" name="purchase[9]['qauntitytype']"  class="form-control"></td> -->
                        <td><input type="text" id="row_126" name="purchase[10]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_127" name="purchase[10]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_131" name="purchase[11]['id']" class="form-control"></td>
                        <td><input type="text" id="row_132" name="purchase[11]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_133"  name="purchase[11]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_134" name="purchase[11]['finalqunatity']"  class="form-control"></td>
                        <!-- <td><input type="text" id="row_115" name="purchase[9]['qauntitytype']"  class="form-control"></td> -->
                        <td><input type="text" id="row_136" name="purchase[11]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_137" name="purchase[11]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_141" name="purchase[12]['id']" class="form-control"></td>
                        <td><input type="text" id="row_142" name="purchase[12]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_143"  name="purchase[12]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_144" name="purchase[12]['finalqunatity']"  class="form-control"></td>
                        <!-- <td><input type="text" id="row_115" name="purchase[9]['qauntitytype']"  class="form-control"></td> -->
                        <td><input type="text" id="row_146" name="purchase[12]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_147" name="purchase[12]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_151" name="purchase[13]['id']" class="form-control"></td>
                        <td><input type="text" id="row_152" name="purchase[13]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_153"  name="purchase[13]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_154" name="purchase[13]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_156" name="purchase[13]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_157" name="purchase[13]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_161" name="purchase[14]['id']" class="form-control"></td>
                        <td><input type="text" id="row_162" name="purchase[14]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_163"  name="purchase[14]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_164" name="purchase[14]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_166" name="purchase[14]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_167" name="purchase[14]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_171" name="purchase[15]['id']" class="form-control"></td>
                        <td><input type="text" id="row_172" name="purchase[15]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_173" name="purchase[15]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_174" name="purchase[15]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_176" name="purchase[15]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_177" name="purchase[15]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_181" name="purchase[16]['id']" class="form-control"></td>
                        <td><input type="text" id="row_182" name="purchase[16]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_183" name="purchase[16]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_184" name="purchase[16]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_186" name="purchase[16]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_187" name="purchase[16]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_191" name="purchase[17]['id']" class="form-control"></td>
                        <td><input type="text" id="row_192" name="purchase[17]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_193" name="purchase[17]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_194" name="purchase[17]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_196" name="purchase[17]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_197" name="purchase[17]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_201" name="purchase[18]['id']" class="form-control"></td>
                        <td><input type="text" id="row_202" name="purchase[18]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_203" name="purchase[18]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_204" name="purchase[18]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_206" name="purchase[18]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_207" name="purchase[18]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_211" name="purchase[19]['id']" class="form-control"></td>
                        <td><input type="text" id="row_212" name="purchase[19]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_213" name="purchase[19]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_214" name="purchase[19]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_216" name="purchase[19]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_217" name="purchase[19]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_221" name="purchase[20]['id']" class="form-control"></td>
                        <td><input type="text" id="row_222" name="purchase[20]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_223" name="purchase[20]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_224" name="purchase[20]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_226" name="purchase[20]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_227" name="purchase[20]['amount']"  class="form-control"></td>
                    </tr>

                     <tr>
                        <td><input type="text" id="row_231" name="purchase[21]['id']" class="form-control"></td>
                        <td><input type="text" id="row_232" name="purchase[21]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_233" name="purchase[21]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_234" name="purchase[21]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_236" name="purchase[21]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_237" name="purchase[21]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_241" name="purchase[22]['id']" class="form-control"></td>
                        <td><input type="text" id="row_242" name="purchase[22]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_243" name="purchase[22]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_244" name="purchase[22]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_246" name="purchase[22]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_247" name="purchase[22]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_251" name="purchase[23]['id']" class="form-control"></td>
                        <td><input type="text" id="row_252" name="purchase[23]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_253" name="purchase[23]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_254" name="purchase[23]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_256" name="purchase[23]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_257" name="purchase[23]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_261" name="purchase[24]['id']" class="form-control"></td>
                        <td><input type="text" id="row_262" name="purchase[24]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_263" name="purchase[24]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_264" name="purchase[24]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_266" name="purchase[24]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_267" name="purchase[24]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_271" name="purchase[25]['id']" class="form-control"></td>
                        <td><input type="text" id="row_272" name="purchase[25]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_273" name="purchase[25]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_274" name="purchase[25]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_276" name="purchase[25]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_277" name="purchase[25]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_281" name="purchase[26]['id']" class="form-control"></td>
                        <td><input type="text" id="row_282" name="purchase[26]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_283" name="purchase[26]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_284" name="purchase[26]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_286" name="purchase[26]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_287" name="purchase[26]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_291" name="purchase[27]['id']" class="form-control"></td>
                        <td><input type="text" id="row_292" name="purchase[27]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_293" name="purchase[27]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_294" name="purchase[27]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_296" name="purchase[27]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_297" name="purchase[27]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_301" name="purchase[28]['id']" class="form-control"></td>
                        <td><input type="text" id="row_302" name="purchase[28]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_303" name="purchase[28]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_304" name="purchase[28]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_306" name="purchase[28]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_307" name="purchase[28]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_311" name="purchase[29]['id']" class="form-control"></td>
                        <td><input type="text" id="row_312" name="purchase[29]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_313" name="purchase[29]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_314" name="purchase[29]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_316" name="purchase[29]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_317" name="purchase[29]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_321" name="purchase[30]['id']" class="form-control"></td>
                        <td><input type="text" id="row_322" name="purchase[30]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_323" name="purchase[30]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_324" name="purchase[30]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_326" name="purchase[30]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_327" name="purchase[30]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_331" name="purchase[31]['id']" class="form-control"></td>
                        <td><input type="text" id="row_332" name="purchase[31]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_333" name="purchase[31]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_334" name="purchase[31]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_336" name="purchase[31]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_337" name="purchase[31]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_341" name="purchase[32]['id']" class="form-control"></td>
                        <td><input type="text" id="row_342" name="purchase[32]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_343" name="purchase[32]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_344" name="purchase[32]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_346" name="purchase[32]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_347" name="purchase[32]['amount']"  class="form-control"></td>
                    </tr>

                     <tr>
                        <td><input type="text" id="row_351" name="purchase[33]['id']" class="form-control"></td>
                        <td><input type="text" id="row_352" name="purchase[33]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_353" name="purchase[33]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_354" name="purchase[33]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_356" name="purchase[33]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_357" name="purchase[33]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_361" name="purchase[34]['id']" class="form-control"></td>
                        <td><input type="text" id="row_362" name="purchase[34]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_363" name="purchase[34]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_364" name="purchase[34]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_366" name="purchase[34]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_367" name="purchase[34]['amount']"  class="form-control"></td>
                    </tr>

                     <tr>
                        <td><input type="text" id="row_371" name="purchase[35]['id']" class="form-control"></td>
                        <td><input type="text" id="row_372" name="purchase[35]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_373" name="purchase[35]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_374" name="purchase[35]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_376" name="purchase[35]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_377" name="purchase[35]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_381" name="purchase[36]['id']" class="form-control"></td>
                        <td><input type="text" id="row_382" name="purchase[36]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_383" name="purchase[36]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_384" name="purchase[36]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_386" name="purchase[36]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_387" name="purchase[36]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_391" name="purchase[37]['id']" class="form-control"></td>
                        <td><input type="text" id="row_392" name="purchase[37]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_393" name="purchase[37]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_394" name="purchase[37]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_396" name="purchase[37]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_397" name="purchase[37]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_401" name="purchase[38]['id']" class="form-control"></td>
                        <td><input type="text" id="row_402" name="purchase[38]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_403" name="purchase[38]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_404" name="purchase[38]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_406" name="purchase[38]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_407" name="purchase[38]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_411" name="purchase[39]['id']" class="form-control"></td>
                        <td><input type="text" id="row_412" name="purchase[39]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_413" name="purchase[39]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_414" name="purchase[39]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_416" name="purchase[39]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_417" name="purchase[39]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_421" name="purchase[40]['id']" class="form-control"></td>
                        <td><input type="text" id="row_422" name="purchase[40]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_423" name="purchase[40]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_424" name="purchase[40]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_426" name="purchase[40]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_427" name="purchase[40]['amount']"  class="form-control"></td>
                    </tr>

                     <tr>
                        <td><input type="text" id="row_431" name="purchase[41]['id']" class="form-control"></td>
                        <td><input type="text" id="row_432" name="purchase[41]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_433" name="purchase[41]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_434" name="purchase[41]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_436" name="purchase[41]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_437" name="purchase[41]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_441" name="purchase[42]['id']" class="form-control"></td>
                        <td><input type="text" id="row_442" name="purchase[42]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_443" name="purchase[42]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_444" name="purchase[42]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_446" name="purchase[42]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_447" name="purchase[42]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_451" name="purchase[43]['id']" class="form-control"></td>
                        <td><input type="text" id="row_452" name="purchase[43]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_453" name="purchase[43]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_454" name="purchase[43]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_456" name="purchase[43]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_457" name="purchase[43]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_461" name="purchase[44]['id']" class="form-control"></td>
                        <td><input type="text" id="row_462" name="purchase[44]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_463" name="purchase[44]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_464" name="purchase[44]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_466" name="purchase[44]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_467" name="purchase[44]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_471" name="purchase[45]['id']" class="form-control"></td>
                        <td><input type="text" id="row_472" name="purchase[45]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_473" name="purchase[45]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_474" name="purchase[45]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_476" name="purchase[45]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_477" name="purchase[45]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_481" name="purchase[46]['id']" class="form-control"></td>
                        <td><input type="text" id="row_482" name="purchase[46]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_483" name="purchase[46]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_484" name="purchase[46]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_486" name="purchase[46]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_487" name="purchase[46]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_491" name="purchase[47]['id']" class="form-control"></td>
                        <td><input type="text" id="row_492" name="purchase[47]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_493" name="purchase[47]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_494" name="purchase[47]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_496" name="purchase[47]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_497" name="purchase[47]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_501" name="purchase[48]['id']" class="form-control"></td>
                        <td><input type="text" id="row_502" name="purchase[48]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_503" name="purchase[48]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_504" name="purchase[48]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_506" name="purchase[48]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_507" name="purchase[48]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_511" name="purchase[49]['id']" class="form-control"></td>
                        <td><input type="text" id="row_512" name="purchase[49]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_513" name="purchase[49]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_514" name="purchase[49]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_516" name="purchase[49]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_517" name="purchase[49]['amount']"  class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="text" id="row_521" name="purchase[50]['id']" class="form-control"></td>
                        <td><input type="text" id="row_522" name="purchase[50]['quantity']" class="form-control"></td>
                        <td><input type="text" id="row_523" name="purchase[50]['shrink']" class="form-control"></td>
                        <td><input type="text" id="row_524" name="purchase[50]['finalqunatity']"  class="form-control"></td>

                        <td><input type="text" id="row_526" name="purchase[50]['unitprice']"  class="form-control"></td>
                        <td><input type="text" id="row_527" name="purchase[50]['amount']"  class="form-control"></td>
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
                <tbody>
 <tr>
                                    <th>Total product Amount <span class="requiredfield">*</span></th>
                                    <td><input type="number"  step="0.01" class="purcomm" id="totalPay11" placeholder="Product Amount" name="totalPay11" required readonly></td>
                                </tr>
                <tr>
                        <th>
                            Commision in Percentage<span class="requiredfield">*</span>
                        </th>
                        <td>

                        <input type="number" value="" class="purcomm" id="comm_percent" onchange="commision()" name="comm_percent" placeholder="Enter percentage"> <span>%</span>
                        </td>
                    </tr>

                    <tr>
                        <th>
                            Commision in Rupees<span class="requiredfield">*</span>
                        </th>
                        <td>
                            <input type="number" value="" class="purcomm1" id="comm_amount" name="comm_amount" placeholder=" commision in rupees" onchange="expenses()"  style="padding-left: 9px;" readonly>
                        <span>INR</span>
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
                    <tr>
                      <th>
                        Number of crates:
                      </th>
                      <td>
                        <input type="text" name="crate_count" value="0" id="crate_count" placeholder="Number of crates" class="purcomm1">
                      </td>
                    </tr>
                    <tr>
                      <th>
                        Rate / Crate:
                      </th>
                      <td>
                        <input type="text" name="crate_rate" id="crate_rate" onkeydown="crateAmount()" value="0" placeholder="Rate / Crate" class="purcomm1">
                      </td>
                    </tr>
                    <tr>
                      <th>
                        Crate Amount:
                      </th>
                      <td>
                        <input type="text" name="crate_amount" id="crate_amount" value="0" placeholder="Crate Amount" class="purcomm1">
                      </td>
                    </tr>

<script type="text/javascript">

  function addcomm() {
      var commision_amount = $("#comm_amount").val();
      if(commision_amount < 0){
        commision_amount = commision_amount*-1;
        $("#comm_amount").val(commision_amount);
        $("#comm_type").val("RECEIVE");
        paycalc();
      }
  }

  function subcomm(){
    var commision_amount = $("#comm_amount").val();
    if(commision_amount > 0){
      commision_amount = commision_amount*-1;
      $("#comm_amount").val(commision_amount);
      $("#comm_type").val("PAID");
      paycalc();
    }
  }


  function crateAmount(){
    var crate_count = parseFloat($("#crate_count").val());
    var crate_rate = parseFloat($("#crate_rate").val());
    var crate_amount = crate_rate * crate_count;
    $("#crate_amount").val(crate_amount);
    paycalc();
  }


  function paycalc(){
    var prodAmount = parseFloat($("#totalPay11").val());
    var com_amount = parseFloat($("#comm_amount").val());

    var crate_amount = parseFloat($("#crate_amount").val());

    var total = parseFloat(prodAmount - com_amount - crate_amount);

    $("#totalPay").val(total);

  }




</script>


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
                                    <td><input type="text"  step="0.01" class="ptext ptotalpa" id="totalPay" placeholder="Payable Amount" onchange="duecalc()" onkeypress='validate(event)' name="totalPay" required ></td>
                                </tr>
                                <tr>
                                    <th>Total expense Amount <span class="requiredfield">*</span></th>
                                    <td>
                                    <input type="number" step="0.01" class="ptext ptotalpa" id="totalExpense" name="totalExpense" placeholder="Expense Amount" onblur="netamount()" required readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Net Amount <span class="requiredfield">*</span></th>
                                    <td>
                                    <input type="number"  step="0.01" class="ptext ptotalpa" id="netAmount" placeholder="Net Amount" name="netAmount" required readonly>
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

                            </tbody>
                     </table>
                 </div>
      </div>

            <div class="row">

            <div class="col-md-6" style="display:none;">
                <table class="table" >
                    <tbody>
                        <tr>
                            <input type="text" id="expense_21" name="expenses[0][id]" readonly>
                            <input type="text" id="expense_22" name="expenses[0][expense]" readonly>
                        </tr>
                        <tr>
                            <input type="text" id="expense_31" name="expenses[1][id]" readonly>
                            <input type="text" id="expense_32" name="expenses[1][expense]" readonly>
                        </tr>
                        <tr>
                            <input type="text" id="expense_41" name="expenses[2][id]" readonly>
                            <input type="text" id="expense_42" name="expenses[2][expense]" readonly>
                        </tr>
                        <tr>
                            <input type="text" id="expense_51" name="expenses[3][id]" readonly>
                            <input type="text" id="expense_52" name="expenses[3][expense]" readonly>
                        </tr>
                        <tr>
                            <input type="text" id="expense_61" name="expenses[4][id]" readonly>
                            <input type="text" id="expense_62" name="expenses[4][expense]" readonly>
                        </tr>
                        <tr>
                            <input type="text" id="expense_71" name="expenses[5][id]" readonly>
                            <input type="text" id="expense_72" name="expenses[5][expense]" readonly>
                        </tr>
                        <tr>
                            <input type="text" id="expense_81" name="expenses[6][id]" readonly>
                            <input type="text" id="expense_82" name="expenses[6][expense]" readonly>
                        </tr>
                        <tr>
                            <input type="text" id="expense_91" name="expenses[7][id]" readonly>
                            <input type="text" id="expense_92" name="expenses[7][expense]" readonly>
                        </tr>
                         <tr>
                            <input type="text" id="expense_101" name="expenses[8][id]" readonly>
                            <input type="text" id="expense_102" name="expenses[8][expense]" readonly>
                        </tr>
                        <tr>
                            <input type="text" id="expense_111" name="expenses[9][id]" readonly>
                            <input type="text" id="expense_112" name="expenses[9][expense]" readonly>
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
                            <input type="text"  step="0.01" class="ptext ptotalpa" id="totalPay1" onchange="duecalc()" onkeypress='validate(event)' name="totalPay" placeholder="payable amount" required >
                        </td>
                    </tr>
                    <tr>
                        <th>Total Amount Paid <span class="requiredfield">*</span></th>
                        <td> <input type="number" step="0.01" class="ptext ptotalam"id="totalPaid" onchange="duecalc()" name="totalPaid" placeholder="amount paid" value="0" required></td>

                    </tr>
                    <tr>
                        <th>Due Amount<span class="requiredfield">*</span></th>
                        <td><input type="number"  step="0.01" class="ptext ptotaldue" id="duepay" value="0" placeholder="due amount" name="duepay" required readonly></td>

                    </tr>


                    <tr>
                        <th>Paid To</th>
                        <td><input type="text" name="paidTo" value=" " class="ptext ptotaldue" placeholder="Receiver name" ></td>

                    </tr>

                     <tr>
                    <th>Enter Cheque Number</th>
                    <td><input type="text" class="ptext" name="chequeNumber" id="chequenumber" placeholder="Enter cheque number" value="" style="margin-left: 90px;width: 81%;padding-left:13px;"></td>
                </tr>
                </table>

            </div>
    </div>
            </div>
      </div>

   <div class="row">
            <script>
                $(function(){
                    $("#cash,#credit").change(function(){
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


                  </div>

            <div class="row pursubmit">

<!--           <button class="buttonreset"><a href="purchase_entry_list.php" style="color: white; text-decoration: none;">Cancel  </a></button>-->


           <button class="buttonsubmit" onclick="confirmModel()" data-toggle="modal" data-target="#confirmModal" type="button"><a >Submit</a></button>
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
              <span aria-hidden="true">?</span>
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
<script src="js/supplierdetails.js"></script>
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
              var finalqnty=$('#final').val();
            //   var final = (parseFloat(loaded)-parseFloat(shrink));
                //$('#final').val(final);
              var total = (parseFloat(unitprice)*finalqnty);
              $('#total').val(total);
          }
          function petchCalculation(){
              var quantity= $('#shrink1').val();
              var petchpercentage=$('#petch').val();
              var petchweight=(parseFloat(quantity)*(petchpercentage/100));
              var finalqty=(parseFloat(quantity)-petchweight);
              $('#shrink').val(petchweight);
          }
          function pfinalQuantity(){
              var shrinkqty=$('#shrink').val();
              var qty=$('#shrink1').val();
              var fqty=(parseFloat(qty)-parseFloat(shrinkqty));
              $('#final').val(fqty);
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
//              var labour = $("#labour").val();
//              var trans = $("#trans").val();
//              var post = $("#post").val();
//              var miscellaneous = $("#miscellaneous").val();
              var com = $("#comm_amount").val();
              var advance = $("#advancepaid").val();
              var balance = $("#balpaid").val();
//    var totalexpense = parseFloat(labour)+parseFloat(trans)+parseFloat(post)+parseFloat(miscellaneous);
var pay = parseFloat(amountCal)-parseFloat(com)-parseFloat(advance)-parseFloat(balance);
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
      <script src="js/jquery-3.2.1.min.js"></script>
      <script src="js/select2.min.js"></script>
    <script src="js/table.js"></script>

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
<!--
        <script>
        $(document).ready(function(){

            // Initialize select2
            $("#address").select2();

            // Read selected option
            $('#but_read').click(function(){
                var username = $('#address option:selected').text();
                var userid = $('#address').val();
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
                    // Qtys1 = document.getElementById("shrink2").value,
                    unitPrice = $('#city').val(),
                    totalAmount = document.getElementById("total").value;
        }
        function clearing(){
           $("#shrink1").val(0);
            $("#shrink").val(0);
            $("#final").val(0);
            // $("#shrink2").val(0);
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
                    cell5 = newRow.insertCell(4),
                    cell6 = newRow.insertCell(5),
                    cell7 = newRow.insertCell(6),
                    // cell8 = newRow.insertCell(7),
                    // cell9 = newRow.insertCell(8),
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
                        cell5.innerHTML = unitPrice;
                        cell6.innerHTML = totalAmount ;
                        // cell8.innerHTML = Qtys1 ;
                        cell7.innerHTML = product_id;
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
            table.rows[rIndex].cells[6].innerHTML = product_id;

             }

             clearing();

           }

            function removeSelectedRow()
            {
                table.deleteRow(rIndex);
                 $('#address option:selected').val(this.cells[0]? this.cells[0].innerHTML:'');
                        $('#shrink1').val(this.cells[1].innerHTML);
                        $('#shrink').val(this.cells[2].innerHTML);
                        $('#final').val(this.cells[3].innerHTML);
                        $('#shrink2').val(this.cells[4].innerHTML);
                        $('#city').val(this.cells[5].innerHTML);
                        $('#total').val(this.cells[6].innerHTML);
            }


        </script>

     <script>


                  function tableone(){
                      var sum = 0;
                      for(row = 2; row<52;row++){
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
                                // $('#row_'+ row +5 ).val(xvalue[4]);
                                $('#row_'+ row +6 ).val(xvalue[5]);
                                $('#row_'+ row +7 ).val(xvalue[6]);









                          if(!isNaN(value) && value.length !=0){
                              sum += parseFloat(value);
                          }
                          $("#totalPay").val(sum);
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
          var netAmount = parseFloat(amount)-parseFloat(totalExpeses);
         $("#netAmount").val(netAmount);
            console.log(netAmount);
         $("#totalPay1").val(netAmount)
      }

    </script>


    <script>
        function colum(){


        for(pro = 2;pro<13; pro++){
                $("#dataTable > thead > tr:nth-child("+pro+") > td:nth-child(8)").css('display','none');
             // $("#dataTable > thead > tr:nth-child("+pro+") > td:nth-child(9)").css('display','none');
        }

        }

    </script>

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
                        <th>Srink</th>
                        <th>Final Quantity</th>
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
                        <!-- <th>Number of Crates Given</th>
                        <th>Crate Bill Amount</th> -->
                        <th>Net Amount</th>
                        <th>Amount Paid</th>
                        <th>Due Amount</th>
                        <!-- <th>Final  Balance Amount</th> -->
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><b id="md_expenses"></b></td>
                        <td><b id="md_comm_amount"></b></td>
                        <!-- <td><b id="md_num_crates"></b></td>
                        <td><b id="md_crate_amt"></b></td> -->
                        <td><b id="md_net_amount"></b></td>
                        <td><b id="md_received_amount"></b></td>
                        <td><b id="md_balance"></b></td>
                        <!-- <td><b id="md_final_bal"></b></td> -->
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



    <script type="text/javascript">

    function sformValidate(){
      var cust_id = $("#employee").val();
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
            document.getElementById("purchase_form").submit();
        }
      }


      function confirmModel(){
        var md_name = $("#employee option:selected").html();
        var md_date = $("#ondate").val();
        var md_transtype = $("input[name=transType]:checked").val();
        if(md_transtype == "1"){
          md_transtype_name = "CASH";
        }else{
          md_transtype_name = "CREDIT";
        }

        var md_comm_amount = $("#comm_amount").val(); //commission amount
        var md_expenses = $("#totalExpense").val(); // total expense
        var md_net_amount = $("#netAmount").val(); // Net amount


    var transType = $("input[name='transType']:checked").val();

     if(transType == "1"){
       // cash purchase
       var md_paid_amount = $("#totalPaid").val(); //amount paid
       var md_due = $("#duepay").val(); // due amt
     }else{
       // credit purchase
       var md_paid_amount = "0"; //amount paid will be zero
       var md_due = parseFloat(md_net_amount); // net amount will be due


     }



        $("#md-name").text(md_name);
        $("#md-date").text(md_date);
        $("#md-transtype").text(md_transtype_name);
    $("#confir_table").html("");
    copytab();
    $("#md_expenses").text(md_expenses);
    $("#md_net_amount").text(md_net_amount);
    $("#md_received_amount").text(md_paid_amount);
    $("#md_balance").text(md_due);
    // $("#md_final_bal").text(md_final_bal);
    $("#md_comm_amount").text(md_comm_amount);
    // $("#md_num_crates").text(md_num_crates);
    // $("#md_crate_amt").text(md_crate_amt);


      }

        function copytab(){
          var prod_name = [];
          var quantity = [];
          var shrink = [];
          var final_quantity = [];
          var unitprice = [];
          var amount = [];
          var len = $("#dataTable > thead > tr").length;

          var count = 2;
          for(var i =0;i<len-1;i++){

            prod = $("#dataTable > thead > tr:nth-child("+count+") > td:nth-child(1)").text();
            prod_name.push(prod);
            quant = $("#dataTable > thead > tr:nth-child("+count+") > td:nth-child(2)").text();
            quantity.push(quant);
            shrnk = $("#dataTable > thead > tr:nth-child("+count+") > td:nth-child(3)").text();
            shrink.push(shrnk);
            final_quant = $("#dataTable > thead > tr:nth-child("+count+") > td:nth-child(4)").text();
            final_quantity.push(final_quant);


            unit = $("#dataTable > thead > tr:nth-child("+count+") > td:nth-child(5)").text();
            unitprice.push(unit);
            amt = $("#dataTable > thead > tr:nth-child("+count+") > td:nth-child(6)").text();
            amount.push(amt);
            count++;
          }

          for (var i = 0; i < len-1; i++) {

            $("#confir_table").append(`

              <tr>
                <td>`+prod_name[i]+`</td>
                <td>`+quantity[i]+`</td>
                <td>`+shrink[i]+`</td>
                <td>`+final_quantity[i]+`</td>
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
            		$("#address").val(prod_id);
            	}

            </script>


</body>

</html>
<?php } ?>
