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


  <!--    search dropdown-->
    <link href="css/select1.min.css" rel="stylesheet">




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
       <form action="sales_return_module/sales_return_creation_handler_direct.php" id="return_form_s" method="post">
      <div class="row">

          <div class="col-md-6 cust_line">
        <!-- <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post"> -->
            <h5 class="headpurchase"><u>Add Sales Return</u></h5>
            <div class="form-group">
                <label>Select Customer</label>
              	<select class="form-control custstate"  name="person_id" id='selUser' required="required">
              		<option>  -- select customers --</option>
              		<?php
              		$query = "SELECT id,first_name,last_name FROM hk_persons WHERE person_active = 1 &&
                  person_type_id = 2 ORDER BY `first_name`";
              		$exe = mysqli_query($conn,$query);


              		while ($row = mysqli_fetch_array($exe)) {
              			?>
              			<option value="<?php echo $row["id"]; ?>"><?php echo $row["first_name"]." ".$row["last_name"]; ?></option>
              			<?php
              		}
              		?>
              	</select>
            </div>

            <div class="form-group">
            	<label>Enter Product Code</label>
            	<input type="number" name="produc_code" id="product_code">
            	<button type="button" class="btn btn-default" onclick="enterprod()" >OK</button>
            </div>

            <script type="text/javascript">
            	function enterprod(){
            		// product_id of input box
            		var prod_id = $("#product_code").val();
            		// id of select tag
            		$("#retpro_type").val(prod_id);
            	}

            </script>


               <label class="retpro">Product Name<span class="requiredfield">*</span></label>

<!--                        <input type="text"  class="salestext1" id="product_type" name="product_type" placeholder="Enter Product Type.." value="" required>-->

                        <select id="retpro_type" class="purtext " name="sretproduct_name" disabled="true">
                            <?php
                            //select product name form database
        $selectProduct = "SELECT * FROM `hk_products` where products_active=1 ORDER BY `name`";
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

                             <input type="number" class="purtext purunit" id="retunit_price" name="retunit_price" onblur="receivableAmount()" placeholder="Enter unit price..">

                            <div class="purreturnrow"></div>
                    <label >Amount<span class="requiredfield">*</span></label>

                            <input type="number" class="purtext purtotal" id="ret_amt" name="retproduct_amount" placeholder="Total Product Amount.."  >

  <div class="row pursave">
  				<button class="buttonsave btn btn-primary" type="button" onclick="addHtmlTableRow()">Add</button>
                <button  type="button" class="btn btn-warning saveedit" onclick="edit();" >Edit</button>
                <button class="buttonsave2 btn btn-danger" type="button" onclick="removeSelectedRow()">Remove</button>
     </div>





            <table class="table table-bordered table-hover table-sm" id="Returntable">
              <thead>
                <tr class="custtd">

                   <th>Product Name </th>
                    <th>Return Quantity </th>
                    <th>Unit Price </th>
                    <th>Amount</th>
                     <th>Product ID</th>
                 </tr>
              </thead>
             <tbody>





              </tbody>
            </table>
              <p>Total receivable amount  <b id="receiveAmount"></b></p>

              <button type="button" class="buttonsave3 btn btn-default" style="float:right" onclick="productdetail();">Save</button>






          </div>





          <div class="col-md-6 cust_line">



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

                        <td>
                            <input type="text" id="in_84" name="returnproduct[7][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_85" name="returnproduct[7][amount]">
                        </td>
                    </tr>


                    <tr>
                        <td>
                            <input type="text" id="in_91"  name="returnproduct[8][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_92" name="returnproduct[8][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_94" name="returnproduct[8][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_95" name="returnproduct[8][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_101"  name="returnproduct[9][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_102" name="returnproduct[9][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_104" name="returnproduct[9][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_105" name="returnproduct[9][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_111"  name="returnproduct[10][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_112" name="returnproduct[10][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_114" name="returnproduct[10][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_115" name="returnproduct[10][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_121"  name="returnproduct[11][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_122" name="returnproduct[11][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_124" name="returnproduct[11][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_125" name="returnproduct[11][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_131"  name="returnproduct[12][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_132" name="returnproduct[12][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_134" name="returnproduct[12][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_135" name="returnproduct[12][amount]">
                        </td>
                    </tr>


                    <tr>
                        <td>
                            <input type="text" id="in_141"  name="returnproduct[13][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_142" name="returnproduct[13][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_144" name="returnproduct[13][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_145" name="returnproduct[13][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_151"  name="returnproduct[14][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_152" name="returnproduct[14][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_154" name="returnproduct[14][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_155" name="returnproduct[14][amount]">
                        </td>
                    </tr>


                     <tr>
                        <td>
                            <input type="text" id="in_161"  name="returnproduct[15][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_162" name="returnproduct[15][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_164" name="returnproduct[15][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_165" name="returnproduct[15][amount]">
                        </td>
                    </tr>


                    <tr>
                        <td>
                            <input type="text" id="in_171"  name="returnproduct[16][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_172" name="returnproduct[16][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_174" name="returnproduct[16][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_175" name="returnproduct[16][amount]">
                        </td>
                    </tr>

                     <tr>
                        <td>
                            <input type="text" id="in_181"  name="returnproduct[17][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_182" name="returnproduct[17][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_184" name="returnproduct[17][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_185" name="returnproduct[17][amount]">
                        </td>
                    </tr>


                    <tr>
                        <td>
                            <input type="text" id="in_191"  name="returnproduct[18][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_192" name="returnproduct[18][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_194" name="returnproduct[18][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_195" name="returnproduct[18][amount]">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" id="in_201"  name="returnproduct[19][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_202" name="returnproduct[19][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_204" name="returnproduct[19][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_205" name="returnproduct[19][amount]">
                        </td>
                    </tr>

                     <tr>
                        <td>
                            <input type="text" id="in_211"  name="returnproduct[20][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_212" name="returnproduct[20][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_214" name="returnproduct[20][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_215" name="returnproduct[20][amount]">
                        </td>
                    </tr>

                     <tr>
                        <td>
                            <input type="text" id="in_221"  name="returnproduct[21][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_222" name="returnproduct[21][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_224" name="returnproduct[21][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_225" name="returnproduct[21][amount]">
                        </td>
                    </tr>

                     <tr>
                        <td>
                            <input type="text" id="in_231"  name="returnproduct[22][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_232" name="returnproduct[22][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_234" name="returnproduct[22][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_235" name="returnproduct[22][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_241"  name="returnproduct[23][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_242" name="returnproduct[23][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_244" name="returnproduct[23][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_245" name="returnproduct[23][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_251"  name="returnproduct[24][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_252" name="returnproduct[24][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_254" name="returnproduct[24][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_255" name="returnproduct[24][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_261"  name="returnproduct[25][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_262" name="returnproduct[25][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_264" name="returnproduct[25][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_265" name="returnproduct[25][amount]">
                        </td>
                    </tr>

                     <tr>
                        <td>
                            <input type="text" id="in_271"  name="returnproduct[26][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_272" name="returnproduct[26][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_274" name="returnproduct[26][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_275" name="returnproduct[26][amount]">
                        </td>
                    </tr>


                    <tr>
                        <td>
                            <input type="text" id="in_281"  name="returnproduct[27][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_282" name="returnproduct[27][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_284" name="returnproduct[27][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_285" name="returnproduct[27][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_291"  name="returnproduct[28][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_292" name="returnproduct[28][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_294" name="returnproduct[28][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_295" name="returnproduct[28][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_301"  name="returnproduct[29][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_302" name="returnproduct[29][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_304" name="returnproduct[29][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_305" name="returnproduct[29][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_311"  name="returnproduct[30][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_312" name="returnproduct[30][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_314" name="returnproduct[30][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_315" name="returnproduct[30][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_321"  name="returnproduct[31][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_322" name="returnproduct[31][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_324" name="returnproduct[31][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_325" name="returnproduct[31][amount]">
                        </td>
                    </tr>


                    <tr>
                        <td>
                            <input type="text" id="in_331"  name="returnproduct[32][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_332" name="returnproduct[32][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_334" name="returnproduct[32][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_335" name="returnproduct[32][amount]">
                        </td>
                    </tr>

                     <tr>
                        <td>
                            <input type="text" id="in_341"  name="returnproduct[33][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_342" name="returnproduct[33][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_344" name="returnproduct[33][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_345" name="returnproduct[33][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_351"  name="returnproduct[34][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_352" name="returnproduct[34][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_354" name="returnproduct[34][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_355" name="returnproduct[34][amount]">
                        </td>
                    </tr>



                    <tr>
                        <td>
                            <input type="text" id="in_361"  name="returnproduct[35][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_362" name="returnproduct[35][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_364" name="returnproduct[35][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_365" name="returnproduct[35][amount]">
                        </td>
                    </tr>

                     <tr>
                        <td>
                            <input type="text" id="in_371"  name="returnproduct[36][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_372" name="returnproduct[36][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_374" name="returnproduct[36][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_375" name="returnproduct[36][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_381"  name="returnproduct[37][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_382" name="returnproduct[37][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_384" name="returnproduct[37][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_385" name="returnproduct[37][amount]">
                        </td>
                    </tr>

                     <tr>
                        <td>
                            <input type="text" id="in_391"  name="returnproduct[38][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_392" name="returnproduct[38][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_394" name="returnproduct[38][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_395" name="returnproduct[38][amount]">
                        </td>
                    </tr>


                     <tr>
                        <td>
                            <input type="text" id="in_401"  name="returnproduct[39][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_402" name="returnproduct[39][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_404" name="returnproduct[39][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_405" name="returnproduct[39][amount]">
                        </td>
                    </tr>

                     <tr>
                        <td>
                            <input type="text" id="in_411"  name="returnproduct[40][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_412" name="returnproduct[40][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_414" name="returnproduct[40][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_415" name="returnproduct[40][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_421"  name="returnproduct[41][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_422" name="returnproduct[41][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_424" name="returnproduct[41][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_425" name="returnproduct[41][amount]">
                        </td>
                    </tr>

                     <tr>
                        <td>
                            <input type="text" id="in_431"  name="returnproduct[42][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_432" name="returnproduct[42][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_434" name="returnproduct[42][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_435" name="returnproduct[42][amount]">
                        </td>
                    </tr>

                     <tr>
                        <td>
                            <input type="text" id="in_441"  name="returnproduct[43][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_442" name="returnproduct[43][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_444" name="returnproduct[43][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_445" name="returnproduct[43][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_451"  name="returnproduct[44][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_452" name="returnproduct[44][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_454" name="returnproduct[44][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_455" name="returnproduct[44][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_461"  name="returnproduct[45][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_462" name="returnproduct[45][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_464" name="returnproduct[45][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_465" name="returnproduct[45][amount]">
                        </td>
                    </tr>


                    <tr>
                        <td>
                            <input type="text" id="in_471"  name="returnproduct[46][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_472" name="returnproduct[46][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_474" name="returnproduct[46][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_475" name="returnproduct[46][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_481"  name="returnproduct[47][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_482" name="returnproduct[47][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_484" name="returnproduct[47][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_485" name="returnproduct[47][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_491"  name="returnproduct[48][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_492" name="returnproduct[48][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_494" name="returnproduct[48][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_495" name="returnproduct[48][amount]">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" id="in_501"  name="returnproduct[49][pro_id]">
                        </td>
                        <td>
                            <input type="text" id="in_502" name="returnproduct[49][quantity]">
                        </td>

                        <td>
                            <input type="text" id="in_504" name="returnproduct[49][rate]">
                        </td>
                        <td>
                            <input type="text"  id="in_505" name="returnproduct[49][amount]">
                        </td>
                    </tr>



              </table>
                  </div>
              </div>








          </div>
        </div>
</form>
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
                      <th>Return Quantity</th>
                      <th>Unit Price</th>
                      <th>Amount</th>
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
                     retproduct_amount  = document.getElementById("ret_amt").value;
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
                         $('#retpro_type').val(this.cells[4].innerHTML);
                         $('#ret_quantity').val(this.cells[1].innerHTML);
                         $('#retunit_price').val(this.cells[2].innerHTML);
                         $('#ret_amt').val(this.cells[3].innerHTML);
                    };
                }
            }
            selectedRowToInput();



            function addHtmlTableRow()
            {
                if(!checkEmptyInput()){
                var newRow = table.insertRow(table.length),
                    cell1 = newRow.insertCell(0),
                    cell2 = newRow.insertCell(1),
                    cell3 = newRow.insertCell(2),
                    cell4 = newRow.insertCell(3),
                    cell5 = newRow.insertCell(4),


                   product_name = $("#retpro_type option:selected").html(),
                   product_id =$("#retpro_type option:selected").val(),
                   ret_quantity= document.getElementById("ret_quantity").value,
                   unit_price  = document.getElementById("retunit_price").value,
                   product_amount  = document.getElementById("ret_amt").value;

                        cell1.innerHTML = product_name;
                        cell2.innerHTML =  ret_quantity;
                        cell3.innerHTML =  unit_price;
                        cell4.innerHTML =  product_amount;
                        cell5.innerHTML =  product_id;


                selectedRowToInput();
                	sum();
                    reseting();
                    scolum();
            }
            }


              function reseting(){
           $("#retpro_type").val(0);
            $("#ret_quantity").val(0);
            // $("#quantitytype").val(0);
            $("#retunit_price").val(0);
            $("#total").val(0);
              $("#ret_amt").val(0);
        }






           function edit(){


           	   var prod_id = $("#retpro_type option:selected").val();
           	   var prod_name = $("#retpro_type option:selected").html();
           	   var unitprice = $("#retunit_price").val();
               var returnQuantity = $("#ret_quantity").val();
               var reciveableAmount = $("#ret_amt").val();
               table.rows[rIndex].cells[1].innerHTML = returnQuantity;
               table.rows[rIndex].cells[3].innerHTML = reciveableAmount;
               table.rows[rIndex].cells[0].innerHTML = prod_name;
               table.rows[rIndex].cells[4].innerHTML = prod_id;
               table.rows[rIndex].cells[2].innerHTML = unitprice;

               sum();

           }


              function removeSelectedRow()
            {
                table.deleteRow(rIndex);
             $('#retpro_type option:selected').val(this.cells[0]? this.cells[0].innerHTML:'');
                         $('#ret_quantity').val(this.cells[1].innerHTML);
                         $('#retunit_price').val(this.cells[3].innerHTML);
                         $('#ret_amt').val(this.cells[4].innerHTML);

            }






           function sum(){
               var sum = 0;
               for(i = 2;i<=table.rows.length;i++){
                   var returnAm = $("#Returntable > thead > tr:nth-child("+i+") > td:nth-child(4)").text();
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
                var j = k+1;
                invalue =[];
                invalue[0]=$("#Returntable > thead > tr:nth-child("+j+") > td:nth-child(5)").text();//product Id
                invalue[1]=$("#Returntable > thead > tr:nth-child("+j+") > td:nth-child(2)").text();//return quantity
                invalue[2] = $("#row_"+k+7).text();//quantity Type id
                invalue[3] = $("#Returntable > thead > tr:nth-child("+j+") > td:nth-child(3)").text();//unit price
                invalue[4] = $("#Returntable > thead > tr:nth-child("+j+") > td:nth-child(4)").text();// amount

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
        var cust_id = $("#selUser").val();
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
          var md_name = $("#selUser > option:selected").val();
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
          // var md_p_billno = $("#billNoInput").val(); //bill number
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
      // $("#md-p-billno").text(md_p_billno);
      $("#md-p-r-billno").text(md_p_r_billno);
      $("#md-r-am-billno").text(tot_amt);
        }

          function copytab(){
            var prod_name = [];
            var return_qty = [];
            var retunit_price =[];
            var recive_amt = [];
            var len = $("#Returntable > thead > tr").length;

            var count = 1;
            for(var i =0;i<len-1;i++){
            	var j = count+1;
              prod = $("#Returntable > thead > tr:nth-child("+j+") > td:nth-child(1)").text();
              prod_name.push(prod);

              return_qtty = $("#Returntable > thead > tr:nth-child("+j+") > td:nth-child(2)").text();
              return_qty.push(return_qtty);

           return_unit = $("#Returntable > thead > tr:nth-child("+j+") > td:nth-child(3)").html();
              retunit_price.push(return_unit);

              recive_amt1 = $("#Returntable > thead > tr:nth-child("+j+") > td:nth-child(4)").text();
              recive_amt.push(recive_amt1);
              count++;
            }

            for(var i = 0; i < len-1; i++) {

              $("#confir_table").append(`<tr>
                  <td>`+prod_name[i]+`</td>

                  <td>`+return_qty[i]+`</td>
                  <td>`+retunit_price[i]+`</td>
                  <td>`+recive_amt[i]+`</td>
                </tr>`);
            }

            console.log(prod_name);
            console.log(return_qty);
            console.log(retunit_price);
            console.log(recive_amt);

          }
      </script>


    <!--      search dropdown-->
      <script src="js/jquery-3.2.1.min.js"></script>
      <script src="js/select2.min.js"></script>





      <script>
        $(document).ready(function(){

            // Initialize select2
            $("#selUser").select2();

            // Read selected option
            $('#but_read').click(function(){
                var username = $('#selUser option:selected').text();
                var userid = $('#selUser').val();

//                $('#result').html("id : " + userid + ", name : " + username);
            });
        });
        </script>


  </div>
</body>
</html>
<?php } ?>
