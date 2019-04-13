<?php
session_start();
require("logout.php");
require('dbconnect.php');
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
  <!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
  <link href="css/purchase_return.css" rel="stylesheet">
  <!-- <script src="ajaxscript/billnum.js"></script> -->

<!--
    <script>
        function sretcolum(){


        for(pro = 2;pro<13; pro++){
                $("#Returntable > thead > tr:nth-child("+pro+") > td:nth-child(6)").css('display','none');
             $("#Returntable > thead > tr:nth-child("+pro+") > td:nth-child(7)").css('display','none');
        }

        }

    </script>
-->
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <?php
    require('header.php');
    ?>
  <div class="content-wrapper">
    <div class="container-fluid">

         <div class="row">

                <pre style="float:right">                                                                                              (Note:Fields with <i class="fa fa-asterisk" style="font-size:13px;color:red;"></i> make are compulsory)</pre>
            </div>


     <!-- customer details-->
      <div class="row">
          <div class="col-md-6 cust_line">
        <!-- <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post"> -->
            <h5 class="headpurchase"><u>Add Sales Return</u></h5>
            <div class="input-group">
                <label>Sale Bill No</label>
              <input class="form-control " type="text" name="billnumber" id="billNoInput" placeholder="Bill No..." >
              <span class="input-group-btn" >
                <button class="btn btn-primary searchbutton" type="button" id="billSearch">
                  <i class="fa fa-search" ></i>
                </button>
              </span>
            <!-- </form> -->

            </div>


              <br>
              <label>Customer Name</label>
         <input type="text" class="purtext custname" name="chequeNumber" id="supp_name" placeholder="Enter Supplier Name.." value="" >

<br>
               <label class="retpro">Product Name<span class="requiredfield">*</span></label>

<!--                        <input type="text"  class="salestext1" id="product_type" name="product_type" placeholder="Enter Product Type.." value="" required>-->

                        <select id="retpro_type" class="purtext " name="sretproduct_name" disabled>
                            <?php
                            //select product name form database
        $selectProduct = "SELECT * FROM `hk_products` where products_active=1";
        $selectproductExe = mysqli_query($conn,$selectProduct);
        while($selectRow = mysqli_fetch_array($selectproductExe)){


                            ?>

                            <option value="<?php echo $selectRow["id"]; ?>"><?php echo $selectRow["name"]." ".$selectRow["type"]; ?></option>

                        <?php
                        }
                        ?>

                        </select>
                        <br>
                 <label >Return Quantity<span class="requiredfield">*</span></label>

                             <input type="number" class=" purqty pwidthi" id="ret_quantity" name="return_quantity" placeholder="Return  quantity.." value="" required >


                    <div class="purreturnrow"></div>

                   <label >Unit Price<span class="requiredfield">*</span></label>

                             <input type="number" class="purtext purunit" id="retunit_price" name="retunit_price" onblur="receivableAmount()" placeholder="Enter unit price.." readonly>

                            <div class="purreturnrow"></div>
                    <label >Amount<span class="requiredfield">*</span></label>

                            <input type="number" class="purtext purtotal" id="ret_amt" name="retproduct_amount" placeholder="Total Product Amount.."  >

  <div class="row pursave">
                <button  type="button" class="buttonsave1 btn btn-warning saveedit" onclick="edit();" >Edit</button>
     </div>





            <table class="table table-bordered table-hover table-sm" id="Returntable">
              <thead>
                <tr class="custtd">

                   <th>Product Name </th>
                    <th>Quantity </th>
                    <th>Quantity Type </th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                     <th>Product ID</th>
                     <th hidden>Qty Type ID</th>
                    <th>Return Quantity</th>
                    <th>Recivebale Amount</th>

                 </tr>
              </thead>
             <tbody>
                 <tr>
                 <td id="row_11"></td>
                 <td id="row_12"></td>
                 <td id="row_13"></td>
                 <td id="row_14"></td>
                 <td id="row_15"></td>
                 <td id="row_16" ></td>
                 <td id="row_17" hidden></td>
                 <td id="row_18"></td>
                 <td id="row_19"></td>
                 </tr>
                 <tr>
                 <td id="row_21"></td>
                 <td id="row_22"></td>
                 <td id="row_23"></td>
                 <td id="row_24"></td>
                 <td id="row_25"></td>
                     <td id="row_26" style="dispaly:none;"></td>
                     <td id="row_27" hidden></td>
                     <td id="row_28"></td>
                     <td id="row_29"></td>
                 </tr>
                 <tr>
                 <td id="row_31"></td>
                 <td id="row_32"></td>
                 <td id="row_33"></td>
                 <td id="row_34"></td>
                 <td id="row_35"></td>
                     <td id="row_36" style="dispaly:none"></td>
                     <td id="row_37" hidden></td>
                     <td id="row_38"></td>
                     <td id="row_39"></td>
                 </tr>
                 <tr>
                 <td id="row_41"></td>
                 <td id="row_42"></td>
                 <td id="row_43"></td>
                 <td id="row_44"></td>
                 <td id="row_45"></td>
                     <td id="row_46" style="dispaly:none"></td>
                     <td id="row_47" hidden></td>
                     <td id="row_48"></td>
                     <td id="row_49"></td>
                 </tr>
                 <tr>
                 <td id="row_51"></td>
                 <td id="row_52"></td>
                 <td id="row_53"></td>
                 <td id="row_54"></td>
                 <td id="row_55"></td>
                     <td id="row_56" style="dispaly:none"></td>
                     <td id="row_57" hidden></td>
                     <td id="row_58"></td>
                     <td id="row_59"></td>
                 </tr>
                 <tr>
                 <td id="row_61"></td>
                 <td id="row_62"></td>
                 <td id="row_63"></td>
                 <td id="row_64"></td>
                 <td id="row_65"></td>
                     <td id="row_66" ></td>
                     <td id="row_67" hidden></td>
                     <td id="row_68"></td>
                     <td id="row_69"></td>
                 </tr>
                 <tr>
                 <td id="row_71"></td>
                 <td id="row_72"></td>
                 <td id="row_73"></td>
                 <td id="row_74"></td>
                 <td id="row_75"></td>
                     <td id="row_76" ></td>
                     <td id="row_77" hidden></td>
                     <td id="row_78"></td>
                     <td id="row_79"></td>
                 </tr>
                 <tr>
                 <td id="row_81"></td>
                 <td id="row_82"></td>
                 <td id="row_83"></td>
                 <td id="row_84"></td>
                 <td id="row_85"></td>
                     <td id="row_86"></td>
                     <td id="row_87" hidden></td>
                     <td id="row_88"></td>
                     <td id="row_89"></td>
                 </tr>




              </tbody>
            </table>
              <p>Total reciveable amount  <b id="receiveAmount"></b></p>

              <button class="buttonsave3 btn btn-default" style="float:right" onclick="productdetail();">Save</button>






          </div>





          <div class="col-md-6 cust_line">

          <form action="sales_return_module/sales_return_creation_handler.php" id="return_form_s" method="post">
            <input type="text" id="sales_id" class="purtext purbill" name="sales_id" style="display:none">
              <input type="text" id="person_name" name="person_name" style="display:none">
              <input type="text" id="person_id" name="person_id" style="display:none">
<!--            <input type="text" id="product_id" class="purtext purbill" name="product_id" style="display:none">-->
<!--            <input type="text" id="sname" class="purtext purbill" name="sname" style="display:none">-->
<!--            <input type="text" id="supplier_id" class="purtext purbill" name="supplier_id" style="display:none">-->
       <label for="bill" class="purbalance">Sales Return Bill No<span class="requiredfield">*</span> </label>


       <?php



       $month = date("m");

       if($month>4){
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







         $calcBillQ = "select MAX(id) as billnum from `hk_sales_return` WHERE `date` BETWEEN '$fromDate' and '$toDate'";
         $calcBillExe = mysqli_query($conn,$calcBillQ);
         while($calcBillRow = mysqli_fetch_array($calcBillExe)){
             $billval = $calcBillRow["billnum"];
         }
         $billval +=1;
         $billNumber = $billval;
         ?>

   <input type="text" id="bills" class="purtext purbilll" name="salesbill"  placeholder="Bill No.." value="<?php echo $billNumber; ?>" maxlength="30" required readonly>



<br>

<div class="form-group">
  <label for="">Date:</label>
  <input type="date" class="purtext" id="ondate" name="ondate" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
</div>

                <h6 class="prtt"><u>Purchase Return Transaction Type</u></h6>


                     <label class="radio-inline">
      <input type="radio" name="transType" id="scash" value="1" >CASH
    </label>
    <label class="radio-inline">
      <input type="radio" name="transType" id="scredit" value="2">CREDIT
    </label>




                     <h6 class="prtt"><u>Payment Details</u></h6>
              <label>Total Receivable Amount <span class="requiredfield">*</span></label>
                                    <input type="text" class="purtext" id="totalPay" onblur="duecalc()" name="totalPay" required>

<div class="row">
                  <div id="scash1" class="headtrans" hidden >
    <div class="row" >

            <h6 class="prtt"><u>Payment Transaction Methods</u></h6>

            <label class="radio-inline radioone">
      <input type="radio" name="transMethod" id="scashm" value="1"  >CASH
    </label>
    <label class="radio-inline radiotwo">
      <input type="radio" name="transMethod" id="schequem" value="2">CHEQUE
    </label>

<!--
    <label class="radio-inline" style="margin-top:8px;">
      <input type="radio" name="transMethod" id="schequem" value="3">RTGS/NEFT
    </label>
-->
    </div>


               <label>Total Receivable Amount <span class="requiredfield">*</span></label>

                            <input type="text" class="purtext totpayy" id="stotalreceivable" onblur="duecalc()" name="totalPay" placeholder="Receivable Amount.." required>

                   <label>Total Amount Received <span class="requiredfield">*</span></label>
                        <input type="text" class="purtext amrece" id="stotalreceived" onblur="balancecalc()" name="totalPaid" placeholder="Amount Received.." value="0" required>

                      <label>Balance Amount<span class="requiredfield">*</span></label>
                     <input type="text" class="purtext balam" id="sbalanceamt" value="0" placeholder="Balance Amount.." name="duepay" required>

                   <label>Enter Cheque Number</label>
                   <input type="text" class="purtext chnum" name="chequeNumber" id="schequenumber" placeholder="Enter cheque number." value="">
                       <label for="transaction" class="purbalance">Transaction Id</label>
        <input type="text" id="transaction_id" name="transaction_id" class="purtext transid" placeholder="Enter transaction id.." >


              </div>


</div>



<div class="row">

       <button class="buttonsubmit" onclick="confirmModel()" type="button" data-toggle="modal" data-target="#confirmModal"><a >Submit</a></button>
     <a  href="sales_return_list.php" style="text-decoration:none;"  class="buttonreset"><span>Cancel</span></a>
    </div>




              <div class="row">
                <div class="col-sm-12">
                <table class="table table-sm" style = "display:none">
                    <tr class="custtd">
                        <td>
                            <input type="text" id="in_11" class="form-control"  name="returnproduct[0][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_12" class="form-control" name="returnproduct[0][quantity]">
                        </td>
                        <td hidden>
                            <input type="text" id="in_13" class="form-control" name="returnproduct[0][quantity_type]" >
                        </td>
                        <td>
                            <input type="text" id="in_14" class="form-control" name="returnproduct[0][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_15" class="form-control" name="returnproduct[0][amount]">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" id="in_21"  name="returnproduct[1][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_22" name="returnproduct[1][quantity]">
                        </td>
                        <td hidden>
                            <input type="text" id="in_23" name="returnproduct[1][quantity_type]" >
                        </td>
                        <td>
                            <input type="text" id="in_24" name="returnproduct[1][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_25" name="returnproduct[1][amount]">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" id="in_31"  name="returnproduct[2][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_32" name="returnproduct[2][quantity]">
                        </td>
                        <td hidden>
                            <input type="text" id="in_33" name="returnproduct[2][quantity_type]" >
                        </td>
                        <td>
                            <input type="text" id="in_34" name="returnproduct[2][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_35" name="returnproduct[2][amount]">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" id="in_41"  name="returnproduct[3][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_42" name="returnproduct[3][quantity]">
                        </td>
                        <td hidden>
                            <input type="text" id="in_43" name="returnproduct[3][quantity_type]" >
                        </td>
                        <td>
                            <input type="text" id="in_44" name="returnproduct[3][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_45" name="returnproduct[3][amount]">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" id="in_51"  name="returnproduct[4][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_52" name="returnproduct[4][quantity]">
                        </td>
                        <td hidden>
                            <input type="text" id="in_53" name="returnproduct[4][quantity_type]" >
                        </td>
                        <td>
                            <input type="text" id="in_54" name="returnproduct[4][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_55" name="returnproduct[4][amount]">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" id="in_61"  name="returnproduct[5][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_62" name="returnproduct[5][quantity]">
                        </td>
                        <td hidden>
                            <input type="text" id="in_63" name="returnproduct[5][quantity_type]" >
                        </td>
                        <td>
                            <input type="text" id="in_64" name="returnproduct[5][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_65" name="returnproduct[5][amount]">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" id="in_71"  name="returnproduct[6][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_72" name="returnproduct[6][quantity]">
                        </td>
                        <td hidden>
                            <input type="text" id="in_73" name="returnproduct[6][quantity_type]" >
                        </td>
                        <td>
                            <input type="text" id="in_74" name="returnproduct[6][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_75" name="returnproduct[6][amount]">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" id="in_81"  name="returnproduct[7][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_82" name="returnproduct[7][quantity]">
                        </td>
                        <td hidden>
                            <input type="text" id="in_83" name="returnproduct[7][quantity_type]" >
                        </td>
                        <td>
                            <input type="text" id="in_84" name="returnproduct[7][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_85" name="returnproduct[7][amount]">
                        </td>
                    </tr>


              </table>
                  </div>
              </div>







  </form>
          </div>
        </div>

        <!-- end of customer deatils-->
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
            <div class="col-md-4">
              <label for="md-transtype">
                Sales Bill No: <b id="md-p-billno"></b>
              </label>
            </div>
            <div class="col-md-4">
              <label for="md-transtype">
                S-R-Bill No: <b id="md-p-r-billno"></b>
              </label>
            </div>
            <div class="col-md-4">
              <label for="md-transtype">
                Total Amount : <b id="md-r-am-billno"></b>
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
                      <th>Quantity Type</th>
                      <th>Unit Price</th>
                      <th>Amount</th>
                      <th>Product ID</th>
                      <th>Return Quantity</th>
                      <th>Receivable Amount</th>
                    </tr>
                  </thead>
                  <tbody id="confir_table">

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


      <script>
            var rIndex,
                table = document.getElementById("Returntable");

            // check the empty input
            function checkEmptyInput()
            {
                var isEmpty = false,
//                     product_id =$("#product_type option:selected").val(),
                    // retproduct_name = document.getElementById("retpro_type").value,
                   return_quantity= document.getElementById("ret_quantity").value,
                    // qty_type  = $("#ret_qty option:selected").html(),
                     // retunit_price  = document.getElementById("retunit_price").value,
                     retproduct_amount  = document.getElementById("ret_amt").value,
                    product_id =$("#sretpro_type option:selected").val();
            }

             selectedRowToInput();

            // display selected row data into input text
            function selectedRowToInput()
            {

                for(var i = 1; i < table.rows.length; i++)
                {
                    table.rows[i].onclick = function()
                    {
                      // get the seected row index
                      rIndex = this.rowIndex;
                         $('#retpro_type').val(this.cells[5].innerHTML);
                         $('#ret_quantity').val(this.cells[7].innerHTML);
                         $('#ret_qty').val(this.cells[6].innerHTML);
                         $('#retunit_price').val(this.cells[3].innerHTML);
                         $('#ret_amt').val(this.cells[8].innerHTML);
                         $('#sretpro_type').val(this.cells[5].innerHTML);
                    };
                }
            }
            selectedRowToInput();


            function editHtmlTbleSelectedRow()
            {
               var
//                retproduct_name = $("#retpro_type option:selected").html(),
                   return_quantity= $("#ret_quantity").val(),
//                    qty_type  = $("#ret_qty option:selected").html(),
//                     retunit_price  = document.getElementById("retunit_price").value,
                     retproduct_amount  = document.getElementById("ret_amt").value,
                    product_id =$("#retpro_type option:selected").val();
//                    quantity_id =$("#ret_qty option:selected").val();

               if(!checkEmptyInput()){
//                   table.rows[rIndex].cells[0].innerHTML = retproduct_name;
                table.rows[rIndex].cells[7].innerHTML = return_quantity;
//                table.rows[rIndex].cells[2].innerHTML = qty_type ;
//                table.rows[rIndex].cells[3].innerHTML =  retunit_price ;
                    table.rows[rIndex].cells[8].innerHTML =  retproduct_amount;
//                    table.rows[rIndex].cells[5].innerHTML =  product_id;
//                    table.rows[rIndex].cells[6].innerHTML =  quantity_id;
              }
            }



           function edit(){



               var returnQuantity = $("#ret_quantity").val();
               var reciveableAmount = $("#ret_amt").val();
               table.rows[rIndex].cells[7].innerHTML = returnQuantity;
               table.rows[rIndex].cells[8].innerHTML = reciveableAmount;

               sum();

           }

           function sum(){
               var sum = 0;
               for(i = 1;i<table.rows.length;i++){
                   var returnAm = $('#row_'+i+9).text();
                   if(returnAm == ""){
                            returnAm =0;
                       console.log(returnAm);
                   }
               sum = parseFloat(sum) +parseFloat(returnAm);
               }
               console.log(sum);
               $("#receiveAmount").text(sum);
               $("#totalPay").val(sum);
               $("#stotalreceivable").val(sum);
           }


           //balance clcultaor

           function balancecalc(){
               var recievable = $("#stotalreceivable").val();
               var received = $("#stotalreceived").val();

               var balance = parseFloat(recievable)- parseFloat(received);
               $("#sbalanceamt").val(balance);
           }



           function receivableAmount(){
               var returnQuantity1 = $("#ret_quantity").val();
               var unitPrice = $('#retunit_price').val();
               var returnAmount = unitPrice*returnQuantity1;
               $("#ret_amt").val(returnAmount);
           }

        </script>
<script>
$( document ).ready(function() {
  $('#billSearch').on('click', function(){
   // alert(  $('#billNoInput').val());

$.ajax({
    url: "get-sales-bill-num.php",
    dataType: 'Json',
    data: {'id':$('#billNoInput').val()},
    success: function(data) {
     //alert('done');
      // console.log(data);
        row =1;
        // $("#Returntable > tbody").html("");


        for(i =0;i<=data.length;i++){

          console.log(data[i]);
          // $("#Returntable > tbody").append(`
          //
          //   <tr>
          //      <td id="row_`+row+`1">`+data[i].name +` `+data[i].type+`</td>
          //      <td id="row_`+row+`2">`+data[i].quantity+`</td>
          //      <td id="row_`+row+`3">`+data[i].quantity_type+`</td>
          //      <td id="row_`+row+`4">`+data[i].rate+`</td>
          //      <td id="row_`+row+`5">`+data[i].amount+`</td>
          //      <td id="row_`+row+`6">`+data[i].product_id+`</td>
          //
          //
          //      </tr>
          //
          //
          //   `);

            $('#supp_name').val(data[i].first_name+" "+data[i].last_name);
            $('#row_'+row+1).html(data[i].name+" "+data[i].type);
            $('#row_'+row+2).html(data[i].quantity);
            $('#row_'+row+3).html(data[i].quantity_type);
            $('#row_'+row+4).html(data[i].rate);
            $('#row_'+row+5).html(data[i].amount);
            $('#row_'+row+6).html(data[i].product_id);
            $('#row_'+row+7).html(data[i].quantity_type_id);
            $('#sales_id').val(data[i].sales_id);

            $('#person_name').val(data[i].first_name+" "+data[i].last_name);

            $('#person_id').val(data[i].person_id);
            $("#receiveAmount").text()
            row++;
        }




//    $('#date').html(data.bill_date);
//
//
//    $('#quantity').html(data.final_quantity);
//    $('#rate').html(data.unit_price);
//    $('#total').html(data.amount_paid);
//   $('#purchaseid').val(data.id);
//   $('#product_id').val(data.product_id);
//   $('#sname').val(data.first_name);
//   $('#supplier_id').val(data.supplier_id);
//   $("#unitu").html(data.quantity_type);

    }
})



  })
});
</script>
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
<script>
function getamount(){
var rate=document.getElementById ( "rate" ).innerText;

var returnedqty = $("#returnqty").val();
var finalamount = parseFloat(rate)*parseFloat(returnedqty);
$("#amount_recievable").val(finalamount);
}
</script>
<script>
function getbalance(){
var amount_recievable=$("#amount_recievable").val();
var amount_recieved = $("#amount_recieved").val();
var balance = parseFloat(amount_recievable)-parseFloat(amount_recieved);
console.log(balance);
$("#balance").val(balance);
}
</script>


      <script>
        function productdetail(){
            for(var k =1;k<=table.rows.length;k++){
                invalue =[];
                invalue[0]=$("#row_"+k+6).text();//product Id
                invalue[1]=$("#row_"+k+8).text();//return quantity
                invalue[2] = $("#row_"+k+7).text();//quantity Type id
                invalue[3] = $("#row_"+k+4).text();//unit price
                invalue[4] = $("#row_"+k+9).text();// amount

                $("#in_"+k+1).val(invalue[0]);
                $("#in_"+k+2).val(invalue[1]);
                $("#in_"+k+3).val(invalue[2]);
                $("#in_"+k+4).val(invalue[3]);
                $("#in_"+k+5).val(invalue[4]);

                console.log(invalue[0]);
            }

        }

      </script>

      <script type="text/javascript">

      function sformValidate(){
        var cust_id = $("#supp_name").html();
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
              document.getElementById("return_form_s").submit();
          }
        }


        function confirmModel(){
          var md_name = $("#supp_name").val();
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
          var md_p_billno = $("#billNoInput").val(); //bill number
          var md_p_r_billno = $("#bills").val();//return bill number
          var tot_amt = $("#totalPay").val();//total amount


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
      $("#md-p-billno").text(md_p_billno);
      $("#md-p-r-billno").text(md_p_r_billno);
      $("#md-r-am-billno").text(tot_amt);
        }

          function copytab(){
            var prod_name = [];
            var quantity = [];
            var quantity_type1 = [];
            var unitprice = [];
            var amount = [];
            var prod_id = [];
            var return_qty = [];
            var recive_amt = [];
            var len = $("#Returntable > tbody > tr").length;

            var count = 1;
            for(var i =0;i<len-1;i++){

              prod = $("#row_"+count+"1").text();
              prod_name.push(prod);
              quant = $("#row_"+count+"2").text();
              quantity.push(quant);
              qty_type = $("#row_"+count+"3").text();
              quantity_type1.push(qty_type);
              u_price = $("#row_"+count+"4").text();
              unitprice.push(u_price);


              amount1 = $("#row_"+count+"5").text();
              amount.push(amount1);
              prod_id1 = $("#row_"+count+"6").text();
              prod_id.push(prod_id1);
              return_qtty = $("#row_"+count+"8").text();
              return_qty.push(return_qtty);
              recive_amt1 = $("#row_"+count+"9").text();
              recive_amt.push(recive_amt1);
              count++;
            }

            for(var i = 0; i < len-1; i++) {

              $("#confir_table").append(`<tr>
                  <td>`+prod_name[i]+`</td>
                  <td>`+quantity[i]+`</td>
                  <td>`+quantity_type1[i]+`</td>
                  <td>`+unitprice[i]+`</td>
                  <td>`+amount[i]+`</td>
                  <td>`+prod_id[i]+`</td>
                  <td>`+return_qty[i]+`</td>
                  <td>`+recive_amt[i]+`</td>
                </tr>`);
            }

            console.log(prod_name);
            console.log(quantity);
            console.log(unitprice);
            console.log(amount);

          }
      </script>





  </div>
</body>
</html>
<?php } ?>
