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
<!--<link href="css/purchaseentry.css" rel="stylesheet">-->
    <!--    search dropdown-->
     <link href="css/select2.min.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <?php
    require('header.php');
    ?>
  <div class="content-wrapper">
    <div class="container-fluid">
     <!-- customer details-->



              <div class="row"><h5 style="margin:8px 2px 10px 15px; "><u>Add Order</u></h5>
    <pre style="float:right">       								     (Note: Fields with <i class="fa fa-asterisk" style="font-size:10px;    margin: 6px 0px -20px 0px !important;color:red !important;"></i> mark are compulsory)</pre></div>

                 <form class="cust_line" action="order_module/order_creation_handler.php" method="post">
    <div class="row">


     <div class="col-md-6">
          <label >Customer Name <span class="requiredfield">*</span></label>

         <select id="address" name="customer_id" required>

<?php
            require('dbconnect.php');
            $c_type_q = " SELECT * FROM `hk_persons` WHERE person_type_id = 2 AND person_active = 1";
            $exe = mysqli_query($conn,$c_type_q);
            while($row = mysqli_fetch_array($exe)){
            ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['first_name']; ?></option>
            <?php
            }
            ?>
</select>


       <div class="orderrow"></div>
          <label >Product Name <span class="requiredfield">*</span></label>

         <select id="employee" class="orderproduct" name="product_id" required>.
             <option>-- Select Product --</option>
             <?php
             require('dbconnect.php');
            $c_type_q = " SELECT * FROM `hk_products` WHERE products_active = 1";
            $exe = mysqli_query($conn,$c_type_q);
            while($row = mysqli_fetch_array($exe)){
            ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']."-".$row['type']."-".$row['quantity_type']; ?></option>
            <?php
            }
            ?>

</select>


<div class="orderrow"></div>
               <label >Quantity <span class="requiredfield">*</span></label>
        <input id='selUser' type="number" class="orderqty2" name="quantity" placeholder=" Product Quantity..">
        </div>
           </div>
        <div class="row ">
           <button type="button" class="buttonsave btn btn-primary" onclick="addHtmlTableRow();">Add</button>
                <button  type="button" class="buttonsave1 btn btn-warning" onclick="editHtmlTbleSelectedRow();">Edit</button>
                <button type="button" class="buttonsave2 btn btn-danger" onclick="removeSelectedRow()">Remove</button>


     </div>





           <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr class="custtd">
<!--                  <th>Sl No</th>-->
                    <th style="display:none;" >Product ID </th>
                  <th>Product Name </th>
                    <th>Quantity</th>
                 </tr>
              </thead>


              <tbody>



              </tbody>
            </table>


<button  type="button" class="buttonsave3 btn btn-success" onclick="tableone()">Save</button>
              <table class="table" id="inputtable" hidden>

                    <tr>
                        <td><input type="text" id="row_21" name="order[0]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_22" name="order[0]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_23" name="order[0]['quantity_entered']" class="form-control"></td>
                    </tr>
                  <tr>
                        <td><input type="text" id="row_31" name="order[1]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_32" name="order[1]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_33" name="order[1]['quantity_entered']" class="form-control"></td>

                    </tr>
                  <tr>
                        <td><input type="text" id="row_41" name="order[2]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_42" name="order[2]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_43" name="order[2]['quantity_entered']" class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_51" name="order[3]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_52" name="order[3]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_53" name="order[3]['quantity_entered']" class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_61" name="order[4]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_62" name="order[4]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_63" name="order[4]['quantity_entered']" class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_71" name="order[5]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_72" name="order[5]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_73" name="order[5]['quantity']" class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_81" name="order[6]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_82" name="order[6]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_83" name="order[6]['quantity_entered']" class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_91" name="order[7]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_92" name="order[7]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_93" name="order[7]['quantity_entered']" class="form-control"></td>
                    </tr>
                <tr>
                        <td><input type="text" id="row_91" name="order[8]['prod_id']" class="form-control"></td>
                        <td><input type="text" id="row_92" name="order[8]['prod_name']" class="form-control"></td>
                        <td><input type="text" id="row_93" name="order[8]['quantity_entered']" class="form-control"></td>
                    </tr>

              </table>
<script>


                  function tableone(){
                      for(row = 2; row<13;row++){
                           var xvalue =[];


                            xvalue[0] = $("#dataTable > thead > tr:nth-child(" + row+ ") > td:nth-child(1)").text();
                            xvalue[1] = $("#dataTable > thead > tr:nth-child(" + row+ ") > td:nth-child(2)").text();
                            xvalue[2] = $("#dataTable > thead > tr:nth-child(" + row+ ") > td:nth-child(3)").text();

                                $('#row_'+ row +1 ).val(xvalue[0]);
                                $('#row_'+ row +2 ).val(xvalue[1]);
                                $('#row_'+ row +3 ).val(xvalue[2]);
                      }
                  }
</script>




          </div>

        </div>




    <div class="row">

       <button class="buttonsubmit" type="submit"><a >Submit</a></button>
     <a  href="order_list1.php" style="text-decoration:none;"  class="buttonreset"><span>Cancel</span></a>
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
<!--      search dropdown-->
       <script src="js/jquery-3.2.1.min.js"></script>
      <script src="js/select2.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
      <script src="script/getQuantity.js"></script>

  <script>
        $(document).ready(function(){

            // Initialize select2
            $("#address").select2();

            // Read selected option
            $('#but_read').click(function(){
                var username = $('#address option:selected').text();
                var userid = $('#address').val();

//                $('#result').html("id : " + userid + ", name : " + username);
            });
        });
        </script>

       <script>
        $(document).ready(function(){

            // Initialize select2
            $("#employee").select2();

            // Read selected option
            $('#but_read').click(function(){
                var username = $('#employee option:selected').text();
                var userid = $('#employee').val();

//                $('#result').html("id : " + userid + ", name : " + username);
            });
        });
        </script>


         <script>

            var rIndex,
                table = document.getElementById("dataTable");

            // check the empty input
            function checkEmptyInput()
            {
                var isEmpty = false,
                     pro_id =$("#employee option:selected").val(),
                    product_id = $("#employee option:selected").html(),
                   quantity= document.getElementById("selUser").value,
                    quantitytype  = $("#qqtype option:selected").html(),
                   qty_id = $("#qqtype option:selected").val();
            }

            // add Row
            function addHtmlTableRow()
            {
                if(!checkEmptyInput()){
                var newRow = table.insertRow(table.length),
                    cell1 = newRow.insertCell(0),
                    cell2 = newRow.insertCell(1),
                    cell3 = newRow.insertCell(2),
                     // cell4 = newRow.insertCell(3),
                     // cell5 = newRow.insertCell(4),
                    pro_id = $("#employee option:selected").val(),
                     product_id = $("#employee option:selected").html(),
                    quantity = document.getElementById("selUser").value;
                      // quantitytype  = $("#qqtype option:selected").html(),
                   // qty_id = $("#qqtype option:selected").val();
             cell1.innerHTML =  pro_id;
                cell2.innerHTML =  product_id;
                cell3.innerHTML = quantity;
                // cell4.innerHTML = qty_id;
                      // cell5.innerHTML =  qty_id ;
                     selectedRowToInput();
                    ordercolumn();

                // call the function to set the event to the new row

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
                        $('#employee').val(this.cells[0].innerHTML);
                        $('#selUser').val(this.cells[2].innerHTML);
//                         $('#qqtype option:selected').val(this.cells[2].innerHTML);
                        $('#qqtype').val(this.cells[4].innerHTML);

                    };
                }
            }
            selectedRowToInput();

            function editHtmlTbleSelectedRow()
            {
               var
                pro_id = document.getElementById("employee").value,
               product_id = $("#employee option:selected").html(),
  quantity = document.getElementById("selUser").value,
                   quantitytype = $("#qqtype option:selected").html(),
                 qty_id =$("#qqtype option:selected").val();

               if(!checkEmptyInput()){
                   table.rows[rIndex].cells[0].innerHTML = pro_id;
                table.rows[rIndex].cells[1].innerHTML = product_id;
                table.rows[rIndex].cells[2].innerHTML = quantity;
                table.rows[rIndex].cells[3].innerHTML = quantitytype;
                    table.rows[rIndex].cells[4].innerHTML = qty_id;
              }
            }

            function removeSelectedRow()
            {
                table.deleteRow(rIndex);
                // clear input text
                  $('#employee').val(this.cells[0]? this.cells[0].innerHTML:'');
                 $('#employee option:selected').val( this.cells[1].innerHTML);
//                document.getElementById("employee").value = "";
                 $('#selUser').val(this.cells[2].innerHTML);
                $("#qqtype option:selected").val( this.cells[3].innerHTML);
                        $('#qqtype').val(this.cells[4].innerHTML);

//                document.getElementById("selUser").value = "";
//                document.getElementById("qqtype").value = "";
            }
        </script>

       <script>
        function ordercolumn(){
for(pro=2;pro<13; pro++){
   $("#dataTable > thead > tr:nth-child("+pro+") > td:nth-child(1)").css('display','none');
                $("#dataTable > thead > tr:nth-child("+pro+") > td:nth-child(5)").css('display','none');

        }

        }
    </script>


  </div>
</body>

</html>
