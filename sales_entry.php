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
$id = $_POST["sale_id"];
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

<script>
        function salescolum(){


        for(pro = 2;pro<13; pro++){

                $("#salestable > thead > tr:nth-child("+pro+") > td:nth-child(1)").css('display','none');
        }
}

    </script>


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
                <pre style="float:right">                                                                                           (Note:Fields with <i class="fa fa-asterisk" style="font-size:10px;color:red;"></i> make are compulsory)</pre>
            </div>

        <form class="cust_line" method="POST" action="sales_entry_module/sales_entry_add.php">
<!--            <h5 style="margin: -18px 0px 8px 0px"><u>Sales Entry</u></h5>-->
<div class="row" style="margin-top:-12px; margin-left: 1px;">
            <h5><u>Sales Transaction Type:</u></h5>


     <label class="radio-inline" style="margin-left: 20px; margin-top: -10px;">
      <input type="radio" name="transType" id="scash" value="1" >CASH
    </label>
    <label class="radio-inline" style="margin-left: 18px; margin-top: -10px;">
      <input type="radio" name="transType" id="scredit" value="2">CREDIT
    </label>

     <label for="date" class="adddate1">Sales Date<span class="requiredfield">*</span></label>
     <input type="date" id="ondate" class="adddate" name="ondate" max="<?php echo date('Y-m-d'); ?>">
            </div>
     <div class="row">
        <div class="col-md-6">
            <table class="stablewidth">
                <tbody>
                <!-- order id fetch -->
                <?php
                $order_id_fetch="SELECT * FROM `hk_orders` WHERE id=".$id;
                $order_id_result=mysqli_query($conn,$order_id_fetch);
                while ($order_id_row = mysqli_fetch_assoc($order_id_result)) {
                ?>
                <input type="hidden" value="<?php echo $order_id_row["id"] ?>"
                name="order_id_fetched">
                <?php } ?>

                <tr>
                    <th>Customer Name<span class="requiredfield">*</span></th>
                    <td>
                    	<select class="saleslabel" id="cust_id" name="cust_name" readonly >
                            <?php
        $sqlpname = "SELECT HKP.*,HKP.first_name,HKP.last_name,HKPB.balance_amount FROM `hk_orders` AS HKO LEFT JOIN `hk_persons` AS HKP ON HKO.person_id=HKP.id LEFT JOIN `hk_person_balance` AS HKPB ON HKO.person_id=HKPB.person_id WHERE
        HKO.id=".$id;
        $resultset = mysqli_query($conn, $sqlpname) or die("database error:". mysqli_error($conn));
        while( $rowscust = mysqli_fetch_assoc($resultset) ) {
        ?>

        <option value="<?php echo $rowscust["id"]; ?>">
            <?php echo $rowscust["first_name"]." ".$rowscust["last_name"]; ?>
            <!-- <?php echo $rowscust["id"]; ?> -->
        </option>
     <?php } ?>
                    	</select>
                    </td>
                    </tr>

                    <tr>
                    <th>Bill Number<span class="requiredfield">*</span></th>

                          <td>
                            <?php
                            require('dbconnect.php');
                            $calcBillQ = "select MAX(id) as bill_number from `hk_sales`";
                            $calcBillExe = mysqli_query($conn,$calcBillQ);
                            while($calcBillRow = mysqli_fetch_array($calcBillExe)){
                                $billval = $calcBillRow["bill_number"];
                            }
                            $billval +=1;
                            $billNumber = "AK/S".sprintf("%05d",$billval);
                            ?>
                             <input type="text" class="salestext1"  name="bill_number" placeholder="Enter Bill  Number.."
                             value="<?php echo $billNumber; ?>" required readonly>
                        </td>
                    </tr>
                    <tr>
                     <th>Weigh Bill Number</th>
                         <td>
                             <input type="text" class="salestext1"  name="weigh_number" placeholder="Enter weigh bill number.." >
                        </td>
                    </tr>
                     <tr>
                     <th>Vehicle Number</th>
                          <td>
                             <input type="text" class="salestext1"  name="vehicle_number" placeholder="Enter vechicle number.." >
                        </td>

                    </tr>
                    <tr>
                    <th>Location</th>
                         <td>
                       <input type="text"  class="salestext1" name="loading_location" placeholder="Enter Location.." >
                    </td>
                    </tr>
                </tbody>
            </table>
        </div>



  <div class="col-md-6">
            <table class="stablewidth">
                <tbody>
                   <tr>
                    <th>Product Name<span class="requiredfield">*</span></th>
                    <td>
<!--                        <input type="text"  class="salestext1" id="product_type" name="product_type" placeholder="Enter Product Type.." value="" required>-->

                         <select id="product_type" class="salesqty3" name="product_type" disabled>
        <?php
        $sql = "SELECT * FROM `hk_products`";
        $resultsetP = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
        while( $rows = mysqli_fetch_assoc($resultsetP) ) {
        ?>

        <option value="<?php echo $rows["id"]; ?>"><?php echo $rows["name"]." ".$rows["type"]; ?></option>
     <?php } ?>
                        </select>

                    </td>
                    </tr>
                   <tr>

                    <th>Sales Quantity<span class="requiredfield">*</span></th>
                        <td>
                             <input type="text" class="salestextt1" id="quantity" name="sale_quantity" onkeypress='validate(event)' placeholder="sales quantity.." value="" required readonly>
 <select class="salesqty1" id="quantitytype" name="quantitytype" disabled>
        <?php
        $sqlQty = "SELECT * FROM `hk_quantity_type`";
        $resultsetQty = mysqli_query($conn, $sqlQty) or die("database error:". mysqli_error($conn));
        while( $rowsQty = mysqli_fetch_assoc($resultsetQty) ) {
        ?>

        <option value="<?php echo $rowsQty["id"]; ?>"><?php echo $rowsQty["quantity_type"]; ?></option>
     <?php } ?>

                            </select>
                        </td>

                    </tr>

                    <tr>
                    <th>Unit Price<span class="requiredfield">*</span></th>
                         <td>
                             <input type="text" class="salestext3" id="unit_price" onchange="finalQuantity()"
        name="unit_price" placeholder="Enter unit price.." onkeypress='validate(event)'>
                        </td>

                    </tr>

                    <tr>
                    <th>Amount<span class="requiredfield">*</span></th>
                         <td>
                            <input type="number" class="salestext3" id="total" name="product_amount" placeholder="Total Product Amount.."  readonly>
                        </td>

                    </tr>

                     <tr>
                    <th>Available Stock<span class="requiredfield">*</span></th>
                         <td>
     <input type="number" id="avail" class="salestext3" name="stock_qty" placeholder="Available Stock.." readonly>

 <!-- <?php echo $rowsStock["quantity"]; ?>

    <?php
        $sqlStock = "SELECT * FROM `hk_stocks`";
        $resultsetStock = mysqli_query($conn,$sqlStock) or die("database error:". mysqli_error($conn));
        while( $rowsStock = mysqli_fetch_assoc($resultsetStock) ) {
        ?>


            <?php } ?> -->

                        </td>
                    </tr>


                </tbody>
            </table>
         </div>
     </div>
            <div class="row ">
                 <button class="buttonsave1 btn btn-warning saveedit" type="button" onclick="editHtmlTbleSelectedRow();">Edit</button>
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
                    <th>Quantity Type </th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                     <th style = "display:none">Product ID</th>
                     <th style = "display:none">Qty Type ID</th>
                     <th>Available Stock</th>

                 </tr>



              </thead>
             <tbody>
  <?php
                 require('dbconnect.php');
                 $Order_Query ="SELECT HKOP.*,HKP.name,HKP.id AS productid,HKP.type,HKQT.quantity_type,HKQT.id AS quantityid,HKS.quantity AS stock_quantity,HKS.id AS stock_id FROM `hk_ordered_products` AS HKOP LEFT JOIN `hk_products` AS HKP ON HKOP.product_id=HKP.id LEFT JOIN `hk_quantity_type` AS HKQT ON HKOP.quantity_type_id=HKQT.id
                 LEFT JOIN `hk_stocks` AS HKS ON HKS.product_id=HKOP.product_id AND HKS.quantity_type_id=HKOP.quantity_type_id
                    WHERE HKOP.order_id=".$id;
                 $OrderEntry_exe = mysqli_query($conn,$Order_Query);
                 $i=1;
                  ?>
                  <?php
                 while($Order_Sale_row = mysqli_fetch_array($OrderEntry_exe))
                  {
              ?>

                <td><?php echo $Order_Sale_row['name']." ".$Order_Sale_row['type']; ?></td>
                <td><?php echo $Order_Sale_row['quantity']; ?></td>
                <td><?php echo $Order_Sale_row['quantity_type']; ?></td>
                <td></td>
                <td></td>
                <td style = "display:none"><?php echo $Order_Sale_row['productid']; ?></td>
                <td style = "display:none"><?php echo $Order_Sale_row['quantityid']; ?></td>
                <td><?php echo $Order_Sale_row['stock_quantity']; ?></td>
                <input type="hidden" value="<?php echo $Order_Sale_row['id']; ?>" name="order_product_id">




<!--
                 <tr>
                 <td>raspury</td>
                 <td>120</td>
                 <td>kg</td>
                 <td>10</td>
                 <td>1200</td>
                     <td>1</td>
                     <td>1</td>
                 </tr>

                 <tr>
                 <td>mallika</td>
                 <td>563</td>
                 <td>crates</td>
                 <td>56</td>
                 <td>4641154</td>
                     <td>2</td>
                     <td>2</td>
                 </tr>

                 <tr>
                 <td>tothapuri</td>
                 <td>64</td>
                 <td>pieces</td>
                 <td>8754</td>
                 <td>54454554</td>
                     <td>3</td>
                     <td>3</td>
                 </tr>

                 <tr>
                 <td>jeerge</td>
                 <td>96679</td>
                 <td>kg</td>
                 <td>15745</td>
                 <td>8785475487</td>
                     <td>4</td>
                     <td>1</td>
                </tr>
-->



              </tbody>
              <?php } ?>
            </table>

               <button onclick="tableone()" class="buttonsave3 btn btn-success sedit" type="button">Save</button>

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
                        <td><input type="text" id="row_73" name="sale[5]['quantity']" class="form-control"></td>
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
                        <td><input type="text" id="row_91" name="sale[8]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_92" name="sale[8]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_93" name="sale[8]['quantity_entered']" class="form-control"></td>
                        <td><input type="text" id="row_94" name="sale[8]['qty_type']" class="form-control"></td>
                        <td><input type="text" id="row_95" name="sale[8]['qty_type_id']" class="form-control"></td>
                    </tr>

              </table>

          </div>

        </div>
    </div>

    <div class="row">
         <div class="col-md-6">
            <table class="stablewidth">
                <tbody>


                     <tr>
                    <th>Total Product Amount</th>

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
                        <th>Balance Receivable</th>
                         <td>
                        <?php
        $balance = "SELECT HKPB.balance_amount FROM `hk_orders` AS HKO LEFT JOIN `hk_persons` AS HKP ON HKO.person_id=HKP.id LEFT JOIN `hk_person_balance` AS HKPB ON HKO.person_id=HKPB.person_id WHERE
        HKO.id=".$id;
        $resultsetbal = mysqli_query($conn, $balance) or die("database error:". mysqli_error($conn));
        while( $rowscustbal = mysqli_fetch_assoc($resultsetbal) ) {
        ?>
                        <input type="text" class="salestext2" id="balrecvable"
                        value="<?php echo $rowscustbal["balance_amount"]; ?>" name="balrec" readonly style="margin-left: -8px;width: 100%;" readonly>
                        <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Balance Received</th>
                         <td>
                        <input type="text" id="balpaid"  onblur="expensescom()" value="0" class="salestext2" name="balrece" style="margin-left: -8px; width: 100%;">
                        </td>
                    </tr>






                </tbody>
             </table>
         </div>

        <div class="col-md-6">
 <div class="row">

          <div class="col-md-4">
               <h6><u>Select Expenses</u></h6>
               <table>
                <tbody>
                <tr>
    <th>Expenses<span class="requiredfield">*</span></th>
     <td>
     <select id="expenses" class="expen" name="expenseslist1">
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
                        <input type="text" id="expensevalue1" class="stext4" name="expensevalue1" placeholder="Amount.." value="0" >

                    </td>
                </tr>
                 </tbody>
            </table>
      </div>
            </div>
             <div class="row salessave">

           <button class="buttonsave btn btn-primary" type="button" onclick="addHtmlTableRow1()" style="margin-right: 2px;">Add</button>
                <button class="buttonsave1 btn btn-warning" type="button" onclick="editHtmlTbleSelectedRow1();" style="margin-right: 2px;">Edit</button>
                <button class="buttonsave2 btn btn-danger" type="button" onclick="removeSelectedRow1()" style="margin-right: 2px;">Remove</button>

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

            <button  onclick="expensetable()" type="button" class="buttonsave3 btn btn-success sedit1 ">Save</button>

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
<!--
         <h5><u>Sales Transaction Type</u></h5>


                     <label class="radio-inline">
      <input type="radio" name="transType" id="scash" value="1" >CASH
    </label>
    <label class="radio-inline">
      <input type="radio" name="transType" id="scredit" value="2">CREDIT
    </label>
-->




                     <h5 style="margin-top: -10px;"><u>Payment Details</u></h5>
                     <table>
                            <tbody>

                             <tr>
                    <th>Total Receivable Amount<span class="requiredfield">*</span></th>

                        <td>
                           <input type="text" id="storedcal"
                           class="salestextt2"  name="rec_amount"
                           placeholder="Total Receivable Amount" onkeypress='validate(event)' required>
                        </td>

                    </tr>



                            <tr>
                        <th>Total expense Amount <span class="requiredfield">*</span></th>
                        <td>
                            <input type="text" class="salestext2" value="0" onblur="expensecall()" id="totalExpense" name="expense_amt" placeholder="Expense Amount.." readonly>
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
                           placeholder="Total Recivable Amount" onkeypress='validate(event)'>
                        </td>

                    </tr>

                    <tr>
                        <th>Total Amount Received <span class="requiredfield">*</span></th>
                        <td> <input type="text" class="salestext2" id="totalPaid" onchange="duecalc()" name="totalPaid" placeholder="Amount Received.." value="0" required></td>

                    </tr>
                    <tr>
                        <th>Balance Amount<span class="requiredfield">*</span></th>
                        <td><input type="text" class="salestext2" id="duepay" value="0" placeholder="Balance Amount.." name="duepay" readonly></td>

                    </tr>

                     <tr>
                    <th>Enter Cheque Number</th>
                    <td><input type="text" class="salestext2" name="chequeNumber" id="schequenumber" placeholder="Enter cheque number." value="" style= "margin-left: 9px;width: 100%;"></td>
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


 <div class="row" style="    margin-left: 378px;
    margin-top: 20px;">

       <button class="buttonsubmit" type="submit"><a >Submit</a></button>
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
       <script src="js/salestable.js"></script>
    <!-- Custom scripts for this page-->
    <script src="js/sb-admin-datatables.min.js"></script>
   <script src="js/supplierdetails.js"></script>
   <script type="text/javascript" src="script/sales_data.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

       <script>
            var rIndex,
                table = document.getElementById("dataTable");

            // check the empty input
            function checkEmptyInput()
            {
                var isEmpty = false,
//                     product_id =$("#product_type option:selected").val(),
                    product_type = document.getElementById("product_type").value,
                   sale_quantity= document.getElementById("quantity").value,
                    quantitytype  = $("#quantitytype option:selected").html(),
                     unit_price  = document.getElementById("unit_price").value,
                     product_amount  = document.getElementById("total").value,
                    product_id =$("#product_type option:selected").val(),
                    quantity_id =$("#quantitytype option:selected").val(),
                   stock_qty =document.getElementById("avail").value;
                    // product_amount_id = $("#avail option:selected").val();
            }
           function reseting(){
           $("#product_type").val(0);
            $("#quantity").val(0);
            $("#quantitytype").val(0);
            $("#unit_price").val(0);
            $("#total").val(0);
              $("#avail").val(0);

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
                         $('#product_type').val(this.cells[5].innerHTML);
                         $('#quantity').val(this.cells[1].innerHTML);
                         $('#quantitytype').val(this.cells[6].innerHTML);
                         $('#unit_price').val(this.cells[3].innerHTML);
                         $('#total').val(this.cells[4].innerHTML);
                          $('#avail').val(this.cells[7].innerHTML);
                    };
                }
            }
            selectedRowToInput();


            function editHtmlTbleSelectedRow()
            {
               var
                product_type = $("#product_type option:selected").html(),
                   sale_quantity= document.getElementById("quantity").value,
                    quantitytype  = $("#quantitytype option:selected").html(),
                     unit_price  = document.getElementById("unit_price").value,
                     product_amount  = document.getElementById("total").value,
                    product_id =$("#product_type option:selected").val(),
                    quantity_id =$("#quantitytype option:selected").val(),
                     stock_qty =document.getElementById("avail").value;
                    // product_amount_id = $("#avail option:selected").val();

               if(!checkEmptyInput()){
                   table.rows[rIndex].cells[0].innerHTML = product_type;
                table.rows[rIndex].cells[1].innerHTML = sale_quantity;
                table.rows[rIndex].cells[2].innerHTML = quantitytype ;
                table.rows[rIndex].cells[3].innerHTML =  unit_price ;
                    table.rows[rIndex].cells[4].innerHTML =  product_amount;
                    table.rows[rIndex].cells[5].innerHTML =  product_id;
                    table.rows[rIndex].cells[6].innerHTML =  quantity_id;
                    table.rows[rIndex].cells[7].innerHTML = stock_qty;
                    // table.rows[rIndex].cells[8].innerHTML =  product_amount_id;
              }
                 reseting();
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

                  function tableone(){
                       var sum=0;
                      for(row = 2; row<13;row++){

                           var xvalue =[];
                            xvalue[0] = $("#dataTable > tbody:nth-child(" + row+ ") > tr > td:nth-child(2)").text();

                            xvalue[1] = $("#dataTable > tbody:nth-child(" + row+ ") > tr >td:nth-child(4)").text();
                            xvalue[2] = $("#dataTable > tbody:nth-child(" + row+ ") > tr >td:nth-child(5)").text();
                            xvalue[3] = $("#dataTable > tbody:nth-child(" + row+ ") > tr >td:nth-child(6)").text();
                            xvalue[4] = $("#dataTable > tbody:nth-child(" + row+ ") > tr >td:nth-child(7)").text();

                            var value=$("#dataTable > tbody:nth-child(" + row+ ") > tr >td:nth-child(5)").text();

                                $('#row_'+ row +1 ).val(xvalue[0]);
                                $('#row_'+ row +2 ).val(xvalue[1]);
                                $('#row_'+ row +3 ).val(xvalue[2]);
                                $('#row_'+ row +4 ).val(xvalue[3]);
                                $('#row_'+ row +5 ).val(xvalue[4]);


                                if (!isNaN(value) && value.length !=0) {
                                    sum+=parseFloat(value);
                                }
                                $("#stotalreceivable").val(sum);

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
              var prebalpaid = $("#balpaid").val();

    		  // var totalexpense = (parseFloat(labour)+parseFloat(trans)+parseFloat(post)+parseFloat(miscellaneous));
			  var pay = (parseFloat(amountCal)-parseFloat(com))+parseFloat(prebalpaid);
//              var pays=parseFloat(totexp)+pay;
              	$("#storedcal").val(pay);

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


<script>
    function setadvance(){
        if($("#advance").val()=="")
        {
            $("#advance").val("0");
        }

    }
</script>







    </div>
</body>

</html>

  <?php }	?>
